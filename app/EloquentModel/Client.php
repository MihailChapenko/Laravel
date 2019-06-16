<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'admin_id', 'name', 'model', 'theme', 'valuetable'
    ];
}
