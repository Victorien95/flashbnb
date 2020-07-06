/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import '../css/app.scss';

import 'select2'

// Places.js
import Places from 'places.js'

let inputAdress = document.querySelector('#ad_adress')
if(inputAdress !== null){
    let place = Places({
        container: inputAdress

    })
    if (document.querySelector('#ad_streetAddress') !== null){
        if (place.getVal("name")){
            document.querySelector('#ad_streetAddress').value = place.getVal("name")
        }
    }
    place.on('change', e => {
        if (document.querySelector('#ad_city')){
            if (e.suggestion.city){
                document.querySelector('#ad_city').value = e.suggestion.city
            }else{
                document.querySelector('#ad_city').value = e.suggestion.name
            }
            if (e.suggestion.postcode){
                document.querySelector('#ad_postalCode').value = e.suggestion.postcode
            }else{
                document.querySelector('#ad_postalCode').value = 'NC'
            }
            document.querySelector('#ad_streetAddress').value = e.suggestion.name

        }else{
            document.querySelector('#lat').value = e.suggestion.latlng.lat
            document.querySelector('#lng').value = e.suggestion.latlng.lng
        }
    })
}

// leaflet MAP
import Map from './modules/map'
//Map.init()


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
var $ = require('jquery');
global.$ = global.jQuery = $;

//require('popper.js');
require('bootstrap');

import stickybits from 'stickybits'
var teststicky = require('stickybits')


// Flickity

var jQueryBridget = require('jquery-bridget');
var Flickity = require('flickity');

// make Flickity a jQuery plugin
Flickity.setJQuery( $ );
jQueryBridget( 'flickity', Flickity, $ );


//require('bootstrap.min.js');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
