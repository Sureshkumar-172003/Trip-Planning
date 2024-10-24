<?php include 'user_header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background color */
            color: #e0e0e0; /* Light text color for contrast */
            margin: 0;
            padding: 0;
        }

        .register-container {
            background-color: #1f1f1f; /* Dark container background */
            padding: 20px; /* Reduced padding */
	    padding-top: 10%;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            width: 80%; /* Adjusted width */
            max-width: 400px; /* Reduced maximum width */
            box-sizing: border-box;
            margin: 80px auto 20px; /* Adjusted margin for proper spacing from the navbar */
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        .register-container label {
            display: block;
            margin-bottom: 8px;
            color: #e0e0e0;
            font-weight: bold;
        }

        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 100%; /* Full width inside container */
            padding: 10px; /* Reduced padding */
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            background-color: #333;
            color: #e0e0e0;
        }

        .register-container input[type="submit"] {
            width: 100%; /* Full width inside container */
            padding: 10px; /* Reduced padding */
            background-color: #28a745;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-container input[type="submit"]:hover {
            background-color: #218838;
        }

        .error-message,
        .success-message {
            text-align: center;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: #28a745;
        }

        .create-account {
            text-align: center;
            margin-top: 10px;
        }

        .create-account a {
            color: #1e90ff; /* Light blue color for links */
            text-decoration: none;
        }

        .create-account a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 20px;
                width: 90%;
                margin-top: 70px; /* Adjust for smaller screens */
            }
        }
    </style>
</head>
<body>
    

    <div class="register-container">
        <h2>User Registration</h2>
        <form method="post" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <label for="pin">PIN Code:</label>
            <input type="text" id="pin" name="pin" required>

            <input type="submit" name="register" value="Register">
<div class="create-account">
            <p>Already have an account? <a href="user_login.php">Login</a></p>
        </div>


        </form>
        <?php
        if (isset($_POST['register'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $pin = $_POST['pin'];

            

            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "suresh");
            if (!$conn) {
                die("Connection Failed: " . mysqli_connect_error());
            }

            // Insert user into database
            $query = "INSERT INTO `users` (`username`, `password`, `email`, `first_name`, `last_name`, `phone`, `address`, `state`, `country`, `pin`) 
                      VALUES ('$username', '$password', '$email', '$first_name', '$last_name', '$phone', '$address', '$state', '$country', '$pin')";

            if (mysqli_query($conn, $query)) {
                echo "<p class='success-message'>Registration successful!</p>";
            } else {
                echo "<p class='error-message'>Error: " . mysqli_error($conn) . "</p>";
            }

            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>
