<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Portfolio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'portfolios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'parent_id', 'benchmark_id', 'name', 'description', 'currency', 'allocation_min', 'allocation_max',
        'sort_order', 'is_active', 'admin_id'
    ];

    public function getPortfoliosList($user)
    {
        if(Auth::id() === 1)
        {
            return Portfolio::all();
        }

        return Portfolio::where('portfolios.client_id', '=', $user->client_id)
                        ->where('admin_id', '!=', $user->admin_id)
                        ->where('parent_id', '!=', 0)
                        ->get();
    }

    public function getAvailablePortfolio()
    {
        return Portfolio::where('client_id', '=', 0)->get();
    }

    public function findPortfolio($id)
    {
        return Portfolio::where('client_id', '=', $id)->first();
    }

    public function findParentPortfolio($id)
    {
        return Portfolio::where('client_id', '=', $id)
                        ->where('admin_id', '!=', 1)
                        ->where('admin_id', '!=', Auth::id())->first();
    }
}
