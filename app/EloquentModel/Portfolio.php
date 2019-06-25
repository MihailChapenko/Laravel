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
        'sort_order', 'is_active', 'admin_id', 'parent_name'
    ];

    public function getPortfoliosList($user)
    {
        if(Auth::id() === 1)
        {
            return Portfolio::all();
        }

        return Portfolio::where('client_id', '=', $user->client_id)
//                        ->whereColumn('parent_id', '=', 'id')
                        ->get();
    }

    public function getAvailablePortfolio()
    {
        return Portfolio::where('client_id', '=', 0)
                        ->where('parent_id', '=', 0)->get();
    }

//    public function getAllParentPorfolios()
//    {
//        return Portfolio::where('parent_id', '=', 0)->get();
//    }

    public function getParentPortfolio($data)
    {
        return Portfolio::where('id', '=', $data['parent_id'])->first();
    }
}
