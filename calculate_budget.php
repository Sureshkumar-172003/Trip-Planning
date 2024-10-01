<?php
// Get form data
$days = $_POST['days'];
$members = $_POST['members'];
$food = $_POST['food'];
$hotel = $_POST['hotel'];
$travel = $_POST['travel'];
$guide = $_POST['guide'];
$budget = $_POST['budget'];
$place_name = $_POST['place_name'];

// Connect to the database
include 'database.php';

// Fetch hotel price
$hotel_query = "SELECT hotel_price FROM details WHERE hotel_name = '$hotel'";
$hotel_result = $conn->query($hotel_query)->fetch_assoc();
$hotel_price = $hotel_result['hotel_price'];

// Fetch place details for food and travel pricing
$place_query = "SELECT * FROM place WHERE name = '$place_name'";
$place = $conn->query($place_query)->fetch_assoc();

// Get prices for selected food, travel, and hotel
$food_price = ($food == 'veg') ? $place['veg'] : $place['nonveg'];
$travel_price = ($travel == 'bus') ? $place['bus'] : $place['train'];

// Calculate cost per person
$cost_per_person = ($food_price * $days) + ($hotel_price * $days) + $travel_price;

// Calculate total cost for all members
$total_cost = $cost_per_person * $members;

// Check if user's budget is sufficient
if ($budget < $total_cost) {
    echo "Your budget is too low! Total estimated cost is ₹$total_cost.";
} else {
    echo "Your trip is planned successfully! Total estimated cost is ₹$total_cost.";
}
?>
