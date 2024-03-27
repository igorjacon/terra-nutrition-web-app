import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/velzon.min.css';
import $ from 'jquery';
import './bootstrap.js';
import './js/theme/layout.js';
import './js/theme/bootstrap.bundle.min.js';
import './js/theme/simplebar.min.js';
// import './js/theme/waves.min.js';
import './js/theme/feather.min.js';
import './js/theme/lord-icon-2.1.0.js';
import './js/theme/jsvectormap.min.js';
import './js/theme/world-merc.js';
import './js/theme/swiper-bundle.min.js';
import './js/theme/apexcharts.min.js';
import './js/theme/dashboard-projects.init.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

$(function () {
    var preloader = document.getElementById("preloader");
    if (preloader) {
        window.addEventListener("load", function () {
            preloader.style.opacity = "0";
            preloader.style.visibility = "hidden";
        });
    }
})
