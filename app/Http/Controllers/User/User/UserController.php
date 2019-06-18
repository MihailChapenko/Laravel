<?php

namespace App\Http\Controllers\User\User;

use App\Http\Requests\ChangeUserPassRequest;
use App\Http\Requests\DeleteUserRequest;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;
use Illuminate\Support\Facades\Validator;
use App\EloquentModel\{
    UserProfile as Profile,
    UserToClient as Relations
};

class UserController extends Controller
{
    private $user;
    private $profile;
    private $relations;

    public function __construct(User $user, Relations $relations, Profile $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->relations = $relations;
    }

    public function index()
    {
        $permissions = Permission::all();

        return view('users.users.info_users', compact('permissions'));
    }

    public function getUsersList()
    {
        $users = $this->user->getUsersList();

        return DataTables::of($users)->setRowClass(
            ( '{{$isAdmin ? "admin user-info" : "user-info"}}' )
        )->setRowAttr([
            'id-user' => '{{$id}}',
        ])->toJson();
    }

    public function findUser(Request $request)
    {
        $user = $this->user->getUserInfo($request->input('userId'));

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function addUser(AddUserRequest $request)
    {
        $admin = $this->user->findAdmin(Auth::id());

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
        }
        else
        {
            $relations = [
                'client_id' => $admin->client_id,
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
            ];

            $user->givePermissionTo(['view users', 'create portfolio', 'create trades']);
            $this->profile->findProfile($user->id)->update(['client_id' => $admin->client_id]);
            $this->relations->create($relations);
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
            $this->profile->findProfile($request->input('userId'))->update(['isActive' => $request->input('isActive')]);
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
