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
    place.on('change', function(e)  {
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

        }
        if (document.querySelector('#ad_lat') && document.querySelector('#ad_lng')){
            document.querySelector('#ad_lat').value = e.suggestion.latlng.lat
            document.querySelector('#ad_lng').value = e.suggestion.latlng.lng
        }
        if (document.querySelector('#lat') && document.querySelector('#lng')){
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

//supression des images
document.querySelectorAll('[data-delete]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault()
        fetch(a.getAttribute('href'), {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({'_token': a.dataset.token})

        }).then(response => response.json())
            .then(data => {
                if (data.success){
                    console.log(a.parentNode.parentNode.parentNode.parentNode)
                    a.parentNode.parentNode.parentNode.parentNode.removeChild(a.parentNode.parentNode.parentNode)
                }else{
                    alert(data.error + 'error')
                }
            })
            .catch(e => alert(e + 'bonjour'))
    })
})

//require('bootstrap.min.js');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

// Favoris
function onClickBtnLike(event){
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.js-likes')
    const icon = this.querySelector('i');

    axios.get(url).then(function (response) {
        spanCount.textContent = response.data.likes
        if (icon.classList.contains('fas')){
            icon.classList.replace('fas', 'far');
        }else{
            icon.classList.replace('far', 'fas');
        }
    }).catch(function (error) {
        if (error.response.status === 403){
            window.alert("Vous ne pouvez pas liker un article si vous n'etes pas conneté")
        }else{
            window.alert("Une erreur s'est produite, réessayez plus tard")
        }
    })
}
document.querySelectorAll('a.js-like').forEach(function (link) {
    link.addEventListener('click', onClickBtnLike);

})
