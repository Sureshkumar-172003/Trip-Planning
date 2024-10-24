<?php
include 'database.php'; // Include your database connection

// Include PHPMailer classes
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get the current date
$currentDate = date('Y-m-d');

// Fetch bookings that ended today
$query = "SELECT b.*, u.email FROM bookings AS b JOIN users AS u ON b.user_id = u.id WHERE b.toDate = '$currentDate'";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $userEmail = $row['email'];
        $placeName = $row['place_name'];
        $fromDate = $row['fromDate'];
        $bookingId = $row['id']; // Get the booking ID for the feedback link

        // Send the follow-up email
        sendFollowUpEmail($userEmail, $placeName, $fromDate, $bookingId);
    }
} else {
    echo "Error fetching bookings: " . $conn->error;
}

// Function to send follow-up email
function sendFollowUpEmail($userEmail, $placeName, $fromDate, $bookingId) {
    $subject = "How Was Your Trip to $placeName?";
    $feedbackLink = "http://localhost/suresh/feedback.php?booking_id=$bookingId";


    $body = "<h2>Dear User,</h2>";
    $body .= "<p>We hope you enjoyed your trip to <strong>$placeName</strong> from <strong>$fromDate</strong>!</p>";
    $body .= "<p>Your feedback is important to us. Please take a moment to share your thoughts about your trip by clicking the link below:</p>";
    $body .= "<p><a href='$feedbackLink'>Give Feedback</a></p>";
    $body .= "<p>Thank you for choosing us!</p>";
    $body .= "<p>Best regards,<br>The Team</p>";

    // Use PHPMailer to send the follow-up email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sureshkumar17may@gmail.com'; // Your email
        $mail->Password = 'defqwiyhgjorizft'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sureshkumar17may@gmail.com', 'Suresh Trip Planning');
        $mail->addAddress($userEmail); // Add user's email

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Follow-up email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
