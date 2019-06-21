<?php

namespace App\Http\Controllers\User\User;

use App\User;
use App\Http\Requests\{
    AddUserRequest,
    EditUserRequest,
    ChangeUserPassRequest,
    DeleteUserRequest
};
use App\EloquentModel\{
    UserProfile as Profile
};
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{
    Auth, Hash
};
use Spatie\Permission\Models\Permission;

class UserController extends Controller
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
        $permissions = Permission::where('name', '!=', 'crud clients')->where('name', '!=', 'view clients')->get();

        return view('users.users.info_users', compact('permissions', 'permissions'));
    }

    public function getUsersList()
    {
        $userInfo = $this->user->getUserInfo(Auth::id());
        $users = Auth::user()->getUsersList($userInfo);

        return DataTables::of($users)->setRowClass(
            ( '{{$is_admin ? "admin user-info" : "user-info"}}' )
        )->setRowAttr([
            'id-user' => '{{$id}}',
        ])->make(true);
    }

    public function findUser(Request $request)
    {
        $user = Auth::user()->getUserInfo($request->input('userId'));
        $userPermissions = $user->permissions()->get();
        $permission = Auth::user()->can('crud users');

        if(!$permission)
        {
            return response()->json(['error' => 'No permissions to edit user']);
        }

        return response()->json(['success' => true, 'user' => $user, 'userPermissions' => $userPermissions]);
    }

    public function addUser(AddUserRequest $request)
    {
        $admin = Auth::user()->findAdmin(Auth::id());

        $data = [
          'name' => $request->input('userName'),
          'email' => $request->input('userEmail'),
          'password' => Hash::make($request->input('userPass'))
        ];

        $user = User::create($data);

        if(Auth::id() === 1)
        {
            $this->profile->findProfile($user->id)->update([
                'is_admin' => true,
                'admin_id' => Auth::id()
            ]);
        }
        else
        {
            $this->profile->findProfile($user->id)->update([
                'client_id' => $admin->client_id,
                'admin_id' => Auth::id()
            ]);
        }

        foreach ($request->input('adminPermissions') as $permission)
        {
            $user->givePermissionTo(intval($permission));
        }

        return response()->json(['success' => true]);
    }

    public function editUser(EditUserRequest $request)
    {
        $data = [
            'name' => $request->input('editUserName'),
            'email' => $request->input('editUserEmail')
        ];
        if(!empty($request->input('editUserPass')))
        {
            $data['password'] = Hash::make($request->input('editUserPass'));
        }

        if(!is_null($request->input('isActive')))
        {
            $this->profile->findProfile($request->input('userId'))->update(['is_active' => $request->input('isActive')]);
        }

        $this->user->find($request->input('userId'))->update($data);

        if(!empty($request->input('adminNewPermissions') && Auth::id() === 1))
        {
            $permissions = [];
            foreach ($request->input('adminNewPermissions') as $permission)
            {
                array_push($permissions, intval($permission));
            }

            $this->user->find($request->input('userId'))->syncPermissions($permissions);
        }

        return response()->json(['success' => true]);
    }

    public function deleteUser(DeleteUserRequest $request)
    {
        $this->user->find($request->input('userId'))->delete();

        return response()->json(['success' => true]);
    }

    public function changePassword(ChangeUserPassRequest $request)
    {
        $this->user->find($request->input('userId'))->update(['password' => Hash::make($request->input('password'))]);

        return response()->json(['success' => true]);
    }
}
