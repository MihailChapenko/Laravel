<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserProfile extends Model
{
    protected $table = 'users_profile';

    protected $fillable = [
      'user_id', 'first_name', 'last_name', 'address', 'phone', 'isAdmin', 'isActive', 'client_id'
    ];

    public function getUserInfo($id)
    {
        return UserProfile::join('users', 'users.id', '=', 'users_profile.user_id')
                        ->select('users_profile.user_id', 'users_profile.first_name', 'users_profile.last_name',
                            'users_profile.address', 'users_profile.phone', 'users_profile.client_id',
                            'users_profile.isAdmin', 'users_profile.isActive', 'users_profile.created_at', 'users.email')
                        ->where('users_profile.user_id', '=', $id)
                        ->first();
    }

    public function updateProfile($data)
    {
        UserProfile::where('user_id', '=', $data['userId'])->update([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'isActive' => intval($data['isActive']),
        ]);

        return true;
    }

    public function findProfile($id)
    {
        return UserProfile::where('user_id', $id)->first();
    }
}
