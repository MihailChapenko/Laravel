<?php

namespace App\Http\Controllers\User\Client;

use App\User;
use App\Http\Requests\{
    AddClientRequest,
    EditClientRequest,
    DeleteClientRequest
};
use App\EloquentModel\{
    UserProfile as Profile,
    Portfolio, Client
};
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    private $user;
    private $client;
    private $profile;
    private $portfolio;

    public function __construct(User $user, Client $client, Profile $profile, Portfolio $portfolio)
    {
        $this->user = $user;
        $this->client = $client;
        $this->profile = $profile;
        $this->portfolio = $portfolio;
    }

    public function index()
    {
        $admins = Auth::user()->getAvailibleAdmins();
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
        ])->make();
    }

    public function findClient(Request $request)
    {
        $client = $this->client->find($request->input('clientId'));
        $permission = Auth::user()->can('crud clients');

        if(!$permission)
        {
            return response()->json(['error' => 'No permissions to edit clients']);
        }

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

        $this->profile->findProfile($request->input('adminId'))->update(['client_id' => $client->id]);
        $this->portfolio->find($request->input('portfolioId'))->update([
            'client_id' => $client->id,
            'parent_id' => 0
        ]);

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
