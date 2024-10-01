<?php 
session_start();
if(isset($_SESSION['id'])){
	include'admin_dashboard.php';
	?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Guide Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background color */
            color: #e0e0e0; /* Light text color for contrast */
            margin: 0;
            padding: 0;
        }
        form {
            background-color: #222; /* Dark background for the form */
            padding: 20px;
			margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            color: #fff;
        }
        h2 {
            text-align: center;
            color: #fff;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #ddd;
        }
        input[type="text"], input[type="file"], textarea, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #666;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Add Guide Details</h2>
        <div class="form-group">
            <label for="place_id">Select Place:</label>
            <select id="place_id" name="names" required>
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

        <label for="guide_name">Guide Name:</label>
        <input type="text" id="guide_name" name="guide" required>

        <label for="guide_image">Guide Image:</label>
        <input type="file" id="guide_image" name="image" required>

        <label for="guide_phone_no">Guide Phone Number:</label>
        <input type="text" id="guide_phone_no" name="phone_no" required>

        <label for="guide_details">Guide Details:</label>
        <textarea id="guide_details" name="details" rows="4" cols="50" required></textarea>

        <input type="submit" name="sub" value="Add Guide">
    </form>
</body>
</html>

<?php
// Database connection
if(isset($_POST['sub'])){

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $place_name = $_POST['names'];
    $guide_name = $_POST['guide'];
    $guide_phone_no = $_POST['phone_no'];
    $guide_details = $_POST['details'];

    // Handle file upload for guide_image
    $guide_image = $_FILES['image']['tmp_name'];
    $guide_image_blob = addslashes(file_get_contents($guide_image));

    // Insert data into the database
    $sql = "INSERT INTO guide (place_name, guide_name, guide_image, guide_phone_no, guide_details) 
            VALUES ('$place_name', '$guide_name', '$guide_image_blob', '$guide_phone_no', '$guide_details')";

    if ($conn->query($sql) === TRUE) {
        echo "New guide details added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
} else {
    ?>
    <script>alert('please login first')</script>
    <?php 
    header('location:admin_login.php');
}
?>
