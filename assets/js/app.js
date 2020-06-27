/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import '../css/app.scss';

// Places.js
import Places from 'places.js'

let inputAdress = document.querySelector('#ad_adress')
if(inputAdress !== null){
    let place = Places({
        container: inputAdress
    })
    place.on('change', e => {
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
        document.querySelector('#ad_lat').value = e.suggestion.latlng.lat
        document.querySelector('#ad_lng').value = e.suggestion.latlng.lng
        console.log(inputAdress.value)
    })
}

// leaflet MAP
import Map from './modules/map'
Map.init()


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
var $ = require('jquery');
global.$ = global.jQuery = $;

//require('popper.js');
require('bootstrap');


//require('bootstrap.min.js');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');