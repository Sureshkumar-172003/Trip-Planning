<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Weather Map</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

<h3>Real-Time Weather Map</h3>
<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    // Initialize the map and set its view
   var defaultLat = <?php echo isset($place['lat']) ? htmlspecialchars($place['lat']) : '20'; ?>;
    var defaultLng = <?php echo isset($place['lng']) ? htmlspecialchars($place['lng']) : '80'; ?>;

    var map = L.map('map').setView([defaultLat, defaultLng], 5);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Function to fetch weather data using Visual Crossing API
    async function getWeatherData(lat, lng) {
        const api_key = '7JFTHRXBCNEZJDP6H9NYY2AE2'; // Replace with your Visual Crossing API key
        const weather_url = `https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/${lat},${lng}?unitGroup=metric&key=${api_key}&contentType=json`;
        
        const response = await fetch(weather_url);
        const data = await response.json();

        return data;
    }

    // Fetch the places and display weather on the map
    var places = <?php echo json_encode($places); ?>;

    places.forEach(function(place) {
        const lat = place.lat;
        const lng = place.lng;
        const place_name = place.place_name;

        // Fetch weather for each place
        getWeatherData(lat, lng).then(data => {
            if (data && data.currentConditions) {
                var temperature = data.currentConditions.temp;
                var condition = data.currentConditions.conditions;

                // Add a marker to the map
                var marker = L.marker([lat, lng]).addTo(map);

                // Add a popup to display weather details
                marker.bindPopup(`<b>Weather in ${place_name}</b><br>Temperature: ${temperature}°C<br>Condition: ${condition}`).openPopup();
            } else {
                console.error('Weather data not available for:', place_name);
            }
        }).catch(error => {
            console.error('Error fetching weather data:', error);
        });
    });
</script>

</body>
</html>
