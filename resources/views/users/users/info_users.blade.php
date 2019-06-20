@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="mb-4">Users</h2>
        @can('crud users')
            <div class="mb-4">
                <button id="addUser" class="btn btn-primary">Add user</button>
            </div>
        @endcan
        <table id="usersList" class="table stripe hover row-border noselect">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('users.users.users_modal')
@endsection
