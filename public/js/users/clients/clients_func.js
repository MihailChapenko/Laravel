$(document).ready(function () {

    $('#adminPermissions').selectpicker({
        liveSearch: true,
    });

    let clientsList = $('#clientsList').DataTable({
        // responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'get_clients_list',
            type: 'get'
        },
        columns: [
            {data: 'id', className: "dt-center", "targets": "_all"},
            {data: 'name', className: "dt-center", "targets": "_all"},
            {data: 'model', className: "dt-center", "targets": "_all"},
            {data: 'theme', className: "dt-center", "targets": "_all"},
            {data: 'valuetable', className: "dt-center", "targets": "_all"},
        ],
    });

    $('#addClient').on('click', function () {
        $('#addClientModal').modal('show');
    });

    $('#addClientSubmit').on('click', function () {
        clearValidation();

        let adminId = $('#adminId option:selected').attr('admin-id'),
            clientName = $('#clientName').val(),
            clientModel = $('#clientModel').val(),
            clientTheme = $('#clientTheme').val(),
            clientValueTable = $('#clientValueTable').val();


        $.ajax({
            type: 'post',
            url: 'add_client',
            data: {
                adminId: adminId,
                clientName: clientName,
                clientModel: clientModel,
                clientTheme: clientTheme,
                clientValueTable: clientValueTable
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
                    $('#addClientModal').modal('hide');
                    clearModalInput();
                    clientsList.ajax.reload();
                }
            }
        });
    });

    $('#clientsList').on('dblclick', '.client-info', function () {
        let clientId = $(this).attr('id-client');

        $.ajax({
            type: 'post',
            url: 'find_client',
            data: {
                clientId: clientId
            },
            error: function (error) {
                Swal.fire({
                    title: 'Something went wrong!',
                    text: 'Please, reload the page.',
                    type: 'error',
                })
            },
            success: function (data) {
                $('#clientId').val(data.client['id']);
                $('#editClientAdminId').val(data.client['admin_id']);
                $('#editClientName').val(data.client['name']);
                $('#editClientModel').val(data.client['model']);
                $('#editClientTheme').val(data.client['theme']);
                $('#editClientValueTable').val(data.client['valuetable']);
                $('#editClientModal').modal('show');
            }
        });
    });

    $('#editClientSubmit').on('click', function() {
        clearValidation();
        let clientId = $('#clientId').val(),
            editClientName = $('#editClientName').val(),
            editClientModel = $('#editClientModel').val(),
            editClientTheme = $('#editClientTheme').val(),
            editClientValueTable = $('#editClientValueTable').val();

        $.ajax({
            type: 'post',
            url: 'edit_client',
            data: {
                clientId: clientId,
                editClientName: editClientName,
                editClientModel: editClientModel,
                editClientTheme: editClientTheme,
                editClientValueTable: editClientValueTable
            },
            error: function(error) {
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
            success: function(data) {
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
                    $('#editClientModal').modal('hide');
                    clearModalInput();
                    clientsList.ajax.reload();
                }
            }
        });
    });

    $('#deleteClientSubmit').on('click', function() {
        let clientId = $('#clientId').val(),
            adminId = $('#editClientAdminId').val();

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
                    url: 'delete_client',
                    data: {
                        clientId: clientId,
                        adminId: adminId
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
                            clientsList.ajax.reload();
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
                            $('#editClientModal').modal('hide');
                            clearModalInput();
                        }
                    }
                });
            }
        })
    })
});