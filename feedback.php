<?php
session_start();
include 'database.php'; // Ensure you include your database connection

$message = ''; // Initialize message variable

// Check if feedback is being submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent SQL injection
    $booking_id = $conn->real_escape_string($_POST['booking_id']);
    $trip_rating = $conn->real_escape_string($_POST['trip_rating']);
    $hotel_rating = $conn->real_escape_string($_POST['hotel_rating']);
    $guide_rating = $conn->real_escape_string($_POST['guide_rating']);
    $trip_comments = $conn->real_escape_string($_POST['trip_comments']);
    $hotel_comments = $conn->real_escape_string($_POST['hotel_comments']);
    $guide_comments = $conn->real_escape_string($_POST['guide_comments']);

    // Insert feedback into the database
    $feedback_query = "INSERT INTO feedback (booking_id, trip_rating, hotel_rating, guide_rating, 
                       trip_comments, hotel_comments, guide_comments) 
                       VALUES ('$booking_id', '$trip_rating', '$hotel_rating', '$guide_rating', 
                       '$trip_comments', '$hotel_comments', '$guide_comments')";

    if ($conn->query($feedback_query) === TRUE) {
        $message = "Feedback submitted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .feedback-form {
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
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            margin: 20px 0;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="feedback-form">
        <h2>Feedback Form</h2>
        <?php if ($message) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="booking_id">Booking ID:</label>
            <input type="text" name="booking_id" required><br>

            <h3>Overall Trip Feedback</h3>
            <label for="trip_rating">Rating (1-5):</label>
            <input type="number" name="trip_rating" min="1" max="5" required><br>
            <label for="trip_comments">Comments:</label><br>
            <textarea name="trip_comments" required></textarea><br>

            <h3>Hotel Feedback</h3>
            <label for="hotel_rating">Rating (1-5):</label>
            <input type="number" name="hotel_rating" min="1" max="5" required><br>
            <label for="hotel_comments">Comments:</label><br>
            <textarea name="hotel_comments" required></textarea><br>

            <h3>Guide Feedback</h3>
            <label for="guide_rating">Rating (1-5):</label>
            <input type="number" name="guide_rating" min="1" max="5" required><br>
            <label for="guide_comments">Comments:</label><br>
            <textarea name="guide_comments" required></textarea><br>

            <input type="submit" value="Submit Feedback">
        </form>
    </div>
</body>
</html>
