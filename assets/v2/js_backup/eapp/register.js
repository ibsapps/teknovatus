function validate_emp_id() {
    var emp_nik = $('#emp_nik').val();
    $.ajax({
        url: "register/validate/emp_nik",
        type: 'post',
        data: 'emp_nik=' + emp_nik,
        dataType: 'json',
        beforeSend: function () {
            $('#div_ndah').hide();
            $('#div_ndah_yaok').hide();
            $('#div_ndah_sure').hide();
        },
        success: function (response) {

            if (response.status == 1) {

                $('#emp_name').val(response.employee.employee_name);
                $('#emp_id').val(response.employee.id);
                $('#emp_division').val(response.employee.division);

                $('#reg_phone').show();
                $('#div_ndah').hide();

            } else {
            	
            	$('#reg_phone').hide();
            	$('#div_ndah').hide();
            	$('#div_ndah_yaok').hide();
            	$('#div_ndah_sure').hide();

                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ID not found!.',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        },
        error: function (response) {
        	$('#reg_phone').hide();
        	$('#div_ndah').hide();
        	$('#div_ndah_yaok').hide();
        	$('#div_ndah_sure').hide();
            alert('Something went wrong. Please try again.');
        }
    });
}

$("#btnRegister").click(function (e) {
    e.preventDefault();
    var emp_id = $('#emp_id').val();
    var phoneNumber = $('#phoneNumber').val();

    if ((emp_id == '')) {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Oops. Please refresh this page & try again.',
            showConfirmButton: false,
            timer: 1000
        });
        return false;
    }

    $.ajax({
        url: "register/sendCode",
        type: 'post',
        data: 'phoneNumber=' + phoneNumber + '&emp_id=' + emp_id,
        success: function (response) {
            if (response == 1) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '<strong>Confirm your phone number.</strong>',
                    icon: 'question',
                    html: '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<center><b>6 digit code has been sent to your phone number.</b></center><br>' +
                        '</div>' +
                        '</div><br>' +
                        '<div class="row">' +
                        '<div class="d-flex justify-content-center">' +
                        '<div class="col-md-8"></div>' +
                        '<div class="col-md-6">' +
                        '<input class="form-control form-control-lg" onkeypress="return isNumericID(event)" oninput="maxLengthCheck(this)" onKeyUp="if(this.value.length==6) return verification();" maxlength="6" min="1" max="999" id="otp_code" />' +
                        '</div>' +
                        '</div>' +
                        '</div><br>' +
                        '<div class="row">' +
                        '</div><br>' +
                        '<hr>',
                    showCloseButton: false,
                    showCancelButton: true,
                    showConfirmButton: false,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    confirmButtonText: '<i class="fa fa-check-square-o"> Confirm</i>',
                    confirmButtonAriaLabel: 'Confirm',
                    cancelButtonText: '<i class="fa fa-remove"> Cancel</i>',
                    cancelButtonAriaLabel: 'Cancel',
                })

            } else {
                alert('Please enter your phone number!');
            }
        }
    });
});

function verification() {
    var otp = $('#otp_code').val();
    var phoneNumber = $('#phoneNumber').val();
    var emp_id = $('#emp_id').val();
    var app = $('#app').val();

    $.ajax({
        url: "register/verify",
        type: 'post',
        data: 'otp=' + otp + '&phoneNumber=' + phoneNumber + '&emp_id=' + emp_id + '&app=' + app,
        dataType: 'json',
        success: function (response) {

            $('#otp_code').val();

            if (response.status == 1) {

			    Swal.fire({
				    title: "Verified!",
		            text: "Please re-login to ibsapps.",
		            icon: "success"
				}).then(function() {
				    window.location.href = response.url;
				});

            } else {

                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Oops! Authentication failed. Please try again.',
                    showConfirmButton: false,
                    timer: 3500
                });

            }
            
        },
        error: function (response) {
            alert('Something went wrong. Please refresh this page and try again.');
        }
    });
}

// Update Phone Number
$("#btnUpdatePhoneNumber").click(function (e) {
    e.preventDefault();
    var phoneNumber = $('#phoneNumber').val();
    $.ajax({
        url: "register/sendCodeChangeNumber",
        type: 'post',
        data: 'phoneNumber=' + phoneNumber + '&emp_id=' + emp_id,
        success: function (response) {
            if (response == 1) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: '<strong>Confirm your phone number.</strong>',
                    icon: 'question',
                    html: '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<center><b>6 digit code has been sent to your phone number.</b></center><br>' +
                        '</div>' +
                        '</div><br>' +
                        '<div class="row">' +
                        '<div class="d-flex justify-content-center">' +
                        '<div class="col-md-8"></div>' +
                        '<div class="col-md-6">' +
                        '<input class="form-control form-control-lg" onkeypress="return isNumericID(event)" oninput="maxLengthCheck(this)" onKeyUp="if(this.value.length==6) return verifyUpdatePhone();" maxlength="6" min="1" max="999" id="otp_code" />' +
                        '</div>' +
                        '</div>' +
                        '</div><br>' +
                        '<div class="row">' +
                        '</div><br>' +
                        '<hr>',
                    showCloseButton: false,
                    showCancelButton: true,
                    showConfirmButton: false,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    confirmButtonText: '<i class="fa fa-check-square-o"> Confirm</i>',
                    confirmButtonAriaLabel: 'Confirm',
                    cancelButtonText: '<i class="fa fa-remove"> Cancel</i>',
                    cancelButtonAriaLabel: 'Cancel',
                })

            } else {
                alert('Please enter your phone number!');
            }
        }
    });
});

function verifyUpdatePhone() {
    var otp = $('#otp_code').val();
    var phoneNumber = $('#phoneNumber').val();

    $.ajax({
        url: "register/verifyChangeNumber",
        type: 'post',
        data: 'otp=' + otp + '&phoneNumber=' + phoneNumber,
        dataType: 'json',
        success: function (response) {

            if (response.status == 1) {

                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Thank you! You new phone are verified.',
                    showConfirmButton: false,
                    timer: 3000
                });
                window.location.href = '/form';

            } else {

                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Oops! Authentication failed. Please try again.',
                    showConfirmButton: false,
                    timer: 3500
                });

            }
            
        },
        error: function (response) {
            alert('Please enter your phone number!');
        }
    });
}

function isNumericID(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
    }
}

function maxLengthCheck(object) {
    if (object.value.length > object.maxLength)
        object.value = object.value.slice(0, object.maxLength)
}

