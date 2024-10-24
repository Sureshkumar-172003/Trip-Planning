<?php
session_start();
include 'user_header.php';
include 'database.php'; // Include your database connection

if (!isset($_SESSION['userid'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Fetch the user details from the database using the session username
$username = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
    <script>
        let isEditing = false;

        function toggleEdit() {
            const fields = document.querySelectorAll('.editable');
            const editButton = document.querySelector('.edit-button');

            isEditing = !isEditing; // Toggle editing mode

            fields.forEach(field => {
                field.readOnly = !isEditing; // Make fields editable based on the mode
            });

            if (isEditing) {
                editButton.textContent = "Save Changes"; // Change button text to Save
            } else {
                editButton.textContent = "Edit Profile"; // Change button text back to Edit

                // Validate fields before saving
                let isValid = true;
                const email = document.getElementById('email');
                const phone = document.getElementById('phone');
                const pin = document.getElementById('pin');

                // Reset validation styles
                [email, phone, pin].forEach(input => {
                    input.style.borderColor = '';
                });

                // Perform validation
                if (email.value && !validateEmail(email.value)) {
                    alert("Please enter a valid email address.");
                    email.style.borderColor = 'red';
                    isValid = false;
                }
                if (phone.value && !validatePhone(phone.value)) {
                    alert("Please enter a valid phone number.");
                    phone.style.borderColor = 'red';
                    isValid = false;
                }
                if (pin.value && !validatePin(pin.value)) {
                    alert("Please enter a valid PIN code.");
                    pin.style.borderColor = 'red';
                    isValid = false;
                }

                if (isValid) {
                    // Save changes via AJAX or form submission (if needed)
                    alert("Changes saved successfully!");
                    // Here you can add the code to save changes to the database.
                } else {
                    // If validation fails, keep the fields in edit mode
                    isEditing = true;
                    fields.forEach(field => {
                        field.readOnly = false;
                    });
                }
            }
        }

        // Email validation
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        // Phone validation (example for 10 digits)
        function validatePhone(phone) {
            const re = /^[0-9]{10}$/;
            return re.test(String(phone));
        }

        // PIN validation (example for 6 digits)
        function validatePin(pin) {
            const re = /^[0-9]{6}$/;
            return re.test(String(pin));
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
            max-width: 800px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        .profile-details {
            margin: 20px 0;
            padding: 15px;
	    padding-top: 20%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-details p {
            margin: 10px 0;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            width: 95%;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input[readonly] {
            background-color: #f0f0f0;
        }

        .edit-button, .logout-button {
            display: inline-block;
            padding: 10px 15px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .edit-button {
            background-color: #28a745;
            color: white;
        }

        .edit-button:hover {
            background-color: #218838;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
        }

        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>!</h1>

    <div class="profile-details">
        <h2>Your Profile Details</h2>
        <p><strong>First Name:</strong> <input type="text" class="editable" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" readonly></p>
        <p><strong>Last Name:</strong> <input type="text" class="editable" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" readonly></p>
        <p><strong>Email:</strong> <input type="email" class="editable" id="email" name="email" value="<?php echo $user['email']; ?>" readonly></p>
        <p><strong>Username:</strong> <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" readonly></p>
        <p><strong>Phone Number:</strong> <input type="text" class="editable" id="phone" name="phone" value="<?php echo $user['phone']; ?>" readonly></p>
        <p><strong>Address:</strong> <input type="text" class="editable" id="address" name="address" value="<?php echo $user['address']; ?>" readonly></p>
        <p><strong>State:</strong> <input type="text" class="editable" id="state" name="state" value="<?php echo $user['state']; ?>" readonly></p>
        <p><strong>Country:</strong> <input type="text" class="editable" id="country" name="country" value="<?php echo $user['country']; ?>" readonly></p>
        <p><strong>PIN Code:</strong> <input type="text" class="editable" id="pin" name="pin" value="<?php echo $user['pin']; ?>" readonly></p>
    </div>

    <button class="edit-button" type="button" onclick="toggleEdit()">Edit Profile</button>
    <button class="logout-button" onclick="window.location.href='logout.php';">Logout</button>
</body>
</html>
