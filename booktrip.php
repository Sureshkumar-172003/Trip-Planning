<?php session_start(); include 'user_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Gallery</title>
    <style>
        body {
            margin-top: 200px; /* Adjust to ensure content starts after the header */
        }

        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1800px;
            padding: 50px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .gallery-item {
            width: 30%;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .gallery-item img {
            width: 100%;  /* Keeps the width of the image responsive */
            height: 200px; /* Fix the height to 200px */
            object-fit: cover; /* Ensures the image doesn't stretch or squish */
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-item p {
            color: #333;
            font-size: 18px;
            padding: 10px 0;
            margin: 0;
            background-color: #f1f1f1;
            position: relative;
        }

        .gallery-item a {
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
        }

        /* Tooltip styling */
        .tooltip {
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            white-space: nowrap;
            z-index: 1;
            max-width: 200px;
            text-align: left;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .gallery-item {
                width: 90%;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .gallery-item {
                width: 45%;
            }
        }
    </style>
    <script>
        // JavaScript to handle tooltip pop-up
        function showTooltip(e, description) {
            var tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.innerText = description;
            document.body.appendChild(tooltip);

            tooltip.style.left = e.pageX + 'px';
            tooltip.style.top = e.pageY + 'px';
            tooltip.style.display = 'block';

            e.target.onmouseleave = function() {
                tooltip.remove();  // Remove tooltip when mouse leaves
            };
        }
    </script>
</head>
<body>
    <div class="gallery-container">
        <?php
        $conn = mysqli_connect("localhost", "root", "", "suresh");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch place names, images, and descriptions from the database
        $place_query = "SELECT name, image, description FROM place";
        $result = mysqli_query($conn, $place_query);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $image_data = base64_encode($row['image']);
                $image_type = 'jpeg'; // or 'png' if your image is in PNG format
                $base64_image = 'data:image/' . $image_type . ';base64,' . $image_data;
        ?>
                <div class="gallery-item">
                    <!-- Image and Name -->
                    <a href="place_detail.php?id=<?php echo $row['name']; ?>">
                        <img src="<?php echo $base64_image; ?>" alt="Place Image">
                        <p onmouseover="showTooltip(event, '<?php echo $row['description']; ?>')">
                            <?php echo $row['name']; ?>
                        </p>
                    </a>
                </div>
        <?php }
        } else {
            echo "<h2>No places are available</h2>";
        }
        ?>
    </div>
</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
