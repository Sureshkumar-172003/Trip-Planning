<?php 
session_start();
if(isset($_SESSION['id'])){
include 'admin_dashboard.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel and Guide Details</title>
    <style>
        
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #222;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group input[type="file"],
        .form-group select {
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
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        h3 {
            margin-top: 30px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Hotel and Guide Details for a Place</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="place_id">Select Place:</label>
                <select id="place_id" name="place" required>
                    <option value="">Select a Place</option>
                    <?php
                    // Database connection
                    $conn = mysqli_connect("localhost", "root", "", "suresh");
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Fetch place names from the database
                    $place_query = "SELECT name FROM place";
                    $result = mysqli_query($conn, $place_query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                    }

                    mysqli_free_result($result);
                    ?>
                </select>
            </div>

            <h3>Hotel Details</h3>
            <div class="form-group">
                <label for="hotel_name">Hotel Name:</label>
                <input type="text" id="hotel_name" name="hotel_name" required>
            </div>
            <div class="form-group">
                <label for="hotel_image">Hotel Image:</label>
                <input type="file" id="hotel_image" name="hotel_image" required>
            </div>
            <div class="form-group">
                <label for="hotel_price">Hotel Price (Per Night, Per Person):</label>
                <input type="number" id="hotel_price" name="hotel_price" required>
            </div>
            <div class="form-group">
                <label for="hotel_description">Hotel Description/Feedback:</label>
                <textarea id="hotel_description" name="hotel_description" rows="3" required></textarea>
            </div>

            
            <div class="form-group">
                <button type="submit" name="submit">Add Details</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    // Get selected place ID
    $place_name = $_POST['place'];

    // Hotel details
    $hotel_name = mysqli_real_escape_string($conn, $_POST['hotel_name']);
    $hotel_description = mysqli_real_escape_string($conn, $_POST['hotel_description']);
    $hotel_price = mysqli_real_escape_string($conn, $_POST['hotel_price']);

    // Upload hotel image
    $hotel_image = $_FILES['hotel_image']['name'];
    $target_dir = "uploads/";
    $hotel_target_file = $target_dir . basename($hotel_image);
    move_uploaded_file($_FILES['hotel_image']['tmp_name'], $hotel_target_file);

    // Guide details
   
    // Insert both hotel and guide details into a single table
    $query = "INSERT INTO details (place_name, hotel_name, hotel_image, hotel_price, hotel_description)
              VALUES ('$place_name', '$hotel_name', '$hotel_target_file', '$hotel_price', '$hotel_description')";

    if (mysqli_query($conn, $query)) {
        echo "Details added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
}else { ?><script>alert('please login first')</script><?php header('location:admin_login.php');}?>

