

<?php 
session_start();

// Check if session ID is set
if (isset($_SESSION['id'])) {
    include 'admin_dashboard.php';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <style>
            
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
        
        <div class="container">
            <h2>Add New Place</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="place_name">Place Name:</label>
                    <input type="text" id="place_name" name="place_name" autocomplete = off required>
                </div>
                <div class="form-group">
                    <label for="place_image">Place Image:</label>
                    <input type="file" id="place_image" name="place_image" required>
                </div>
                <div class="form-group">
                    <label for="place_description">Place Description:</label>
                    <textarea id="place_description" name="place_description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="food_veg_price">Food Price (Veg, Per Day):</label>
                    <input type="number" id="food_veg_price" name="food_veg_price[]" required>
                </div>
                <div class="form-group">
                    <label for="food_nonveg_price">Food Price (Non-Veg, Per Day):</label>
                    <input type="number" id="food_nonveg_price" name="food_nonveg_price[]" required>
                </div>
                <h3>Schedule Details</h3>
                <div class="form-group">
                    <label for="schedule_day1">Day 1 Schedule:</label>
                    <textarea id="schedule_day1" name="schedule_day1" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label for="schedule_day2">Day 2 Schedule:</label>
                    <textarea id="schedule_day2" name="schedule_day2" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label for="schedule_day3">Day 3 Schedule:</label>
                    <textarea id="schedule_day3" name="schedule_day3" rows="2" required></textarea>
                </div>
                <h3>Travel Details</h3>
                <div class="form-group">
                    <label for="car_price">Bus Travel Price (Per Day):</label>
                    <input type="number" id="car_price" name="car_price" required>
                </div>
                <div class="form-group">
                    <label for="train_price">Train Travel Price (Per Day):</label>
                    <input type="number" id="train_price" name="train_price" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit">Add Place</button>
                </div>
            </form>
        </div>
    </body>
    </html>

    <?php
    // Handle form submission
    if (isset($_POST['submit'])) {
        $conn = mysqli_connect("localhost", "root", "", "suresh");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);
        $place_description = mysqli_real_escape_string($conn, $_POST['place_description']);
        $food_veg_price = mysqli_real_escape_string($conn, $_POST['food_veg_price'][0]);
        $food_nonveg_price = mysqli_real_escape_string($conn, $_POST['food_nonveg_price'][0]);
        $schedule_day1 = mysqli_real_escape_string($conn, $_POST['schedule_day1']);
        $schedule_day2 = mysqli_real_escape_string($conn, $_POST['schedule_day2']);
        $schedule_day3 = mysqli_real_escape_string($conn, $_POST['schedule_day3']);
        $car_price = mysqli_real_escape_string($conn, $_POST['car_price']);
        $train_price = mysqli_real_escape_string($conn, $_POST['train_price']);

        $place_image = addslashes(file_get_contents($_FILES['place_image']['tmp_name']));
        
 // Handle file upload for place image
        // $target_dir = "uploads/";
        // $place_image = $target_dir . basename($_FILES["place_image"]["name"]);
        // $imageFileType = strtolower(pathinfo($place_image, PATHINFO_EXTENSION));
        
        // if (!file_exists('uploads')) {
        //     mkdir('uploads', 0777, true);
        // }
        
        // $check = getimagesize($_FILES["place_image"]["tmp_name"]);
        // if ($check !== false) {
        //     // Upload the file
        //     if (move_uploaded_file($_FILES["place_image"]["tmp_name"], $place_image)) {
                // Prepare an insert statement
                $sql = "INSERT INTO place (name, image, description, veg, nonveg, schedule_1, schedule_2, schedule_3, bus, train) 
                        VALUES ('$place_name', '$place_image', '$place_description', '$food_veg_price', '$food_nonveg_price', '$schedule_day1', '$schedule_day2', '$schedule_day3', '$car_price', '$train_price')";

                if (mysqli_query($conn, $sql)) {
                    echo "<p style='color: #4caf50; text-align: center;'>New place added successfully!</p>";
                } else {
                    echo "<p style='color: #f44336; text-align: center;'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
                }
        //     } else {
        //         echo "<p style='color: #f44336; text-align: center;'>Sorry, there was an error uploading your file.</p>";
        //     }
        // } else {
        //     echo "<p style='color: #f44336; text-align: center;'>File is not an image.</p>";
        // }

        // Close connection
        mysqli_close($conn);
    }
} else {
    // Session ID is not set, show alert and redirect to login page
    echo "<script>alert('Please login first'); window.location.href='admin_login.php';</script>";
    exit();
}
?>
