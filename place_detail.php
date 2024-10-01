<?php
// Fetch place name from URL
$place_name = $_GET['id'];

// Connect to the database
include 'database.php';

// Fetch place details
$place_query = "SELECT * FROM place WHERE name = '$place_name'";
$place_result = $conn->query($place_query);
$place = $place_result->fetch_assoc();

// Fetch hotels for the place from 'details' table (admin table) with hotel_name, hotel_image, hotel_price, and hotel_description
$hotel_query = "SELECT hotel_name, hotel_image, hotel_price, hotel_description FROM details WHERE place_name = '$place_name'";
$hotel_result = $conn->query($hotel_query);

// Fetch guides for the place
$guide_query = "SELECT * FROM guide WHERE place_name = '$place_name'";
$guide_result = $conn->query($guide_query);

$sql = "SELECT place_name, lat, lng FROM schedule_places where name='$place_name'";
$result = mysqli_query($conn, $sql);

$places = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $places[] = $row;  // Store the place data in an array
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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
        }

        .form-container {
            width: 60%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
            font-weight: bold;
            margin-top: 15px;
        }

        .form-container select, 
        .form-container input[type="number"], 
        .form-container input[type="submit"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
            margin-bottom: 15px;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-container input[type="number"] {
            width: calc(100% - 22px); /* Adjusting for padding */
        }

        .form-container select {
            width: calc(100% - 22px);
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }
        }

        /* Add styles for hotel images and descriptions */
        .hotel-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .hotel-option img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
            border-radius: 8px;
            object-fit: cover;
        }

        .hotel-description {
            font-size: 0.9rem;
            color: #666;
        }

        .schedule-section {
            margin-top: 15px;
            padding: 10px;
            background-color: #e8e8e8;
            border-radius: 6px;
            border: 1px solid #ccc;
            display: flex;
            flex-direction: column;
        }

        .schedule-section p {
            margin: 5px 0;
            font-weight: bold;
        }

        .schedule-section-empty {
            font-style: italic;
            color: #888;
        }

        /* Style the label for the schedule */
        .schedule-label {
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        /* Hidden section */
        .hidden-section {
            display: none;
        }

        /* Guide option styles */
        .guide-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .guide-option img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
            border-radius: 8px;
            object-fit: cover;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-top: -10px;
        }
        #map {
            height: 300px;
            width:100%;
        }
    </style>
    <script>
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
        }

        // Show additional fields for number of adults, children, and rooms after hotel selection
        function showHotelOptions() {
            document.getElementById('extra-options').style.display = 'block';
        }

        // Function to validate the total number of adults and children
        function validateAdultsChildren() {
            const totalMembers = parseInt(document.getElementById("members").value);
            const adults = parseInt(document.getElementById("adults").value) || 0;
            const children = parseInt(document.getElementById("children").value) || 0;

            if (adults + children > totalMembers) {
                alert("The total number of adults and children cannot exceed the total number of members.");
                document.getElementById("adults").value = '';
                document.getElementById("children").value = '';
            }
        }

        // Function to calculate the total budget
        function calculateBudget() {
            const days = parseInt(document.getElementById('days').value);
            const members = parseInt(document.getElementById('members').value);
            const adults = parseInt(document.getElementById('adults').value);
            const children = parseInt(document.getElementById('children').value);
            const rooms = parseInt(document.getElementById('rooms').value);
            const hotelPrice = parseInt(document.querySelector('input[name="hotel"]:checked').nextElementSibling.nextElementSibling.innerText.split('₹')[1].split(' ')[0]);
            const foodCost = parseInt(document.getElementById('food').selectedOptions[0].text.split('₹')[1].split(' ')[0]);

            if (!days || !members || !adults || !rooms || !hotelPrice || !foodCost) return 0;

            // Calculate total food cost
            const totalFoodCost = members * foodCost * days;

            // Calculate hotel cost (rooms * price per night * days)
            const totalHotelCost = rooms * hotelPrice * days;

            // Total budget
            return totalFoodCost + totalHotelCost;
        }

        // Function to update the budget status and enable/disable the submit button
        function checkBudget() {
            const userBudget = parseInt(document.getElementById('user-budget').value) || 0;
            const calculatedBudget = calculateBudget();

            if (userBudget < calculatedBudget) {
                document.getElementById('budget-error').innerText = `Your budget is less than the required amount (₹${calculatedBudget}).`;
                document.getElementById('submit-btn').disabled = true;
            } else {
                document.getElementById('budget-error').innerText = '';
                document.getElementById('submit-btn').disabled = false;
            }
        }

        // Function to attach budget validation event listeners
        function attachBudgetValidation() {
            document.getElementById('days').addEventListener('change', checkBudget);
            document.getElementById('members').addEventListener('change', checkBudget);
            document.getElementById('adults').addEventListener('change', checkBudget);
            document.getElementById('children').addEventListener('change', checkBudget);
            document.getElementById('rooms').addEventListener('change', checkBudget);
            document.getElementById('food').addEventListener('change', checkBudget);
            document.getElementById('hotel').addEventListener('change', checkBudget);
            document.getElementById('user-budget').addEventListener('input', checkBudget);
        }

        // Initialize validation when the page loads
        window.onload = function() {
            attachBudgetValidation();
            updateSchedule(); // Existing function to update the schedule
        };
    </script>
