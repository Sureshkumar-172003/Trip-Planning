<?php
// Fetch place name from URL
$place_name = $_GET['id'];

// Connect to the database
include 'database.php';


// Fetch place details
$place_query = "SELECT * FROM place WHERE name = '$place_name'";
$place_result = $conn->query($place_query);
$place = $place_result->fetch_assoc();

$bus_price = $place['bus'];
$car_price = $place['car'];

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
if (isset($_POST['fromDate']) && isset($_POST['toDate']) && isset($_POST['rooms']) && isset($_POST['hotel_name'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $num_rooms = $_POST['rooms'];
    $hotel_name = $_POST['hotel_name']; // Get the selected hotel name from the form

    // Query to check room availability
    $availability_query = "
        SELECT SUM(num_rooms) as booked_rooms
        FROM book_hotel 
        WHERE hotel_name = ? 
        AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?))
    ";
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($availability_query);
    $stmt->bind_param("sssss", $hotel_name, $toDate, $toDate, $fromDate, $fromDate);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $booked_rooms = $row['booked_rooms'] ? $row['booked_rooms'] : 0;

    // Assuming you have a fixed number of rooms available (e.g., 10)
    $total_available_rooms = 10; 

    if ($booked_rooms + $num_rooms > $total_available_rooms) {
        $availability_message = "Not enough rooms available for the selected dates.";
    } else {
        $availability_message = "Rooms are available for the selected dates.";
    }

    $stmt->close();
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
.form-container input[type="date"],
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
/* Container styling */
label {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

/* Radio button styling */
input[type="radio"] {
    appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #4CAF50;
    border-radius: 50%;
    outline: none;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-right: 8px;
}

/* Radio button checked state */
input[type="radio"]:checked {
    background-color: #4CAF50;
}

/* Label for radio buttons */
label[for="yes"], label[for="no"] {
    font-size: 16px;
    color: #555;
    margin-left: 8px;
    cursor: pointer;
}

/* Hover effect for radio button labels */
label[for="yes"]:hover, label[for="no"]:hover {
    color: #4CAF50;
    transition: color 0.3s;
}

	#user-budget {
    		padding: 10px;
    		border: 1px solid #ddd;
    		border-radius: 5px;
    		font-size: 1rem; /* Keep the font size same */
    		width: 100%; /* Set the width to 100% for consistency */
    		margin-bottom: 15px; /* Maintain spacing below the input */
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
	button {
            padding: 10px;
            font-size: 1rem;
            background-color: #4CAF50; /* Same as submit button */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; /* Match width with other inputs */
        }

        button:hover {
            background-color: #45a049; /* Match hover effect */
        }
    </style>
    <script>

	    document.addEventListener('DOMContentLoaded', function() {
            const toDateInput = document.getElementById('toDate');
            const fromDateInput = document.getElementById('fromDate');
            const daysInput = document.getElementById('days');
	   
            fromDateInput.addEventListener('change', function() {
                const fromDate = new Date(fromDateInput.value);
                const toDate = new Date(fromDate);
                toDate.setDate(fromDate.getDate() + 3); // Set max to 3 days after fromDate
                
                const maxToDate = toDate.toISOString().split('T')[0];
                toDateInput.setAttribute('max', maxToDate);
                
                // Automatically select the number of days based on from and to date
                if (toDateInput.value) {
                    updateDaysCount(fromDate, new Date(toDateInput.value));
                }
            });

            toDateInput.addEventListener('change', function() {
                const fromDate = new Date(fromDateInput.value);
                if (fromDateInput.value) {
                    updateDaysCount(fromDate, new Date(toDateInput.value));
                }
            });
        });

	
        function updateDaysCount(fromDate, toDate) {
            const timeDiff = toDate - fromDate;
            const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert time difference to days
            document.getElementById("days").value = days > 0 ? days : 1; // Ensure at least 1 day
            updateSchedule();
            calculateBudget();
        }
	
	let guideCost = 0; // Initialize guide cost

	// Attach event listeners for guide cost options
	document.addEventListener('DOMContentLoaded', function() {
   	 document.getElementById('yes').addEventListener('click', function() {
        guideCost = 800 * parseInt(document.getElementById('days').value); // Calculate guide cost based on days
        calculateBudget(); // Recalculate budget
    	});

    	document.getElementById('no').addEventListener('click', function() {
        guideCost = 0; // Reset guide cost
        calculateBudget(); // Recalculate budget
    	});
	});

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

        function calculateBudget() {
            const days = parseInt(document.getElementById('days').value) || 0;
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            const rooms = parseInt(document.getElementById('rooms').value) || 0;

            const hotelElement = document.querySelector('input[name="hotel"]:checked');
            const foodElement = document.getElementById('food');
	    const travelModeElement = document.getElementById('travel-mode');


            if (!days || !rooms || !hotelElement || !foodElement || !travelModeElement) {
                document.getElementById('submit-btn').disabled = true;
                return;
            }

            // Extract hotel price from selected hotel
            const hotelPrice = parseInt(
                hotelElement.nextElementSibling.nextElementSibling.innerText.split('₹')[1].split(' ')[0]
            );

            // Extract food cost from selected food preference
            const foodCost = parseInt(
                foodElement.selectedOptions[0].text.split('₹')[1].split(' ')[0]
            );

	    const travelModePrice = parseInt(
        	travelModeElement.selectedOptions[0].text.split('₹')[1].split(' ')[0]
    	     );

            // Calculate total costs
            const totalFoodCost = (adults * foodCost + children * foodCost * 0.5) * days;
            const totalHotelCost = rooms * hotelPrice * days;
	    const totalTravelCost = travelModePrice * days;

	    const totalBudget = totalFoodCost + totalHotelCost + totalTravelCost + guideCost;

            // Display the calculated budget
            document.getElementById('user-budget').value = `₹${totalBudget}`;

            // Enable the submit button only if a valid budget is calculated
            document.getElementById('submit-btn').disabled = false;

        }

        // Validate adults and children count
        function validateAdultsChildren() {
            const totalMembers = parseInt(document.getElementById('members').value);
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;

            if (adults + children > totalMembers) {
                alert("Total adults and children cannot exceed the total members.");
                document.getElementById('adults').value = '';
                document.getElementById('children').value = '';
            }
        }

        // Attach listeners for calculation and validation
        function attachEventListeners() {
            document.getElementById('days').addEventListener('change', calculateBudget);
            document.getElementById('adults').addEventListener('change', () => {
                validateAdultsChildren();
                calculateBudget();
            });
            document.getElementById('children').addEventListener('change', () => {
                validateAdultsChildren();
                calculateBudget();
            });
            document.getElementById('rooms').addEventListener('change', calculateBudget);
            document.getElementById('food').addEventListener('change', calculateBudget);

            const hotelOptions = document.querySelectorAll('input[name="hotel"]');
            hotelOptions.forEach(option =>
                option.addEventListener('change', calculateBudget)
            );
        }

        // Initialize listeners when the page loads
        window.onload = function () {
            attachEventListeners();
        };
    </script>
</head>
<body>
    <h2>Plan Your Trip to <?= htmlspecialchars($place['name']); ?></h2>

    <div class="form-container">
        <form action="book_trip.php" method="post">
            <!-- Hidden Field for Place Name -->
            <input type="hidden" name="place_name" value="<?= htmlspecialchars($place['name']); ?>">

	    <label for="fromDate">From Date:</label>
            <input type="date" id="fromDate" name="fromDate" required>

            <label for="toDate">To Date:</label>
            <input type="date" id="toDate" name="toDate" required>


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

	    <!-- Travel Mode Selection -->
	    <label for="travel-mode">Select Travel Mode:</label>
	    <select name="travel_mode" id="travel-mode">
    		<option value="bus">Bus - ₹<?= htmlspecialchars($bus_price); ?></option>
    		<option value="car">Car - ₹<?= htmlspecialchars($car_price); ?></option>
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

                <label for="rooms">Number of Rooms:</label>
        	<input type="number" id="rooms" name="rooms" required min="1">

        	
            </div>

           <label>Do you need a guide?</label><br>
	   <input type="radio" id="yes" name="guide" value="yes">
	   <label for="yes">Yes</label><br>
	   <input type="radio" id="no" name="guide" value="no">
	   <label for="no">No</label><br>

            <!-- Budget Input Field -->
           <label for="user-budget">Estimated Budget :</label>
           <input type="text" id="user-budget" name="user_budget" readonly>            
           <div id="map"></div>

<!-- Include Leaflet.js -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
   
        // Fetch weather data for given latitude and longitude
        async function getWeatherData(lat, lng) {
            const api_key = '7JFTHRXBCNEZJDP6H9NYY2AE2'; // Replace with your Visual Crossing API key
            const weather_url = `https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/${lat},${lng}?unitGroup=metric&key=${api_key}&contentType=json`;

            const response = await fetch(weather_url);
            const data = await response.json();
            return data;
        }

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
                    if (data && data.currentConditions) {
                        const temperature = data.currentConditions.temp;
                        const condition = data.currentConditions.conditions;

                        // Add a marker to the map
                        const marker = L.marker([lat, lng]).addTo(map);

                        // Add a popup to display weather details
                        marker.bindPopup(`<b>${place_name}</b><br>Temperature: ${temperature}°C<br>Condition: ${condition}`).openPopup();
                    } else {
                        console.error('Weather data not available for:', place_name);
                    }
                }).catch(error => {
                    console.error('Error fetching weather data:', error);
                });
            });
        }

        // Initialize the map when the page loads
        window.onload = function () {
            initializeMap();
            attachEventListeners(); // Attach event listeners for form validation and budget calculation
        };
</script>



            <!-- Submit Button -->
      		<button type="submit">Book Now</button>
	
        </form>
    </div>
</body>
</html>