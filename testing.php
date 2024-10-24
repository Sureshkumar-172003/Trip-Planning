<?php
// Fetch place name from URL
$place_name = $_GET['id'];

// Connect to the database
include 'database.php';

// Fetch place details
$place_query = "SELECT * FROM place WHERE name = '$place_name'";
$place_result = $conn->query($place_query);
$place = $place_result->fetch_assoc();

// Fetch hotels for the place from 'details' table
$hotel_query = "SELECT hotel_name, hotel_image, hotel_price, hotel_description, available_rooms FROM details WHERE place_name = '$place_name'";
$hotel_result = $conn->query($hotel_query);

// Fetch guides for the place
$guide_query = "SELECT * FROM guide WHERE place_name = '$place_name'";
$guide_result = $conn->query($guide_query);

$sql = "SELECT place_name, lat, lng FROM schedule_places WHERE name='$place_name'";
$result = mysqli_query($conn, $sql);

$places = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $places[] = $row; // Store the place data in an array
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Trip to <?= htmlspecialchars($place['name']); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize calendar for date selection
        flatpickr("#travel-dates", {
            mode: "range",
            minDate: "today"
        });

        // Check if the selected hotel has enough rooms
        function validateRooms() {
            const hotelOption = document.querySelector('input[name="hotel"]:checked');
            const selectedRooms = parseInt(document.getElementById('rooms').value) || 0;
            const availableRooms = parseInt(hotelOption.dataset.rooms);

            if (selectedRooms > availableRooms) {
                alert(`The selected hotel only has ${availableRooms} rooms available.`);
                document.getElementById('rooms').value = '';
            }
        }

        // Function to calculate the total budget
        function calculateTotalBudget() {
            const selectedHotel = document.querySelector('input[name="hotel"]:checked');
            const hotelPrice = selectedHotel ? parseInt(selectedHotel.dataset.price) : 0;
            const members = parseInt(document.getElementById('members').value) || 0;
            const foodPreference = document.getElementById('food').value;
            const foodCost = foodPreference === "veg" ? <?= htmlspecialchars($place['veg']); ?> : <?= htmlspecialchars($place['nonveg']); ?>;
            const days = document.getElementById('days').value || 1;

            // Fetch selected travel mode price
            const travelMode = document.querySelector('input[name="travel-mode"]:checked');
            const travelPrice = travelMode ? parseInt(travelMode.dataset.price) : 0;

            const totalCost = (hotelPrice * days) + (foodCost * members * days) + (travelPrice * days);
            document.getElementById('user-budget').value = `₹${totalCost}`;
        }

        // Function to update the schedule dynamically based on the number of days selected
        function updateSchedule() {
            const days = document.getElementById("days").value;
            let schedule = "";

            // Update schedule based on the selected number of days
            if (days == "1") {
                schedule = "<p>Schedule for Day 1: <?= htmlspecialchars($place['schedule_1']); ?></p>";
            } else if (days == "2") {
                schedule = "<p>Schedule for Day 1: <?= htmlspecialchars($place['schedule_1']); ?></p><p>Schedule for Day 2: <?= htmlspecialchars($place['schedule_2']); ?></p>";
            } else if (days == "3") {
                schedule = "<p>Schedule for Day 1: <?= htmlspecialchars($place['schedule_1']); ?></p><p>Schedule for Day 2: <?= htmlspecialchars($place['schedule_2']); ?></p><p>Schedule for Day 3: <?= htmlspecialchars($place['schedule_3']); ?></p>";
            } else {
                schedule = "<p class='schedule-section-empty'>Please select the number of days to see the schedule.</p>";
            }

            // Display the updated schedule in the schedule section
            document.getElementById("schedule-display").innerHTML = schedule;
            calculateTotalBudget(); // Recalculate budget when the schedule is updated
        }

        // Attach event listener for room validation
        function attachRoomValidation() {
            const hotelOptions = document.querySelectorAll('input[name="hotel"]');
            hotelOptions.forEach(option =>
                option.addEventListener('change', validateRooms)
            );
        }

        // Initialize the map when the page loads
        window.onload = function () {
            initializeMap();
            attachRoomValidation();
        };
    </script>
