import './styles/bootstrap.min.css';
import './styles/bootstrap-datetimepicker.min.css';
import './styles/choices.min.css';
import './styles/icons.min.css';
import './styles/app.min.css';
import './styles/variables.css';
import './styles/chosen.css';
import './styles/custom.css';
import './js/theme/bootstrap.bundle.min.js';
import './js/theme/simplebar.min.js';
import './js/libs/choices.min.js';
import './js/libs/flag-input.init.js';
import './js/libs/jquery.js';
import './js/libs/bootstrap.js';
import './js/libs/chosen.jquery.js';
import './js/libs/bootstrap-datetimepicker.min.js';
import swal from "sweetalert2";
// import jQuery from 'jquery';
// window.jQuery = jQuery;

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// Profile Foreground Img
if (document.querySelector(".custom-file-input")) {
    document.querySelector(".custom-file-input").addEventListener("change", function () {
        var preview = document.querySelector(".user-profile-image");
        var file = document.querySelector(".custom-file-input").files[0];
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

let simpleAjax = function simpleAjax(method, url, data,
                                     target = 'body',
                                     isReload = false,
                                     isAppend = true,
                                     callBack = null,
                                     contentType = 'application/x-www-form-urlencoded; charset=UTF-8',
                                     processData = true) {
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        contentType: contentType,
        processData: processData,
        beforeSend: function () {
            $('#preloader').css('opacity', 1).css('visibility', 'visible');
            $('#status').show();
        },
        success: function (response) {
            $('.modal-backdrop').not('.modal-stack').remove();
            $('body').removeClass("modal-open");
            let errorForm = $('.form-error-message', $('<div/>').html(response)).length > 0;

            if (isReload && !errorForm) {
                $(target).empty();
            }

            $('[data-modal-delete]').remove();

            if (isAppend || errorForm) {
                if (errorForm) {
                    // const myModal = new bootstrap.Modal(response)
                    $('body').append(response);
                } else  {
                    $(target).append(response);
                }
            }

            if (callBack && !errorForm) {
                callBack(response);
            }

            $('#preloader').css('opacity', 0).css('visibility', 'hidden');
            $('#status').hide();
            $('[data-modal-delete]').modal('toggle');
            $("select.swal2-select:hidden").remove();
        },
        error: function (jqXHR) {
            // called when user session is expired then he's redirect automaticly to /login
            if (jqXHR.status == 401) {
                location.reload();
            } else {
                $('.modal').modal('hide');
                $('body').append('<div class="alertify-logs bottom left"><div class="error show">An error occurred, please try again.</div></div>');
            }
        },
        complete: function () {
            // $('#preloader').hide();
            $('#preloader').css('opacity', 0).css('visibility', 'hidden');
            $('#status').hide();
        }
    });
}
window.simpleAjax = simpleAjax;

$(function (){
    var preloader = document.getElementById("preloader");
    if (preloader) {
        window.addEventListener("load", function () {
            preloader.style.opacity = "0";
            preloader.style.visibility = "hidden";
        });
    }
    $('.toggle-weekdays').on('click', function() {
        let value = $(this).data('day');
        let weekdays = ["1","2","3","4","5"];
        let weekends = ["6","0"];
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
    $('[data-toggle="time"]').datetimepicker({
        format: 'LT'
    });
    $("select").not('[data-disabled]').chosen({
        allow_single_deselect: true,
        inherit_select_classes: true
    });
    $('[data-toggle="datetimepicker"]').datetimepicker({
        sideBySide: true,
        format: 'L LT',
        locale: 'en',
        stepping: 15,
        useCurrent: 'day'
    });
});

let search = function(element) {
    const value = $(element).val();
    const url = $(element).data('url');
    const target = $(element).data('target');

    $.ajax({
        method: 'GET',
        url: url,
        data: {'search': value},
        success: function (response) {
            $(target).replaceWith(response);
        },
        error: function (err) {
            console.log(err);
        }
    })
};
window.search = search;

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
    $('select').chosen();
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
    $('select').chosen();
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

let editModal = function (url, target = 'body', data = null, callBack = null) {
    if (window.event) window.event.preventDefault();
    simpleAjax('POST', url, data, target);
    $(document).off('submit', '.modal form');

    $(document).on("submit", ".modal form", function (event) {
        event.stopPropagation();
        event.preventDefault();
        var form = $(this)[0];
        var formData = new FormData(form);
        simpleAjax('POST', $(this).attr('action'), formData, target, false, true, callBack, false, false);
    });
};
window.editModal = editModal;

let addDataToChosen = function (json) {
    if (json) {
        let target = json['form_field_id'].startsWith("#") ? json['form_field_id'] : '#' + json['form_field_id'];

        for (let index = 0; index < json['data'].length; index++) {
            let optionValues = {
                value: json['data'][index]['entityId'],
                text: json['data'][index]['entityName'],
            };
            for (let key in json['data'][index]['entityAttr']) {
                optionValues[key] = json['data'][index]['entityAttr'][key];
            }
            $(target).append($('<option>', optionValues));

            if ($(target).attr('multiple')) {
                let value = $(target).val();
                value.push(json['data'][index]['entityId']);
                $(target).val(value);
            } else {
                $(target).val(json['data'][index]['entityId']);
            }
        }

        $(target).trigger("chosen:updated");
    }
};
window.addDataToChosen = addDataToChosen;

let calculateFoodValue = function(element) {
    let row = $(element).closest('[data-collection-item]');
    let proteinValue = $(row).find('.food-item :selected').data('protein');
    let carbValue = $(row).find('.food-item :selected').data('carbs');
    let fatValue = $(row).find('.food-item :selected').data('fat');
    let gram_quantity = $(row).find('.measurement :selected').data('gram-quantity');
    let quantity = $(row).find('.quantity').val();

    // calculate protein
    if (quantity !== "") {
        let protein = ((quantity * parseFloat(gram_quantity) / 100) * parseFloat(proteinValue)).toFixed(2);
        $(row).find('.protein').text(protein + " g");

        // calculate carbs
        let carbs = ((quantity * parseFloat(gram_quantity) / 100) * parseFloat(carbValue)).toFixed(2);
        $(row).find('.carbs').text(carbs + " g");

        // calculate fat
        let fat = ((quantity * parseFloat(gram_quantity) / 100) * parseFloat(fatValue)).toFixed(2);
        $(row).find('.fat').text(fat + " g");

        calculateTotals($(row).parent());
    }
};
window.calculateFoodValue = calculateFoodValue;

let calculateTotals = function(foodContainer) {
    let totalQuantity = 0;
    let totalProtein = 0;
    let totalCarbs = 0;
    let totalFat = 0;

    $(foodContainer).find('.protein').each(function () {
        let amountString = $.trim($(this).text());
        let amount = amountString.substring(0, amountString.indexOf(' '));
        totalProtein = totalProtein + parseFloat(amount);
    });
    $(foodContainer).find('.carbs').each(function () {
        let amountString = $.trim($(this).text());
        let amount = amountString.substring(0, amountString.indexOf(' '));
        totalCarbs = totalCarbs + parseFloat(amount);
    });
    $(foodContainer).find('.fat').each(function () {
        let amountString = $.trim($(this).text());
        let amount = amountString.substring(0, amountString.indexOf(' '));
        totalFat = totalFat + parseFloat(amount);
    });
    $(foodContainer).find('.quantity').each(function () {
        let gQuantity = $(this).closest('[data-collection-item]').find('.measurement :selected').data('gram-quantity');
        totalQuantity = totalQuantity + (parseFloat($(this).val()) * parseFloat(gQuantity));
    });

    let totalQuantityRounded = totalQuantity.toFixed(2);
    let totalProteinRounded = totalProtein.toFixed(2);
    let totalCarbsRounded = totalCarbs.toFixed(2);
    let totalFatRounded = totalFat.toFixed(2);

    $(foodContainer).closest('.tab-pane').find('.total-quantity').text(totalQuantityRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-protein').text(totalProteinRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-carbs').text(totalCarbsRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-fat').text(totalFatRounded + " g");

    // Save totals
    $(foodContainer).closest('.tab-pane').find('.option-total-quantity').val(totalQuantityRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-protein').val(totalProteinRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-carbs').val(totalCarbsRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-fat').val(totalFatRounded);
}
window.calculateTotals = calculateTotals;