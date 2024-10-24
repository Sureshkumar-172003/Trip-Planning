<?php
include 'database.php'; // Include database connection

// Include PHPMailer classes
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'D:/xampp/htdocs/suresh/phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$pending_bookings = [];

// Fetch pending bookings from the database
$query = "SELECT * FROM bookings WHERE verification = 'pending'";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pending_bookings[] = $row;
    }
} else {
    $message = "Error fetching bookings: " . $conn->error;
}

// Verify or cancel booking
if (isset($_POST['verify']) || isset($_POST['cancel'])) {
    $booking_id = $_POST['booking_id'];

    // Check if it's a cancellation or verification
    if (isset($_POST['verify'])) {
        $status = 'verified';
        $email_subject = 'Booking Verified';
        $email_body = "<h2>Your booking has been verified!</h2>";
        $message_success = "Booking verified successfully!";
    } else {
        $status = 'rejected';
        $email_subject = 'Booking Rejected';
        $email_body = "<h2>Unfortunately, your booking has been rejected.</h2>";
        $message_success = "Booking rejected successfully!";
    }

    // Update the booking status
    $update_query = "UPDATE bookings SET verification = '$status' WHERE id = '$booking_id'";
    
    if ($conn->query($update_query) === TRUE) {
        $message = $message_success;

        // Fetch user's email from the booking record
        $email_query = "SELECT u.email, b.* FROM bookings AS b JOIN users AS u ON b.user_id = u.id WHERE b.id = '$booking_id'";
        $email_result = $conn->query($email_query);

        if ($email_result && $email_result->num_rows > 0) {
            $booking_details = $email_result->fetch_assoc();
            $user_email = $booking_details['email'];

            // Send email to the user
            $mail = new PHPMailer(true);
            try {
                // SMTP server configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sureshkumar17may@gmail.com'; // Your email
                $mail->Password = 'defqwiyhgjorizft'; // Your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('sureshkumar17may@gmail.com', 'Suresh Trip Planning');
                $mail->addAddress($user_email); // Add user's email

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $email_subject . ': Your Trip to ' . htmlspecialchars($booking_details['place_name']);
                $mail->Body = $email_body;

                // Common email body details for both verification and cancellation
                $mail->Body .= "<h3>Here are your booking details:</h3>";
                $mail->Body .= "<p><strong>Destination:</strong> " . htmlspecialchars($booking_details['place_name']) . "</p>";
		$mail->Body .= "<p><strong>From Date</strong> " . htmlspecialchars($booking_details['fromDate']) . "</p>";
		$mail->Body .= "<p><strong>To Date:</strong> " . htmlspecialchars($booking_details['toDate']) . "</p>";
                $mail->Body .= "<p><strong>Hotel:</strong> " . htmlspecialchars($booking_details['hotel']) . "</p>";
                $mail->Body .= "<p><strong>Days:</strong> " . htmlspecialchars($booking_details['days']) . "</p>";
                $mail->Body .= "<p><strong>Members:</strong> " . htmlspecialchars($booking_details['members']) . "</p>";
                $mail->Body .= "<p><strong>Adults:</strong> " . htmlspecialchars($booking_details['adults']) . "</p>";
                $mail->Body .= "<p><strong>Children:</strong> " . htmlspecialchars($booking_details['children']) . "</p>";
                $mail->Body .= "<p><strong>Guide:</strong> " . htmlspecialchars($booking_details['guide']) . "</p>";
                $mail->Body .= "<p><strong>Total Estimated Budget:</strong> ₹" . htmlspecialchars($booking_details['total_budget']) . "</p>";
                                
                if ($status == 'verified') {
                    $mail->Body .= "<h4>Your booking has been successfully verified. We hope you have a great trip!</h4>";
                } else {
                    $mail->Body .= "<h4>We regret to inform you that your booking has been cancelled. Please contact us for more details.</h4>";
                }

                // Send email
                $mail->send();
                $message .= " Email notification sent to the user.";

            } catch (Exception $e) {
                $message .= " Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Error fetching user's email: " . $conn->error;
        }
    } else {
        $message = "Error updating booking status: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verify/Cancel Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            color: green;
            margin-bottom: 15px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button.cancel {
            background-color: #f44336;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Pending Bookings</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <table>
        <tr>
            <th>Destination</th>
	    <th>fromDate</th>
	    <th>toDate</th>
            <th>Hotel</th>
            <th>Days</th>
            <th>Adults</th>
            <th>Children</th>
            <th>Guide</th>
	    <th>Total Budget</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($pending_bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['place_name']); ?></td>
		<td><?php echo htmlspecialchars($booking['fromDate']); ?></td>
		<td><?php echo htmlspecialchars($booking['toDate']); ?></td>
                <td><?php echo htmlspecialchars($booking['hotel']); ?></td>
                <td><?php echo htmlspecialchars($booking['days']); ?></td>
                <td><?php echo htmlspecialchars($booking['adults']); ?></td>
                <td><?php echo htmlspecialchars($booking['children']); ?></td>
                <td><?php echo htmlspecialchars($booking['guide']); ?></td>
		<td>₹<?php echo htmlspecialchars($booking['total_budget']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                        <button type="submit" name="verify">Verify</button>
                        <button type="submit" name="cancel" class="cancel">Cancel</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
