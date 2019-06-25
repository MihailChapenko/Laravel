$(document).ready(function () {

    let portfilioList = $('#portfolioList').DataTable({
        // responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: 'get_portfolio_list',
            type: 'get'
        },
        columns: [
            {data: 'id', className: "dt-center", "targets": "_all"},
            {data: 'parent_name', className: "dt-center", "targets": "_all"},
            {data: 'name', className: "dt-center", "targets": "_all"},
            {data: 'description', className: "dt-center", "targets": "_all"},
            {data: 'allocation_min', className: "dt-center", "targets": "_all"},
            {data: 'allocation_max', className: "dt-center", "targets": "_all"},
            {data: 'is_active', className: "dt-center", "targets": "_all"},
        ],
    });

    $('#addPortfolio').on('click', function () {
        $('#addPortfolioModal').modal('show');
    });

    $('#addPortfolioSubmit').on('click', function () {
        $('.selectpicker').selectpicker('refresh');
        clearValidation();
        let portfolioParentId = $('#portfolioParent option:selected').attr('id-parent'),
            portfolioParentName = $('#portfolioParent option:selected').text(),
            portfolioClientId = $('#portfolioParent option:selected').attr('id-client'),
            portfolioName = $('#portfolioName').val(),
            portfolioDescription = $('#portfolioDescription').val(),
            portfolioCurrency = $('#portfolioCurrency').val(),
            portfolioAllocationMax = $('#portfolioAllocationMax').val(),
            portfolioAllocationMin = $('#portfolioAllocationMin').val(),
            portfolioSortOrder = $('#portfolioSortOrder').val();

        $.ajax({
            type: 'post',
            url: 'add_portfolio',
            data: {
                portfolioName: portfolioName,
                portfolioParentId: portfolioParentId,
                portfolioCurrency: portfolioCurrency,
                portfolioClientId: portfolioClientId,
                portfolioSortOrder: portfolioSortOrder,
                portfolioParentName: portfolioParentName,
                portfolioDescription: portfolioDescription,
                portfolioAllocationMax: portfolioAllocationMax,
                portfolioAllocationMin: portfolioAllocationMin,
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

                    $("#portfolioParent").append('<option id-parent="' + data.portfolio['id'] + '" id-client="' +
                        data.portfolio['client_id'] + '" >' + data.portfolio['name'] + '</option>').selectpicker("refresh");
                    $('#addPortfolioModal').modal('hide');
                    clearModalInput();
                    portfilioList.ajax.reload();
                }
            }
        })
    });

    $('#portfolioList').on('dblclick', '.portfolio-info', function () {
        let portfolioId = $(this).attr('id-portfolio');

        $.ajax({
            type: 'post',
            url: 'find_portfolio',
            data: {
                portfolioId: portfolioId
            },
            error: function (error) {
                Swal.fire({
                    title: 'Something went wrong!',
                    text: 'Please, reload the page.',
                    type: 'error',
                })
            },
            success: function (data) {
                if (data.error) {
                    console.log(data.error)
                } else {
                    $('#editPortfolioId').val(data.portfolioInfo['id']);
                    if (data.parentPortfolioInfo === null) {
                        $('#editPortfolioNewParent').prop('disabled', true)
                            .selectpicker('refresh')
                            .selectpicker('val', data.portfolioInfo['name']);
                    } else {
                        $('#editPortfolioNewParent').prop('disabled', false)
                            .selectpicker('refresh')
                            .selectpicker('val', data.parentPortfolioInfo['name']);
                    }
                    $('#editPortfolioName').val(data.portfolioInfo['name']);
                    $('#editPortfolioDescription').val(data.portfolioInfo['description']);
                    $('#editPortfolioCurrency').val(data.portfolioInfo['currency']);
                    $('#editPortfolioAllocationMax').val(data.portfolioInfo['allocation_max']);
                    $('#editPortfolioAllocationMin').val(data.portfolioInfo['allocation_min']);
                    $('#editPortfolioSortOrder').val(data.portfolioInfo['sort_order']);
                    (data.portfolioInfo['is_active'] === 1) ? $('#editPortfolioIsActive').prop('checked', true) : '';
                    $('#editPortfolioModal').modal('show');
                }
            }
        })
    });

    $('#editPortfolioSubmit').on('click', function () {
        clearValidation();
        let portfolioId = $('#editPortfolioId').val(),
            parentPortfolioId = $('#editPortfolioNewParent option:selected').attr('id-parent'),
            editPortfolioName = $('#editPortfolioName').val(),
            editPortfolioDescription = $('#editPortfolioDescription').val(),
            editPortfolioCurrency = $('#editPortfolioCurrency').val(),
            editPortfolioAllocationMax = $('#editPortfolioAllocationMax').val(),
            editPortfolioAllocationMin = $('#editPortfolioAllocationMin').val(),
            editPortfolioSortOrder = $('#editPortfolioSortOrder').val(),
            editPortfolioIsActive = ($('#editPortfolioIsActive').prop('checked')) ? 1 : 0;

        $.ajax({
            type: 'post',
            url: 'edit_portfolio',
            data: {
                portfolioId: portfolioId,
                parentPortfolioId: parentPortfolioId,
                editPortfolioName: editPortfolioName,
                editPortfolioDescription: editPortfolioDescription,
                editPortfolioCurrency: editPortfolioCurrency,
                editPortfolioAllocationMax: editPortfolioAllocationMax,
                editPortfolioAllocationMin: editPortfolioAllocationMin,
                editPortfolioSortOrder: editPortfolioSortOrder,
                editPortfolioIsActive: editPortfolioIsActive
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
                        title: 'Updated successfully'
                    });
                    $('#editPortfolioModal').modal('hide');
                    clearModalInput();
                    portfilioList.ajax.reload();
                }
            }
        });
    });

    $('#deletePortfolioSubmit').on('click', function () {
        let portfolioId = $('#editPortfolioId').val();

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
                    url: 'delete_portfolio',
                    data: {
                        portfolioId: portfolioId
                    },
                    error: function (error) {
                        Swal.fire({
                            title: 'Something went wrong!',
                            text: 'Please, reload the page.',
                            type: 'error',
                        })
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
                                title: 'Deleted successfully'
                            });
                            $('#editPortfolioModal').modal('hide');
                            portfilioList.ajax.reload();
                        }
                    }
                });
            }
        })
    });
});