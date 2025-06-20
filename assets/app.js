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

let toggleContainer = function toggleContainer(element) {
    let container = element.dataset.container;
    if ($('#' + container).is(':visible')) {
        $(element).children('i').removeClass('las la-angle-up').addClass('las la-angle-down');
        $('#' + container).slideUp('fast');
    } else {
        $(element).children('i').removeClass('las la-angle-down').addClass('las la-angle-up');
        $('#' + container).slideDown('fast');
    }
};

window.toggleContainer = toggleContainer;

let updateBmiResults = function updateBmiResults(gender, age) {
    let collectionId = '#customer_measurement';
    let method = $(collectionId + '_method :selected').val();

    // Height and Weight
    let weight = $(collectionId + '_currWeight_measurement').val();
    let heightValue = $(collectionId + '_height_measurement').val();
    let height = parseFloat(heightValue.replace(",", "."));

    // Skinfold measurements
    let chest = parseFloat($(collectionId + '_chest').val().replace(",", "."));
    let abdomen = parseFloat($(collectionId + '_abdomen').val().replace(",", "."));
    let thigh = parseFloat($(collectionId + '_thigh').val().replace(",", "."));
    let triceps = parseFloat($(collectionId + '_triceps').val().replace(",", "."));
    let biceps = parseFloat($(collectionId + '_biceps').val().replace(",", "."));
    let suprailiac = parseFloat($(collectionId + '_suprailiac').val().replace(",", "."));
    let subscapular = parseFloat($(collectionId + '_subscapular').val().replace(",", "."));
    let midaxillary = parseFloat($(collectionId + '_midaxillary').val().replace(",", "."));

    let folds = {
        "chest": chest,
        "abdomen": abdomen,
        "thigh": thigh,
        "triceps": triceps,
        "biceps": biceps,
        "suprailiac": suprailiac,
        "subscapular": subscapular,
        "midaxillary": midaxillary
    }

    // Recommendations
    // let recommendedBFP = recommendedBFP(gender, age);

    let density = 0;
    let bodyFat = 0;
    let sum_skinfolds = 0;

    if (method === 'jackson_pollock_3') {
        ({density, sum_skinfolds } = jacksonPollockDensity(gender, folds, age));
    } else if (method === 'guedes_3') {
        ({density, sum_skinfolds } = guedesDensity(gender, folds));
    } else if (method === 'durin_womersley_4') {
        ({density, sum_skinfolds } = durinWomersleyDensity(gender, folds, age));
    } else if (method === 'faulkner_4') {
        ({bodyFat, sum_skinfolds } = faulknerBodyFat(folds));
    } else if (method === 'jackson_pollock_7') {
        ({density, sum_skinfolds } = jacksonPollock7Density(gender, folds, age));
    }

    if ((density !== 0 || bodyFat !== 0) && sum_skinfolds > 0) {
        if (bodyFat === 0) {
            bodyFat = ((495 / density) - 450).toFixed(2); // Body fat percentage
        }
        let lfp = (100-bodyFat).toFixed(2); // Lean fat percentage
        let bf = ((bodyFat/100)*weight).toFixed(2); // Body fat
        let lm = (weight-bf).toFixed(2); // Lean mass

        $('#bfp').text(bodyFat + "%");
        $('#lfp').text(lfp + "%");
        $('#bf').text(bf + "kg");
        $('#lm').text(lm + "kg");
        $('#bd').text(density);
        $('#sum_sf').text(sum_skinfolds);

        $(collectionId + '_bfp').val(bodyFat);
        $(collectionId + '_lfp').val(lfp);
        $(collectionId + '_bf').val(bf);
        $(collectionId + '_lm').val(lm);
        $(collectionId + '_bodyDensity').val(density);
        $(collectionId + '_sumSkinfolds').val(sum_skinfolds);
    }

    if (weight !== "" && heightValue !== "") {
        let bmi = (weight/(height**2)).toFixed(2) // BMI
        $('#bmi').text(bmi);
        $(collectionId + '_bmi').val(bmi);
    }
}
window.updateBmiResults = updateBmiResults;

