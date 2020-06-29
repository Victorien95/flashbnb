import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import 'leaflet/dist/leaflet'

export default class Map {
    static init(){
        let map = document.querySelector('#map')
        if (map !== null){
            let center = [map.dataset.lat, map.dataset.lng]
            let icon = L.icon({
                iconUrl: '/images/marker-icon.png',
            });
            map = L.map('map').setView(center, 13)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker(center, {icon: icon}).addTo(map);
        }
    }
}

