@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="mb-4">Users</h2>
        @role('super-admin|admin')
            <div class="mb-4">
                <button id="addUser" class="btn btn-primary">Add user</button>
            </div>
        @endrole
        <table id="usersList" class="table stripe hover row-border noselect">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('users.users.users_modal')
@endsection
