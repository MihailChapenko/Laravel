<?php

namespace App\Observers;

use App\User;
use App\EloquentModel\UserProfile;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if(Auth::id() === 1)
        {
            UserProfile::create([
                'user_id' => $user->id,
                'isAdmin' => true,
                'isActive' => true
            ]);
        }
        else
        {
            UserProfile::create([
                'user_id' => $user->id,
                'isAdmin' => false,
                'isActive' => true
            ]);
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\User $user
     * @return void
     * @throws \Exception
     */
    public function deleted(User $user)
    {
        UserProfile::where('user_id', $user->id)->delete();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
