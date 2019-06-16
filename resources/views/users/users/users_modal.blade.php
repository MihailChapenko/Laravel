<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userNameDiv" class="form-group">
                    <label for="userName">Name</label>
                    <input type="text" class="form-control modal-input" id="userName">
                </div>
                <div id="userEmailDiv" class="form-group">
                    <label for="userEmail">Email address</label>
                    <input type="text" class="form-control modal-input" id="userEmail">
                </div>
                @role('super-admin')
                    <div id="adminPermissionsDiv" class="form-group">
                        <label for="adminPermissions">Admin Permissions</label>
                        <select id="adminPermissions" class="selectpicker form-control" multiple data-live-search="true" autocomplete="off">
                            @foreach($permissions as $permission)
                                <option permission-id="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endrole
                <div id="userPassDiv" class="form-group">
                    <label for="userPass">Password</label>
                    <input type="password" class="form-control modal-input" id="userPass">
                </div>
            </div>
            <div class="modal-footer">
                <button id="addUserSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="editUserId" type="hidden">
                <div id="editUserNameDiv" class="form-group">
                    <label for="editUserName">Name</label>
                    <input type="text" class="form-control modal-input" id="editUserName">
                </div>
                <div id="editUserEmailDiv" class="form-group">
                    <label for="editUserEmail">Email address</label>
                    <input type="text" class="form-control modal-input" id="editUserEmail">
                </div>
                @role('super-admin')
                <div id="editAdminPermissionsDiv" class="form-group">
                    <label for="editAdminPermissions">Admin Permissions</label>
                    <select id="editAdminPermissions" class="selectpicker form-control" multiple data-live-search="true" autocomplete="off">
                        @foreach($permissions as $permission)
                            <option permission-id="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endrole
                <div id="editUserPassDiv" class="form-group">
                    <label for="editUserPass">Password</label>
                    <input type="password" class="form-control modal-input" id="editUserPass">
                </div>
            </div>
            <div class="modal-footer">
                <button id="deleteUserSubmit" type="button" class="btn btn-danger">Delete user</button>
                <button id="editUserSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>