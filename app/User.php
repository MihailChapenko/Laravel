<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\{
    HasRoles, HasPermissions
};
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
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
            return User::join('users_profile', 'users_profile.user_id', '=', 'users.id')
                        ->select('users.id', 'users.name', 'users.email', 'users_profile.is_admin', 'users_profile.is_active')
                        ->where('users.id', '!=', 1)->get();
        }

        return User::join('users_profile', 'users_profile.user_id', '=', 'users.id')
                    ->select('users.id', 'users.name', 'users.email', 'users_profile.is_admin')
                    ->where('users_profile.is_admin', '!=', 1)
                    ->where('users.id', '!=', Auth::id())
                    ->where('users_profile.admin_id', '=', Auth::id())->get();
    }

    public function findAdmin($id)
    {
        return User::join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->select('users_profile.client_id', 'users_profile.user_id', 'users_profile.admin_id')
            ->where('users.id', '=', $id)->first();
    }

    public function getAvailibleAdmins()
    {
        return User::join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->select('users.id', 'users.email')
            ->where('users.id', '!=', 1)
            ->where('is_admin', '=', 1)
            ->where('client_id', '=', 0)->get();
    }

    public function getUserInfo($id)
    {
        return User::join('users_profile', 'users_profile.user_id', '=', 'users.id')
                    ->select('users.id', 'users.name', 'users.email', 'users_profile.is_active',
                             'users_profile.admin_id', 'users_profile.client_id')
                    ->where('users.id', '=', $id)->first();
    }
}