</head>
<body>
    <h2>Plan Your Trip to <?= htmlspecialchars($place['name']); ?></h2>

    <div class="form-container">
        <form action="book_trip.php" method="post">
            <!-- Hidden Field for Place Name -->
            <input type="hidden" name="place_name" value="<?= htmlspecialchars($place['name']); ?>">

            <!-- Travel Dates -->
            <label for="travel-dates">Select Travel Dates:</label>
            <input type="text" id="travel-dates" name="travel_dates" required>

            <!-- Number of Days -->
            <label for="days">Select Number of Days (1-3):</label>
            <select name="days" id="days" onchange="updateSchedule()">
                <option value="">Select Days</option>
                <option value="1">1 Day</option>
                <option value="2">2 Days</option>
                <option value="3">3 Days</option>
            </select>

            <!-- Travel Mode Selection -->
            <label for="travel-mode">Select Travel Mode:</label>
            <div>
                <label>
                    <input type="radio" name="travel-mode" value="bus" data-price="<?= htmlspecialchars($place['bus']); ?>" onclick="calculateTotalBudget()">
                    Bus (₹<?= htmlspecialchars($place['bus']); ?>)
                </label>
                <label>
                    <input type="radio" name="travel-mode" value="train" data-price="<?= htmlspecialchars($place['train']); ?>" onclick="calculateTotalBudget()">
                    Train (₹<?= htmlspecialchars($place['train']); ?>)
                </label>
            </div>

            <!-- Schedule Section -->
            <label class="schedule-label">Schedule:</label>
            <div id="schedule-display" class="schedule-section">
                <!-- Schedule will be dynamically updated here -->
            </div>

            <!-- Number of Members -->
            <label for="members">Enter Number of Members:</label>
            <input type="number" name="members" id="members" min="1" required onchange="calculateTotalBudget()">

            <!-- Food Preference -->
            <label for="food">Food Preference:</label>
            <select name="food" id="food" onchange="calculateTotalBudget()">
                <option value="veg">Veg - ₹<?= htmlspecialchars($place['veg']); ?> per person</option>
                <option value="nonveg">Non-Veg - ₹<?= htmlspecialchars($place['nonveg']); ?> per person</option>
            </select>

            <!-- Hotel Selection -->
            <label for="hotel">Select Hotel:</label>
            <div id="hotel">
                <?php while($hotel = $hotel_result->fetch_assoc()) { ?>
                    <div class="hotel-option">
                        <input type="radio" name="hotel" value="<?= htmlspecialchars($hotel['hotel_name']); ?>" data-rooms="<?= htmlspecialchars($hotel['available_rooms']); ?>" data-price="<?= htmlspecialchars($hotel['hotel_price']); ?>" required>
                        <img src="<?= htmlspecialchars($hotel['hotel_image']); ?>" alt="Hotel Image">
                        <div>
                            <strong><?= htmlspecialchars($hotel['hotel_name']); ?></strong> - ₹<?= htmlspecialchars($hotel['hotel_price']); ?> per night
                            <p class="hotel-description"><?= htmlspecialchars($hotel['hotel_description']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Budget Input Field -->
            <label for="user-budget">Estimated Budget :</label>
            <input type="text" id="user-budget" name="user_budget" readonly>            

            <div id="map"></div>

            <!-- Include Leaflet.js -->
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

            <script>
                // Initialize the map and markers with weather data
                function initializeMap() {
                    const defaultLat = <?php echo isset($place['lat']) ? htmlspecialchars($place['lat']) : '20'; ?>;
                    const defaultLng = <?php echo isset($place['lng']) ? htmlspecialchars($place['lng']) : '80'; ?>;

                    const map = L.map('map').setView([defaultLat, defaultLng], 8);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const places = <?php echo json_encode($places); ?>;
                    places.forEach(function(place) {
                        const lat = parseFloat(place.lat);
                        const lng = parseFloat(place.lng);
                        const place_name = place.place_name;

                        // Fetch weather for each place
                        getWeatherData(lat, lng).then(data => {
                            if (data) {
                                const weatherDescription = data.weather[0].description;
                                L.marker([lat, lng]).addTo(map)
                                    .bindPopup(`<b>${place_name}</b><br>${weatherDescription}`).openPopup();
                            }
                        });
                    });
                }

                // Function to get weather data
                async function getWeatherData(lat, lng) {
                    const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lng}&appid=YOUR_API_KEY&units=metric`);
                    return response.json();
                }
            </script>

            <!-- Submit Button -->
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>
