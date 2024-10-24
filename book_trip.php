<?php
session_start();

include 'database.php'; // Ensure you include your database connection

$message = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place_name = $_POST['place_name'];
    $days = $_POST['days'];
    $members = $_POST['members'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $userid = $_SESSION['id'];
    $food = $_POST['food'];
    $hotel = $_POST['hotel'];
    $rooms = $_POST['rooms'];
    $guide = isset($_POST['guide']) ? $_POST['guide'] : 'None';
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $travel_mode = $_POST['travel_mode'];
	

    // Retrieve food prices from the place table based on the selected hotel
    $hotel_query = "SELECT d.hotel_price, p.veg, p.nonveg, p.car, p.bus
                    FROM details AS d 
                    JOIN place AS p ON p.name = '$place_name' 
                    WHERE d.hotel_name = '$hotel'";

    $hotel_result = $conn->query($hotel_query);
    
    if ($hotel_result) {
        $hotel_data = $hotel_result->fetch_assoc();

        // Check if data is returned
        if ($hotel_data) {
            $food_cost = ($food == 'veg') ? $hotel_data['veg'] : $hotel_data['nonveg'];
            $hotel_price = $hotel_data['hotel_price'];
   
            $travel_cost = ($travel_mode == 'car') ? $hotel_data['car'] : $hotel_data['bus'];

            $total_food_cost = ($adults * $food_cost + $children * $food_cost * 0.5) * $days;
            $total_hotel_cost = $rooms * $hotel_price * $days;
            $guide_cost = $guide !== 'None' ? (800 * $days) : 0;
	    $total_travel_cost = $travel_cost * $days;
            $total_budget = $total_food_cost + $total_hotel_cost + $guide_cost + $total_travel_cost;

           
            // Save the booking to the database with 'pending' verification status
            $booking_query = "INSERT INTO bookings (user_id, place_name, fromDate, toDate, days, members, adults, children, food, hotel, rooms, guide, total_budget, verification) 
                              VALUES ('$userid','$place_name', '$fromDate', '$toDate', '$days', '$members', '$adults', '$children', '$food', '$hotel', '$rooms', '$guide', '$total_budget', 'pending')";
            
            if ($conn->query($booking_query) === TRUE) {
                $message = "<div class='booking-confirmation'>";
                $message .= "<h2>Your booking is submitted for review!</h2>";
                $message .= "<h3>The admin will verify your booking shortly.</h3>";
                
                // Display booking details
                $message .= "<h3>Here are your booking details:</h3>";
                $message .= "<p><strong>Destination:</strong> " . htmlspecialchars($place_name) . "</p>";
		$message .= "<p><strong>From Date:</strong> " . htmlspecialchars($fromDate) . "</p>";
                $message .= "<p><strong>To Date:</strong> " . htmlspecialchars($toDate) . "</p>";   
                $message .= "<p><strong>Hotel:</strong> " . htmlspecialchars($hotel) . "</p>";
                $message .= "<p><strong>Days:</strong> " . htmlspecialchars($days) . "</p>";
                $message .= "<p><strong>Members:</strong> " . htmlspecialchars($members) . "</p>";
                $message .= "<p><strong>Adults:</strong> " . htmlspecialchars($adults) . "</p>";
                $message .= "<p><strong>Children:</strong> " . htmlspecialchars($children) . "</p>";
                $message .= "<p><strong>Food Preference:</strong> " . htmlspecialchars($food) . "</p>";
                $message .= "<p><strong>Rooms:</strong> " . htmlspecialchars($rooms) . "</p>";
                $message .= "<p><strong>Guide:</strong> " . htmlspecialchars($guide) . "</p>";
                $message .= "<p><strong>Total Estimated Budget:</strong> ₹" . htmlspecialchars($total_budget) . "</p>";
                $message .= "</div>";

		$subject = "Thank You for Traveling with Us!";
                $email_message = "
                    <h2>Hello!</h2>
                    <p>We hope you had a great trip to <strong>$place_name</strong> and enjoyed your stay at <strong>$hotel</strong>.</p>
                    <p>We'd love to hear about your experience! Please click the link below to leave feedback:</p>
                    <a href='feedback.php'>Leave Feedback</a>
                    <p>Thank you for choosing us!</p>
                ";

                // Send the email (you might want to use a mail library for better handling)
                $to = $_SESSION['id']; // Make sure to set the user's email in session during login
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Suresh Trip Planning";
            } else {
                $message = "<div class='error'>Error: " . $conn->error . "</div>";
            }
        } else {
            $message = "<div class='error'>No hotel data found for the selected hotel.</div>";
        }
    } else {
        $message = "<div class='error'>Error preparing hotel query: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .booking-confirmation {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
        }
        h2 {
            color: #2c3e50;
        }
        h3 {
            color: #34495e;
        }
        p {
            color: #555555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="booking-confirmation">
        <?php echo $message; ?>
    </div>
</body>
</html>
