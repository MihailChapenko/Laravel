<?php

namespace App\Http\Controllers\Portfolio;

use App\User;
use App\Http\Requests\{
    DeletePortfolioRequest,
    AddPortfolioRequest,
    EditPortfolioRequest
};
use Illuminate\Http\Request;
use App\EloquentModel\Currency;
use App\EloquentModel\Portfolio;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PortfolioController extends Controller
{
    private $user;
    private $currency;
    private $portfolio;

    public function __construct(Portfolio $portfolio, User $user, Currency $currency)
    {
        $this->user = $user;
        $this->currency = $currency;
        $this->portfolio = $portfolio;
    }

    public function index()
    {
        $userInfo = $this->user->getUserInfo(Auth::id());
        $currency = $this->currency->all();

        if(Auth::id() === 1)
        {
            $parentPortfolios = $this->portfolio->getAllPortfolios();
        }
        else
        {
            $parentPortfolios = $this->portfolio->getAllClientPortfolios($userInfo);
        }

        return view('portfolio.info_portfolio', compact('currency', 'parentPortfolios'));
    }

    public function getPortfolioList()
    {
        $user = $this->user->getUserInfo(Auth::id());

        if(Auth::id() === 1)
        {
            $portfolio = $this->portfolio->getAllPortfolios();
        }
        else
        {
            $portfolio = $this->portfolio->getPortfoliosList($user);
        }

        return DataTables::of($portfolio)->setRowClass(
            'portfolio-info'
        )->setRowAttr([
            'id-portfolio' => '{{$id}}',
        ])->make();
    }

    public function addPortfolio(AddPortfolioRequest $request)
    {
        $admin = Auth::user()->findAdmin(Auth::id());

        if(Auth::id() === 1 && $request->input('portfolioParentId') == 0)
        {
            $adminTopPortfolio = null;
        }
        else if($request->input('portfolioParentId') == 0)
        {
            $adminTopPortfolio = $this->portfolio->findTopPortfolioForAdmin($admin->client_id);
        }
        else
        {
            $adminTopPortfolio = $this->portfolio->findParentPortfolio($request->input('portfolioParentId'));
        }

        $newPortfolio = [
            'client_id' => $request->input('portfolioClientId'),
            'parent_id' => $request->input('portfolioParentId'),
            'parent_name' => $request->input('portfolioParentName'),
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

        if($adminTopPortfolio)
        {
            $newPortfolio['parent_id'] = $adminTopPortfolio->id;
            $newPortfolio['client_id'] = $adminTopPortfolio->client_id;
        }
        else
        {
            $newPortfolio['parent_id'] = $request->input('portfolioParentId');
            $newPortfolio['client_id'] = $admin->client_id;
        }

        $portfolio = $this->portfolio->create($newPortfolio);

        return response()->json(['success' => true, 'portfolio' => $portfolio]);
    }

    public function findPortfolio(Request $request)
    {
        $portfolioInfo = $this->portfolio->find($request->input('portfolioId'));
        $permission = Auth::user()->can('crud portfolios');

        if(!$permission)
        {
            return response()->json(['error' => 'No permissions to edit portfolios']);
        }

        return response()->json(['success' => true, 'portfolioInfo' => $portfolioInfo]);
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
            'is_active' => intval($request->input('editPortfolioIsActive'))
        ];

        if($request->input('portfolioId') === $request->input('parentPortfolioId'))
        {
            $newData['parent_id'] = 0;
        }
        else
        {
            $newData['parent_id'] = $request->input('parentPortfolioId');
        }

        $this->portfolio->find($request->input('portfolioId'))->update($newData);

        return response()->json(['success' => true]);
    }

    public function deletePortfolio(DeletePortfolioRequest $request)
    {
        $this->portfolio->find($request->input('portfolioId'))->delete();

        return response()->json(['success' => true]);
    }
}
