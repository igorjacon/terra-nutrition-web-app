import $ from "jquery";
import swal from 'sweetalert2';

// Profile Foreground Img
if (document.querySelector("#customer_user_profileFile_file")) {
    document.querySelector("#customer_user_profileFile_file").addEventListener("change", function () {
        var preview = document.querySelector(".user-profile-image");
        var file = document.querySelector(".profile-img-file-input").files[0];
        var reader = new FileReader();
        reader.addEventListener(
            "load",
            function () {
                preview.src = reader.result;
            },
            false
        );
        if (file) {
            reader.readAsDataURL(file);
        }
    });
}

$(function (){
    $('.toggle-weekdays').on('click', function() {
        let value = $(this).data('day');
        let weekdays = ["1","2","3","4","5"];
        let weekends = ["6","7"];
        switch (value) {
            case 'all':
                $('#days input[type="checkbox"]').each(function() {
                    $(this).attr('checked', true);
                });
                break;
            case 'week':
                $('#days input[type="checkbox"]').each(function() {
                    if ($.inArray($(this).val(), weekdays) !== -1) {
                        $(this).attr('checked', true);
                    } else {
                        $(this).attr('checked', false);
                    }
                });
                break;
            case 'wends':
                $('#days input[type="checkbox"]').each(function() {
                    if ($.inArray($(this).val(), weekends) !== -1) {
                        $(this).attr('checked', true);
                    } else {
                        $(this).attr('checked', false);
                    }
                });
                break;
            case 'none':
                $('#days input[type="checkbox"]').each(function() {
                    $(this).attr('checked', false);
                });
                break
        }
    });
    // $('[data-toggle="time"]').datetimepicker({
    //     format: 'LT'
    // });
});

var datepicker = function (target) {
    let datetimepicker = $(target).datetimepicker({
        format: 'L',
        locale: window.locale,
    });
    $(target).on('dp.change', function (e) {
        datetimepicker.datetimepicker('destroy');
        $(target).unbind('dp.change');
    });
    $(target).datetimepicker('show');
}
window.datepicker = datepicker;

let counter = 0;
let addCollection = function (element, prototypeName = '__name__') {
    let collectionId = $(element).attr('data-collection-id');
    let prototype = $('#' + collectionId).attr('data-prototype');
    let collectionItems = $('#' + collectionId).find('[data-collection-item]');
    if (counter === 0) {
        counter = collectionItems.length+1;
    } else {
        counter++;
    }

    let regex = new RegExp(prototypeName, 'g');
    let newWidget = prototype.replace(regex, counter);

    //append widget
    $(newWidget).appendTo('#' + collectionId);
    $(newWidget).find('select').each(function() {
        let id = $(this).attr('id');
        new Choices('#'+id);
    });
    $('[data-toggle="time"]').datetimepicker({
        format: 'LT'
    });
}
window.addCollection = addCollection;

let addOptionCollection = function (element, prototypeName = '__name__') {
    let collectionId = $(element).attr('data-collection-id');
    let prototype = $('#' + collectionId).attr('data-prototype');
    let collectionItems = $('#' + collectionId).find('.accordion-item');
    if (counter === 0) {
        counter = collectionItems.length+1;
    } else {
        counter++;
    }
    let optionCounter = collectionItems.length+1;

    let regex = new RegExp(prototypeName, 'g');
    let newWidget = prototype.replace(regex, counter);
    newWidget = newWidget.replace('__key__', optionCounter);

    //append widget
    $(newWidget).appendTo('#' + collectionId);
    $(newWidget).find('select').each(function() {
        let id = $(this).attr('id');
        new Choices('#'+id);
    });
    $('[data-toggle="time"]').datetimepicker({
        format: 'LT'
    });
}
window.addOptionCollection = addOptionCollection;

let removeCollection = function(element) {
    let collectionItem = $(element).closest('[data-collection-item]');
    $(collectionItem).remove();
}
window.removeCollection = removeCollection;

let removeEntity = function (url) {
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
window.removeEntity = removeEntity;