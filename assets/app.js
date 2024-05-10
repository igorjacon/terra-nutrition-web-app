import './styles/bootstrap.min.css';
import './styles/bootstrap-datetimepicker.min.css';
import './styles/choices.min.css';
import './styles/icons.min.css';
import './styles/app.min.css';
import './styles/variables.css';
import './styles/custom.css';
import './bootstrap.js';
import $ from './vendor/jquery/jquery.index';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
$(function (){
    var preloader = document.getElementById("preloader");
    if (preloader) {
        window.addEventListener("load", function () {
            preloader.style.opacity = "0";
            preloader.style.visibility = "hidden";
        });
    }
});