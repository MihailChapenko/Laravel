$(document).ready(function() {

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
            {data: 'name', className: "dt-center", "targets": "_all"},
            {data: 'description', className: "dt-center", "targets": "_all"},
            {data: 'allocation_min', className: "dt-center", "targets": "_all"},
            {data: 'allocation_max', className: "dt-center", "targets": "_all"},
            {data: 'is_active', className: "dt-center", "targets": "_all"},
        ],
    });

    $('#addPortfolio').on('click', function() {
        $('#addPortfolioModal').modal('show');
    });

    $('#addPortfolioSubmit').on('click', function() {
        clearValidation();
        let portfolioName = $('#portfolioName').val(),
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
                portfolioDescription: portfolioDescription,
                portfolioCurrency: portfolioCurrency,
                portfolioAllocationMax: portfolioAllocationMax,
                portfolioAllocationMin: portfolioAllocationMin,
                portfolioSortOrder: portfolioSortOrder
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
                        title: 'Created successfully'
                    });
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
            error: function(error) {
                Swal.fire({
                    title: 'Something went wrong!',
                    text: 'Please, reload the page.',
                    type: 'error',
                })
            },
            success: function(data) {
                if(data.error) {
                    console.log(data.error)
                } else {
                    $('#editPortfolioId').val(data.portfolio['id']);
                    $('#editPortfolioName').val(data.portfolio['name']);
                    $('#editPortfolioDescription').val(data.portfolio['description']);
                    $('#editPortfolioCurrency').val(data.portfolio['currency']);
                    $('#editPortfolioAllocationMax').val(data.portfolio['allocation_max']);
                    $('#editPortfolioAllocationMin').val(data.portfolio['allocation_min']);
                    $('#editPortfolioSortOrder').val(data.portfolio['sort_order']);
                    (data.portfolio['is_active'] === 1) ? $('#editPortfolioIsActive').prop('checked', true) : '';
                    $('#editPortfolioModal').modal('show');
                }
            }
        })
    });

    $('#editPortfolioSubmit').on('click', function() {
        clearValidation();
        let portfolioId = $('#editPortfolioId').val(),
            editPortfolioName = $('#editPortfolioName').val(),
            editPortfolioDescription = $('#editPortfolioDescription').val(),
            editPortfolioCurrency = $('#editPortfolioCurrency').val(),
            editPortfolioAllocationMax = $('#editPortfolioAllocationMax').val(),
            editPortfolioAllocationMin = $('#editPortfolioAllocationMin').val(),
            editPortfolioSortOrder = $('#editPortfolioSortOrder').val(),
            editPortfolioIsActive = $('#editPortfolioIsActive').prop('checked');

        $.ajax({
            type: 'post',
            url: 'edit_portfolio',
            data: {
                portfolioId: portfolioId,
                editPortfolioName: editPortfolioName,
                editPortfolioDescription: editPortfolioDescription,
                editPortfolioCurrency: editPortfolioCurrency,
                editPortfolioAllocationMax: editPortfolioAllocationMax,
                editPortfolioAllocationMin: editPortfolioAllocationMin,
                editPortfolioSortOrder: editPortfolioSortOrder,
                editPortfolioIsActive: editPortfolioIsActive
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
                    $('#editPortfolioModal').modal('hide');
                    clearModalInput();
                    portfilioList.ajax.reload();
                }
            }
        });
    });

    $('#deletePortfolioSubmit').on('click', function() {
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
                    error: function(error) {
                        Swal.fire({
                            title: 'Something went wrong!',
                            text: 'Please, reload the page.',
                            type: 'error',
                        })
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