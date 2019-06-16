<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'client_id', 'isAdmin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUsersList()
    {
        if(Auth::id() === 1)
        {
            return User::where('id', '!=', 1)->get();
        }
        return User::join('users_to_client', 'users_to_client.user_id', '=', 'users.id')
                    ->select('users.id', 'users.name', 'users.email')
                    ->where('users_to_client.admin_id', '=', Auth::id())->get();
    }

    public function getAvailibleAdmins()
    {
        return User::select('users.id', 'users.email')
            ->where('isAdmin', '=', 1)
            ->where('client_id', '=', 0)->get();
    }
}
