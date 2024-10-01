<?php
// Connect to MySQL database
$conn = mysqli_connect("localhost", "root", "", "suresh");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all places with their coordinates
$sql = "SELECT place_name, lat, lng FROM schedule_places";
$result = mysqli_query($conn, $sql);

$places = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $places[] = $row;  // Store the place data in an array
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Multiple Places on Map</title>

    <!-- Include Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Places on the Map</h2>
    <div id="map"></div>

    <!-- Include Leaflet.js -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Initialize the map and set its center
        var map = L.map('map').setView([13.0827, 80.2707], 8); // Default center: Chennai, India

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Array to store place data from PHP
        var places = <?php echo json_encode($places); ?>;

        // Loop through the places array and create markers for each place
        places.forEach(function(place) {
            // Create marker at the location of the place
            var marker = L.marker([parseFloat(place.lat), parseFloat(place.lng)]).addTo(map);

            // Optionally bind a popup to each marker
            marker.bindPopup('<h3>' + place.place_name + '</h3>');
        });
    </script>

</body>
</html>