function jacksonPollockDensity(gender, folds, age) {
    let density = 0;
    let sum_skinfolds = 0;

    if (gender === "male") {
        if (!isNaN(folds['chest']) && !isNaN(folds['abdomen']) && !isNaN(folds['thigh'])) {
            sum_skinfolds = folds['chest']+folds['abdomen']+folds['thigh'];
            density = (1.10938 - (0.0008267 * sum_skinfolds) + (0.0000016 * (sum_skinfolds**2)) - (0.0002574 * parseInt(age))).toFixed(6); // Body Density
        }
    } else if (gender === "female") {
        if (!isNaN(folds['triceps']) && !isNaN(folds['suprailiac']) && !isNaN(folds['thigh'])) {
            sum_skinfolds = folds['triceps']+folds['suprailiac']+folds['thigh'];
            density = (1.0994921 - (0.0009929 * sum_skinfolds) + (0.0000023 * (sum_skinfolds**2)) - (0.0001392 * parseInt(age))).toFixed(6); // Body Density
        }
    }
    return { density, sum_skinfolds };
}

function guedesDensity(gender, folds) {
    let density = 0;
    let sum_skinfolds = 0;

    if (gender === "male") {
        if (!isNaN(folds['triceps']) && !isNaN(folds['suprailiac']) && !isNaN(folds['abdomen'])) {
            sum_skinfolds = folds['triceps']+folds['suprailiac']+folds['abdomen'];
            let logSum = Math.log10(sum_skinfolds);
            density = (1.17136 - (0.06706 * logSum)).toFixed(6);
        }
    } else if (gender === "female") {
        if (!isNaN(folds['subscapular']) && !isNaN(folds['suprailiac']) && !isNaN(folds['thigh'])) {
            sum_skinfolds = folds['subscapular']+folds['suprailiac']+folds['thigh'];
            let logSum = Math.log10(sum_skinfolds);
            density = (1.16650 - (0.07063 * logSum)).toFixed(6);
        }
    }
    return { density, sum_skinfolds };
}

function durinWomersleyDensity(gender, folds, age) {
    let density = 0;
    let sum_skinfolds = 0;

    if (!isNaN(folds['biceps']) && !isNaN(folds['triceps']) && !isNaN(folds['suprailiac']) && !isNaN(folds['subscapular'])) {
        sum_skinfolds = folds['biceps']+folds['triceps']+folds['suprailiac']+folds['subscapular'];
        let logSum = Math.log10(sum_skinfolds);

        if (gender === "male") {
            if (age < 17) density = (1.1533 - 0.0643 * logSum).toFixed(6);
            else if (age < 20) density = (1.1620 - 0.0630 * logSum).toFixed(6);
            else if (age < 30) density = (1.1631 - 0.0632 * logSum).toFixed(6);
            else if (age < 40) density = (1.1422 - 0.0544 * logSum).toFixed(6);
            else if (age < 50) density = (1.1620 - 0.0700 * logSum).toFixed(6);
            else density = (1.1715 - 0.0779 * logSum).toFixed(6);
        } else if (gender === "female") {
            if (age < 17) density = (1.1369 - 0.0598 * logSum).toFixed(6);
            else if (age < 20) density = (1.1549 - 0.0678 * logSum).toFixed(6);
            else if (age < 30) density = (1.1599 - 0.0717 * logSum).toFixed(6);
            else if (age < 40) density = (1.1423 - 0.0632 * logSum).toFixed(6);
            else if (age < 50) density = (1.1333 - 0.0612 * logSum).toFixed(6);
            else density = (1.1339 - 0.0645 * logSum).toFixed(6);
        }
    }

    return { density, sum_skinfolds };
}

function faulknerBodyFat(folds) {
    let bodyFat = 0;
    let sum_skinfolds = 0;

    if (!isNaN(folds['abdomen']) && !isNaN(folds['triceps']) && !isNaN(folds['suprailiac']) && !isNaN(folds['subscapular'])) {
        sum_skinfolds = folds['abdomen']+folds['triceps']+folds['suprailiac']+folds['subscapular'];
        bodyFat = ((sum_skinfolds * 0.153) + 5.783).toFixed(2);
    }

    return { bodyFat, sum_skinfolds };
}

function jacksonPollock7Density(gender, folds, age) {
    let density = 0;
    let sum_skinfolds = 0;

    if (!isNaN(folds['chest']) && !isNaN(folds['abdomen']) && !isNaN(folds['thigh']) && !isNaN(folds['triceps'])
        && !isNaN(folds['subscapular']) && !isNaN(folds['suprailiac']) && !isNaN(folds['midaxillary'])) {
        sum_skinfolds = folds['chest']+folds['abdomen']+folds['thigh']+folds['triceps']+folds['subscapular']+folds['suprailiac']+folds['midaxillary'];
        if (gender === "male") {
            density = (1.112 - (0.00043499 * sum_skinfolds) + (0.00000055 * (sum_skinfolds**2)) - (0.00028826 * parseInt(age))).toFixed(6); // Body Density
        } else if (gender === "female") {
            density = (1.097 - (0.00046971 * sum_skinfolds) + (0.00000056 * (sum_skinfolds**2)) - (0.00012828 * parseInt(age))).toFixed(6); // Body Density
        }
    }

    return { density, sum_skinfolds };
}

