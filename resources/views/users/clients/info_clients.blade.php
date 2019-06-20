@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Clients</h2>
        @can('crud clients')
            <div class="mb-4">
                <button id="addClient" class="btn btn-primary">Add client</button>
            </div>
        @endcan
        <table id="clientsList" class="table stripe hover row-border noselect">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Model</th>
                <th>Theme</th>
                <th>Value table</th>
            </tr>
            </thead>
        </table>
    </div>

    @include('users.clients.clients_modal')
@endsection