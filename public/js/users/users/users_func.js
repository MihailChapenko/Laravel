$(document).ready(function () {

    let usersList = $('#usersList').DataTable({
        // responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'get_users_list',
            type: 'get'
        },
        columns: [
            {data: 'id', className: "dt-center", "targets": "_all"},
            {data: 'name', className: "dt-center", "targets": "_all"},
            {data: 'email', className: "dt-center", "targets": "_all"},
        ],
    });

    $('#addUser').on('click', function () {
        $('#addUserModal').modal('show');
    });

    $('#addUserSubmit').on('click', function () {
        clearValidation();
        let adminPermissions = [];
        let userName = $('#userName').val(),
            userEmail = $('#userEmail').val(),
            userPass = $('#userPass').val();

        $.each($('#adminPermissions option:selected'), function(){
            adminPermissions.push($(this).attr('permission-id'));
        });

        $.ajax({
            type: 'post',
            url: 'add_user',
            data: {
                userName: userName,
                userEmail: userEmail,
                adminPermissions: adminPermissions,
                userPass: userPass
            },
            error: function (error) {
                if (error.responseJSON.errors) {
                    let errors = error.responseJSON.errors;
                    $.each(errors, function (index, value) {
                        $('#' + index).addClass('is-invalid');
                        $('#' + index + 'Div').append('<span class="invalid-feedback d-block"><strong>' + value + '</strong></span>');
                    });
                } else {
                    Swal.fire({
                        title: 'Something went wrong!',
                        text: 'Please, reload the page.',
                        type: 'error',
                    })
                }
            },
            success: function (data) {
                if (data.success === true) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Toast.fire({
                        type: 'success',
                        title: 'Created successfully'
                    });
                    $('#addUserModal').modal('hide');
                    clearModalInput();
                    usersList.ajax.reload();
                }
            }
        });
    });

    $('#usersList').on('dblclick', '.user-info', function () {
        let userId = $(this).attr('id-user');

        $.ajax({
            type: 'post',
            url: 'find_user',
            data: {
                userId: userId
            },
            error: function (error) {
                Swal.fire({
                    title: 'Something went wrong!',
                    text: 'Please, reload the page.',
                    type: 'error',
                });
            },
            success: function (data) {
                if(data.error) {
                    console.log(data.error)
                } else {
                    $('#editUserId').val(data.user['id']);
                    $('#editUserName').val(data.user['name']);
                    $('#editUserEmail').val(data.user['email']);
                    (data.user['is_active'] === 1) ? $('#isActive').prop('checked', true) : '';
                    $('#editUserModal').modal('show');
                }
            }
        });
    });

    $('#editUserSubmit').on('click', function () {
        clearValidation();
        let adminNewPermissions = [];
        let userId = $('#editUserId').val(),
            editUserName = $('#editUserName').val(),
            editUserEmail = $('#editUserEmail').val(),
            editUserPass = $('#editUserPass').val(),
            isActive = ($('#isActive').prop('checked')) ? 1 : 0;

        $.each($('#editAdminPermissions option:selected'), function(){
            adminNewPermissions.push($(this).attr('permission-id'));
        });

        $.ajax({
            type: 'post',
            url: 'edit_user',
            data: {
                userId: userId,
                adminNewPermissions: adminNewPermissions,
                editUserName: editUserName,
                editUserEmail: editUserEmail,
                editUserPass: editUserPass,
                isActive: isActive
            },
            error: function (error) {
                if (error.responseJSON.errors) {
                    let errors = error.responseJSON.errors;
                    $.each(errors, function (index, value) {
                        console.log(errors);
                        $('#' + index).addClass('is-invalid');
                        $('#' + index + 'Div').append('<span class="invalid-feedback d-block"><strong>' + value + '</strong></span>');
                    });
                } else {
                    Swal.fire({
                        title: 'Something went wrong!',
                        text: 'Please, reload the page.',
                        type: 'error',
                    })
                }
            },
            success: function (data) {
                if (data.success === true) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Toast.fire({
                        type: 'success',
                        title: 'Updated successfully'
                    });
                    $('#editUserModal').modal('hide');
                    clearModalInput();
                    usersList.ajax.reload();
                }
            }
        });
    });

    $('#deleteUserSubmit').on('click', function () {
        let userId = $('#editUserId').val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'delete',
                    url: 'delete_user',
                    data: {
                        userId: userId
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Something went wrong!',
                            text: 'Please, reload the page.',
                            type: 'error',
                        })
                    },
                    success: function(data) {
                        if (data.success === true) {
                            usersList.ajax.reload();
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'center',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Toast.fire({
                                type: 'success',
                                title: 'Deleted successfully'
                            });
                            $('#editUserModal').modal('hide');
                            clearModalInput();
                        }
                    }
                });
            }
        })
    });
});