$(document).ready(function () {
    $('.book-now-btn').on('click', function () {
        let contact = {
            'name': $('#contact_name').val(),
            'email': $('#contact_email').val(),
            'subject': $('#contact_subject').val(),
            'message': $('#contact_message').val()
        }
        $.ajax({
            url: '/validate-contact',
            type: 'post',
            data: { contact: contact },
            success: function (errors) {
                if (errors.name_error || errors.email_error || errors.subject_error || errors.msg_error) {
                    $('#name-error').text(errors.name_error);
                    $('#email-error').text(errors.email_error);
                    $('#subject-error').text(errors.subject_error);
                    $('#msg-error').text(errors.msg_error);
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Message sent."
                    });
                }
            }
        });
    });
});