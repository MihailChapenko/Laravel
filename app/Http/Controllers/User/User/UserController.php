<?php

namespace App\Http\Controllers\User\User;

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
use App\EloquentModel\UserToClient as Relations;

class UserController extends Controller
{
    private $user;
    private $relations;

    public function __construct(User $user, Relations $relations)
    {
        $this->user = $user;
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
            ('{{$isAdmin ? "admin user-info" : "user-info"}}' )
        )->setRowAttr([
            'id-user' => '{{$id}}',
        ])->toJson();
    }

    public function findUser(Request $request)
    {
        $user = $this->user->find($request->input('userId'));

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function addUser(AddUserRequest $request)
    {
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
            $user->update(['isAdmin' => true]);
        }
        else
        {
            $relations = [
                'client_id' => Auth::user()->client_id,
                'admin_id' => Auth::id(),
                'user_id' => $user->id
            ];
            $user->update(['client_id' => Auth::user()->client_id]);
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

    public function deleteUser(Request $request)
    {
        User::find($request->input('userId'))->delete();

        return response()->json(['success' => true]);
    }
}
