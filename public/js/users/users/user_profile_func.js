$(document).ready(function () {

    $('#saveProfile').on('click', function () {
        let userId = $('#userId span').text(),
            firstName = $('#firstName').val(),
            lastName = $('#lastName').val(),
            address = $('#address').val(),
            phone = $('#phone').val(),
            email = $('#email').val(),
            isActive = ($('#isActive').prop('checked')) ? 1 : 0;

        $.ajax({
            type: 'post',
            url: 'update_profile',
            data: {
                userId: userId,
                firstName: firstName,
                lastName: lastName,
                address: address,
                phone: phone,
                email: email,
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
                }
            }
        })
    });

    $('#saveNewPass').on('click', function () {
        clearValidation();
        let userId = $('#userId span').text(),
            password = $('#password').val(),
            password_confirmation = $('#password_confirmation').val();

        $.ajax({
            type: 'post',
            url: 'change_password',
            data: {
                userId: userId,
                password: password,
                password_confirmation: password_confirmation
            },
            error: function(error) {
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
                }
            }
        });
    });

});