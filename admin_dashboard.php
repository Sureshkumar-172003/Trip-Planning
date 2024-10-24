<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #ddd;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #222;
            color: #fff;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .nav-bar {
            display: flex;
            justify-content: center;
            background-color: green;
            padding: 10px 0;
        }
        .nav-bar a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .nav-bar a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #222;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }
        h2, h3 {
            color: #fff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ddd;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: calc(100% - 20px);
            padding: 12px;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #333;
            color: #fff;
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-group button {
            padding: 12px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="nav-bar">
        <a href="admin_dashboard.php">Home</a>
        <a href="add_place.php">Add Place</a>
        <a href="details.php">Add Hotel</a>
        <a href="admin_verify.php">Verify Bookings</a> <!-- Link to verify bookings -->
        <a href="admin_verified.php">Confirmed Bookings</a> <!-- Link to view verified bookings -->
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Welcome to the Admin Dashboard!</h2>
        <p>Use the links above to navigate through the admin functionalities.</p>
    </div>
</body>
</html>
