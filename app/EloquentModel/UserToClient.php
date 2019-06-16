<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;

class UserToClient extends Model
{
    protected $table = 'users_to_client';

    protected $fillable = [
      'user_id', 'client_id', 'admin_id'
    ];
}
