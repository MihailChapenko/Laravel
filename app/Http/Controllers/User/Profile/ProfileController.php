<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Requests\UpdateProfileRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EloquentModel\UserProfile as Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;
    private $profile;

    public function __construct(User $user, Profile $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
    }
    public function index()
    {
        $userInfo = $this->profile->getUserInfo(Auth::id());

        return view('users/users/user_profile', compact('userInfo'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->profile->updateProfile($request->all());
        $this->user->find(intval($request->input('userId')))->update(['email' => $request->input('email')]);

        return response()->json(['success' => true]);
    }

}
