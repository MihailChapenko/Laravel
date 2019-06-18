<?php

namespace App\Http\Controllers\User\Client;

use App\Http\Requests\DeleteClientRequest;
use App\User;
use Illuminate\Http\Request;
use App\EloquentModel\Client;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use App\EloquentModel\UserToClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\AddClientRequest;
use App\Http\Requests\EditClientRequest;
use App\EloquentModel\{
    UserProfile as Profile,
    UserToClient as Relations,
    Portfolio
};

class ClientController extends Controller
{
    private $user;
    private $client;
    private $profile;
    private $relations;
    private $portfolio;

    public function __construct(User $user, Client $client, Relations $relations, Profile $profile, Portfolio $portfolio)
    {
        $this->user = $user;
        $this->client = $client;
        $this->profile = $profile;
        $this->portfolio = $portfolio;
        $this->relations = $relations;
    }

    public function index()
    {
        $admins = $this->user->getAvailibleAdmins();
        $portfolio = $this->portfolio->getAvailablePortfolio();

        return view('users.clients.info_clients', compact('admins', 'portfolio'));
    }

    public function getClientsList()
    {
        $clients = $this->client->all();

        return DataTables::of($clients)->setRowClass(
            'client-info'
        )->setRowAttr([
            'id-client' => '{{$id}}',
            'id-admin' => '{{$admin_id}}'
        ])->toJson();
    }

    public function findClient(Request $request)
    {
        $client = $this->client->find($request->input('clientId'));

        return response()->json(['success' => true, 'client' => $client]);
    }

    public function addClient(AddClientRequest $request)
    {

        $clientData = [
            'admin_id' => $request->input('adminId'),
            'name' => $request->input('clientName'),
            'model' => $request->input('clientModel'),
            'theme' => $request->input('clientTheme'),
            'valuetable' => $request->input('clientValueTable')
        ];

        $client = $this->client->create($clientData);
        $this->profile->findProfile($request->input('adminId'))->update(['client_id' => $request->input('adminId')]);
        $this->portfolio->find($request->input('portfolioId'))->update(['client_id' => $client->id]);

        return response()->json(['success' => true]);
    }

    public function editClient(EditClientRequest $request)
    {
        $data = [
            'name' => $request->input('editClientName'),
            'model' => $request->input('editClientModel'),
            'theme' => $request->input('editClientTheme'),
            'valuetable' => $request->input('editClientValueTable')
        ];

        $this->client->find($request->input('clientId'))->update($data);

        return response()->json(['success' => true]);
    }

    public function deleteClient(DeleteClientRequest $request)
    {
        $this->client->find($request->input('clientId'))->delete();
        $this->profile->findProfile($request->input('adminId'))->update(['client_id' => 0]);

        return response()->json(['success' => true]);
    }
}
