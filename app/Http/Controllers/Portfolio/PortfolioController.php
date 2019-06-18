<?php

namespace App\Http\Controllers\Portfolio;

use App\Http\Requests\DeletePortfolioRequest;
use App\User;
use App\Http\Requests\AddPortfolioRequest;
use App\Http\Requests\EditPortfolioRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EloquentModel\Portfolio;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


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
        $portfolio = $this->portfolio->all();

        return DataTables::of($portfolio)->setRowClass(
            'portfolio-info'
        )->setRowAttr([
            'id-portfolio' => '{{$id}}',
        ])->toJson();
    }

    public function addPortfolio(AddPortfolioRequest $request)
    {
        $admin = $this->user->findAdmin(Auth::id());

        $newPortfolio = [
            'client_id' => 0,
            'parent_id' => 0,
            'benchmark_id' => 0,
            'name' => $request->input('portfolioName'),
            'description' => $request->input('portfolioDescription'),
            'currency' => $request->input('portfolioCurrency'),
            'allocation_max' => $request->input('portfolioAllocationMax'),
            'allocation_min' => $request->input('portfolioAllocationMin'),
            'sort_order' => $request->input('portfolioSortOrder'),
            'isActive' => 1
        ];



        $this->portfolio->create($newPortfolio);

        return response()->json(['success' => true]);
    }

    public function findPortfolio(Request $request)
    {
        $portfolio = $this->portfolio->find($request->input('portfolioId'));

        return response()->json(['success' => true, 'portfolio' => $portfolio]);
    }

    public function editPortfolio(EditPortfolioRequest $request)
    {
        $newData = [
            'client_id' => 0,
            'parent_id' => 0,
            'benchmark_id' => 0,
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
