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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $permissions = Permission::all();

        return view('users.users.info_users', compact('permissions'));
    }

    public function getUsersList()
    {
        $users = Auth::user()->getUsersList();

        return DataTables::of($users)->setRowClass(
            ( '{{$is_admin ? "admin user-info" : "user-info"}}' )
        )->setRowAttr([
            'id-user' => '{{$id}}',
        ])->make();
    }

    public function findUser(Request $request)
    {
        $user = Auth::user()->getUserInfo($request->input('userId'));

        return response()->json(['success' => true, 'user' => $user]);
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
            $user->assignRole('admin');

            foreach ($request->input('adminPermissions') as $permission)
            {
                $user->givePermissionTo(intval($permission));
            }

            $this->profile->findProfile($user->id)->update([
                'is_admin' => true,
                'admin_id' => Auth::id()
            ]);
        }
        else
        {
            $user->givePermissionTo(['view users', 'create portfolio', 'create trades']);
            $this->profile->findProfile($user->id)->update([
                'client_id' => $admin->client_id,
                'admin_id' => Auth::id()
            ]);
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

        if(!empty($request->input('isActive')))
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
