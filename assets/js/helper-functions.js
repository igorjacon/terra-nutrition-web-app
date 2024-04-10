import $ from 'jquery';
import swal from 'sweetalert2';

let removeUser = function (url) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            $('body').append(response);
            swal.fire({
                title: "Are you sure?",
                text: "You will not be able to revert this action!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes!",
                cancelButtonText: "Cancel",
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545'
            }).then(function (result) {
                if (result.value == true) {
                    let id = $(response)[0].id;
                    if (id) {
                        document.querySelector('#' + id).submit();
                    }
                } else {
                    return false;
                }
            });
        },
        error: function (jqXHR) {
            if (jqXHR.status == 401) {
                location.reload();
            } else {
                swal.fire("Error", "An error occurred, please try again.", "error")
            }
        }
    });
}

window.removeUser = removeUser;