</head>
<body>
    <h2>Plan Your Trip to <?= htmlspecialchars($place['name']); ?></h2>

    <div class="form-container">
        <form action="calculate_budget.php" method="post">
            <!-- Hidden Field for Place Name -->
            <input type="hidden" name="place_name" value="<?= htmlspecialchars($place['name']); ?>">

            <!-- Number of Days -->
            <label for="days">Select Number of Days (1-3):</label>
            <select name="days" id="days" onchange="updateSchedule()">
                <option value="">Select Days</option>
                <option value="1">1 Day</option>
                <option value="2">2 Days</option>
                <option value="3">3 Days</option>
            </select>

            <!-- Schedule Section -->
            <label class="schedule-label">Schedule:</label>
            <div id="schedule-display" class="schedule-section">
                <!-- Schedule will be dynamically updated here -->
            </div>

            <!-- Number of Members -->
            <label for="members">Enter Number of Members:</label>
            <input type="number" name="members" id="members" min="1" required>

            <!-- Food Preference -->
            <label for="food">Food Preference:</label>
            <select name="food" id="food">
                <option value="veg">Veg - ₹<?= htmlspecialchars($place['veg']); ?> per person</option>
                <option value="nonveg">Non-Veg - ₹<?= htmlspecialchars($place['nonveg']); ?> per person</option>
            </select>

            <!-- Hotel Selection -->
            <label for="hotel">Select Hotel:</label>
            <div id="hotel">
                <?php while($hotel = $hotel_result->fetch_assoc()) { ?>
                    <div class="hotel-option">
                        <input type="radio" name="hotel" value="<?= htmlspecialchars($hotel['hotel_name']); ?>" required onchange="showHotelOptions()">
                        <img src="<?= htmlspecialchars($hotel['hotel_image']); ?>" alt="Hotel Image">
                        <div>
                            <strong><?= htmlspecialchars($hotel['hotel_name']); ?></strong> - ₹<?= htmlspecialchars($hotel['hotel_price']); ?> per night
                            <p class="hotel-description"><?= htmlspecialchars($hotel['hotel_description']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Additional Options (Number of Adults, Children, Rooms) -->
            <div id="extra-options" class="hidden-section">
                <label for="adults">Enter Number of Adults:</label>
                <input type="number" name="adults" id="adults" min="1" required onchange="validateAdultsChildren()">

                <label for="children">Enter Number of Children:</label>
                <input type="number" name="children" id="children" min="0" required onchange="validateAdultsChildren()">

                <label for="rooms">Enter Number of Rooms:</label>
                <input type="number" name="rooms" id="rooms" min="1" required>
            </div>

            <!-- Guide Selection -->
            <label for="guide">Select a Guide:</label>
            <div id="guide">
                <?php while($guide = $guide_result->fetch_assoc()) { ?>
                    <div class="guide-option">
                        <input type="radio" name="guide" value="<?= htmlspecialchars($guide['guide_name']); ?>" required>
                        <img src="data:image/jpeg;base64,<?= base64_encode($guide['guide_image']); ?>" alt="Guide Image">
                        <div>
                            <strong><?= htmlspecialchars($guide['guide_name']); ?></strong> - <?= htmlspecialchars($guide['guide_phone_no']); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Budget Input Field -->
            <label for="user-budget">Enter Your Budget:</label>
            <input type="number" id="user-budget" name="user_budget" min="1" required>
            <div id="budget-error" class="error-message"></div>
            <div id="map"></div>

<!-- Include Leaflet.js -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Use a fallback in case 'place_lat' or 'place_lng' are not set
    var defaultLat = <?php echo isset($place['place_lat']) ? htmlspecialchars($place['place_lat']) : '13.0827'; ?>;
    var defaultLng = <?php echo isset($place['place_lng']) ? htmlspecialchars($place['place_lng']) : '80.2707'; ?>;

    var map = L.map('map').setView([defaultLat, defaultLng], 8);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var places = <?php echo json_encode($places); ?>;
    
    // Check if 'places' is a valid array and iterate
    if (Array.isArray(places)) {
        places.forEach(function(place) {
            var marker = L.marker([parseFloat(place.lat), parseFloat(place.lng)]).addTo(map);
            marker.bindPopup('<h3>' + place.place_name + '</h3>');
        });
    } else {
        console.error("Places data is not an array.");
    }
</script>



            <!-- Submit Button -->
            <input type="submit" id="submit-btn" value="Calculate Budget" disabled>
        </form>
    </div>
</body>
</html>
