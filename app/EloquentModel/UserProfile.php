<?php

namespace App\EloquentModel;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserProfile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'first_name', 'last_name', 'address', 'phone', 'is_admin', 'is_active', 'client_id', 'admin_id'
    ];

    public function getUserInfo($id)
    {
        return UserProfile::join('users', 'users.id', '=', 'users_profile.user_id')
                        ->select('users_profile.user_id', 'users_profile.first_name', 'users_profile.last_name',
                            'users_profile.address', 'users_profile.phone', 'users_profile.client_id',
                            'users_profile.is_admin', 'users_profile.is_active', 'users_profile.created_at', 'users.email')
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
        ]);

        return true;
    }

    public function findProfile($id)
    {
        return UserProfile::where('user_id', $id)->first();
    }
}
