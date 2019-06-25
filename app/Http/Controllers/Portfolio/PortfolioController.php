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
        $currency = $this->currency->all();
//        $parentPortfolios = $this->portfolio->getAllParentPorfolios();
        $parentPortfolios = $this->portfolio->all();

        return view('portfolio.info_portfolio', compact('currency', 'parentPortfolios'));
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

        $newPortfolio = [
            'parent_name' => $request->input('portfolioParentName'),
            'parent_id' => $request->input('portfolioParentId'),
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

        if($request->input('portfolioClientId') != 0)
        {
            $newPortfolio['client_id'] = $request->input('portfolioClientId');
        }
        else if($admin->client_id != 0)
        {
            $newPortfolio['client_id'] = $admin->client_id;
        }
        else
        {
            $newPortfolio['client_id'] = 0;
        }

        $portfolio = $this->portfolio->create($newPortfolio);

        return response()->json(['success' => true, 'portfolio' => $portfolio]);
    }

    public function findPortfolio(Request $request)
    {
        $portfolioInfo = $this->portfolio->find($request->input('portfolioId'));
        $parentPortfolioInfo = $this->portfolio->getParentPortfolio($portfolioInfo);
        $permission = Auth::user()->can('crud portfolios');

        if(!$permission)
        {
            return response()->json(['error' => 'No permissions to edit portfolios']);
        }

        return response()->json(['success' => true, 'portfolioInfo' => $portfolioInfo, 'parentPortfolioInfo' => $parentPortfolioInfo]);
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
