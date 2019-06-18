@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Profile info</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-4"><h5 id="userId">User Id: <span>{{ $userInfo->user_id }}</span></h5></div>
                    <div class="col-6 mb-4">
                        <h5>Status:
                            @if($userInfo->user_id === 1)
                                Owner
                            @elseif($userInfo->isAdmin)
                                Admin
                            @else
                                User
                            @endif
                        </h5>
                    </div>
                    <div class="col-6">
                        <div id="firstNameDiv" class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" value="{{ $userInfo->first_name }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="lastNameDiv" class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" value="{{ $userInfo->last_name }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="addressDiv" class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" value="{{ $userInfo->address }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="phoneDiv" class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" maxlength="10" value="{{ $userInfo->phone }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="emailDiv" class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" value="{{ $userInfo->email }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <button id="saveProfile" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Change password</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div id="passwordDiv" class="form-group">
                            <label for="password">New password</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="password_confirmationDiv" class="form-group">
                            <label for="password_confirmation">New password confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation">
                        </div>
                    </div>
                    <div class="col-12">
                        <button id="saveNewPass" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection