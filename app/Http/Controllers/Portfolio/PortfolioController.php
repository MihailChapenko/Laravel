<?php

namespace App\Http\Controllers\Portfolio;

use App\User;
use App\Http\Requests\{
    DeletePortfolioRequest,
    AddPortfolioRequest,
    EditPortfolioRequest
};
use Illuminate\Http\Request;
use App\EloquentModel\Portfolio;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PortfolioController extends Controller
{
    private $user;
    private $portfolio;

    public function __construct(Portfolio $portfolio, User $user)
    {
        $this->user = $user;
        $this->portfolio = $portfolio;
    }

    public function index()
    {
        return view('portfolio.info_portfolio');
    }

    public function getPortfolioList()
    {
        $user = $this->user->getUserInfo(Auth::id());
        $portfolio = $this->portfolio->getPortfoliosList($user);

        return DataTables::of($portfolio)->setRowClass(
            'portfolio-info'
        )->setRowAttr([
            'id-portfolio' => '{{$id}}',
        ])->make();
    }

    public function addPortfolio(AddPortfolioRequest $request)
    {
        $admin = Auth::user()->findAdmin(Auth::id());
        $parentPortfolio = $this->portfolio->findParentPortfolio($admin->client_id);

        if(is_null($parentPortfolio))
        {
            $parentPortfolio = $this->portfolio->findPortfolio($admin->client_id);
        }

        $newPortfolio = [
            'admin_id' => ($admin->id) ? $admin->id : Auth::id(),
            'benchmark_id' => 0,
            'name' => $request->input('portfolioName'),
            'description' => $request->input('portfolioDescription'),
            'currency' => $request->input('portfolioCurrency'),
            'allocation_max' => $request->input('portfolioAllocationMax'),
            'allocation_min' => $request->input('portfolioAllocationMin'),
            'sort_order' => $request->input('portfolioSortOrder'),
            'is_active' => 1
        ];

        if(Auth::id() === 1)
        {
            $newPortfolio['client_id'] = 0;
            $newPortfolio['parent_id'] = 0;
        }
        else
        {
            $newPortfolio['client_id'] = $admin['client_id'];
            $newPortfolio['parent_id'] = $parentPortfolio['id'];
        }

        $this->portfolio->create($newPortfolio);

        return response()->json(['success' => true]);
    }

    public function findPortfolio(Request $request)
    {
        $portfolio = $this->portfolio->find($request->input('portfolioId'));
        $permission = Auth::user()->can('crud portfolios');

        if(!$permission)
        {
            return response()->json(['error' => 'No permissions to edit portfolios']);
        }

        return response()->json(['success' => true, 'portfolio' => $portfolio]);
    }

    public function editPortfolio(EditPortfolioRequest $request)
    {
        $newData = [
            'name' => $request->input('editPortfolioName'),
            'description' => $request->input('editPortfolioDescription'),
            'currency' => $request->input('editPortfolioCurrency'),
            'allocation_max' => $request->input('editPortfolioAllocationMax'),
            'allocation_min' => $request->input('editPortfolioAllocationMin'),
            'sort_order' => $request->input('editPortfolioSortOrder'),
            'isActive' => intval($request->input('editPortfolioIsActive'))
        ];

        $this->portfolio->find($request->input('portfolioId'))->update($newData);

        return response()->json(['success' => true]);
    }

    public function deletePortfolio(DeletePortfolioRequest $request)
    {
        $this->portfolio->find($request->input('portfolioId'))->delete();

        return response()->json(['success' => true]);
    }
}
