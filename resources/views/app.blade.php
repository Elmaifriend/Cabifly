<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


</head>
<body>
    <livewire:multi-step-form />

    <script>
        document.addEventListener('livewire:load', function () {
            // Inicializar el mapa de Leaflet
            var map = L.map('map').setView([51.505, -0.09], 13); // Coordenadas por defecto
    
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
    
            // Pin fijo en el centro
            var marker = L.marker([51.505, -0.09]).addTo(map);
    
            // Mover el pin con el mapa
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                Livewire.emit('setLocation', e.latlng.lat, e.latlng.lng); // Emitir al componente Livewire
            });
        });
    </script>
</body>
</html>