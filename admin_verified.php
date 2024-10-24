<?php
include 'database.php'; // Include database connection

$message = '';
$verified_bookings = [];

// Fetch verified bookings from the database
$query = "SELECT * FROM bookings WHERE verification = 'verified'";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $verified_bookings[] = $row;
    }
} else {
    $message = "Error fetching verified bookings: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verified Bookings</title>
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
    </style>
</head>
<body>
    <h1>Confirmed Bookings</h1>
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
        </tr>
        <?php foreach ($verified_bookings as $booking): ?>
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
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
