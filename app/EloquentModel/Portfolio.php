<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';

    protected $fillable = [
        'client_id', 'parent_id', 'benchmark_id', 'name', 'description', 'currency', 'allocation_min', 'allocation_max',
        'sort_order', 'isActive'
    ];

    public function getAvailablePortfolio()
    {
        return Portfolio::where('client_id', '=', 0)->get();
    }
}