function recommendedBFP(gender, age) {
    let bodyFatRanges = {
        "male": [
            { min: 18, max: 24, low: 10, high: 15 },
            { min: 25, max: 34, low: 12, high: 18 },
            { min: 35, max: 44, low: 14, high: 20 },
            { min: 45, max: 54, low: 16, high: 22 },
            { min: 55, max: 64, low: 18, high: 24 },
            { min: 65, max: 100, low: 20, high: 26 }
        ],
        "female": [
            { min: 18, max: 24, low: 18, high: 22 },
            { min: 25, max: 34, low: 20, high: 24 },
            { min: 35, max: 44, low: 22, high: 26 },
            { min: 45, max: 54, low: 24, high: 28 },
            { min: 55, max: 64, low: 26, high: 30 },
            { min: 65, max: 100, low: 28, high: 32 }
        ]
    };

    if (!bodyFatRanges[gender]) {
        return null;
    }

    for (let range of bodyFatRanges[gender]) {
        if (age >= range.min && age <= range.max) {
            return [range.low, range.high];
        }
    }

    return null;
}

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
    let kjValue = $(row).find('.food-item :selected').data('energy-kj');
    let gram_quantity = $(row).find('.measurement :selected').data('gram-quantity');
    let quantity = $(row).find('.quantity').val();

    // calculate protein
    if (quantity !== "") {
        let protein = (((quantity * parseFloat(gram_quantity)) / 100) * parseFloat(proteinValue)).toFixed(2);
        $(row).find('.protein').text(protein + " g");

        // calculate carbs
        let carbs = (((quantity * parseFloat(gram_quantity)) / 100) * parseFloat(carbValue)).toFixed(2);
        $(row).find('.carbs').text(carbs + " g");

        // calculate fat
        let fat = (((quantity * parseFloat(gram_quantity)) / 100) * parseFloat(fatValue)).toFixed(2);
        $(row).find('.fat').text(fat + " g");

        // calculate calories
        let kj = (((quantity * parseFloat(gram_quantity)) / 100) * parseFloat(kjValue)).toFixed(2);
        let calories = (parseFloat(kj) * 0.239).toFixed(2);
        $(row).find('.calories').text(calories + " kcal");

        calculateTotals($(row).parent());
    }
};
window.calculateFoodValue = calculateFoodValue;

let calculateTotals = function(foodContainer) {
    let totalQuantity = 0;
    let totalProtein = 0;
    let totalCarbs = 0;
    let totalFat = 0;
    let totalCalories = 0;

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
    $(foodContainer).find('.calories').each(function () {
        let amountString = $.trim($(this).text());
        let amount = amountString.substring(0, amountString.indexOf(' '));
        totalCalories = totalCalories + parseFloat(amount);
    });
    $(foodContainer).find('.quantity').each(function () {
        let gQuantity = $(this).closest('[data-collection-item]').find('.measurement :selected').data('gram-quantity');
        totalQuantity = totalQuantity + (parseFloat($(this).val()) * parseFloat(gQuantity));
    });

    let totalQuantityRounded = totalQuantity.toFixed(2);
    let totalProteinRounded = totalProtein.toFixed(2);
    let totalCarbsRounded = totalCarbs.toFixed(2);
    let totalFatRounded = totalFat.toFixed(2);
    let totalCaloriesRounded = totalCalories.toFixed(2);

    $(foodContainer).closest('.tab-pane').find('.total-quantity').text(totalQuantityRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-protein').text(totalProteinRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-carbs').text(totalCarbsRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-fat').text(totalFatRounded + " g");
    $(foodContainer).closest('.tab-pane').find('.total-calories').text(totalCaloriesRounded + " kcal");

    // Save totals
    $(foodContainer).closest('.tab-pane').find('.option-total-quantity').val(totalQuantityRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-protein').val(totalProteinRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-carbs').val(totalCarbsRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-fat').val(totalFatRounded);
    $(foodContainer).closest('.tab-pane').find('.option-total-calories').val(totalCaloriesRounded);
}
window.calculateTotals = calculateTotals;