<?php
session_start();
include 'database.php'; // Include your database connection

// If you want to restrict access to logged-in users, uncomment the following lines
// if (!isset($_SESSION['userid'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
    background-color: #f0f4f8; /* Light background for contrast */
    margin: 0;
    padding: 20px;
    color: #333;
}

h1 {
    color: #2c3e50; /* Darker heading color */
    text-align: center;
    margin-bottom: 30px;
    font-size: 2.5em; /* Larger font size for headings */
    text-transform: uppercase; /* Uppercase text for emphasis */
}

p {
    font-size: 18px; /* Slightly larger font for better readability */
    line-height: 1.8; /* Increased line height for better spacing */
    margin: 10px 0;
    padding: 0 15px; /* Padding for text */
}

ul {
    list-style-type: none; /* Remove bullet points */
    padding: 0;
    margin: 20px 0; /* Margin around the list */
}

ul li {
    background-color: #fff; /* White background for list items */
    padding: 15px; /* Padding for better spacing */
    margin: 10px 0; /* Margin between items */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    transition: transform 0.2s; /* Animation on hover */
}

ul li:hover {
    transform: translateY(-2px); /* Lift effect on hover */
}

a {
    display: inline-block;
    margin-top: 30px; /* Margin above the button */
    text-decoration: none;
    color: #fff; /* White text for links */
    background-color: #3498db; /* Blue background for buttons */
    padding: 12px 20px; /* Padding for button size */
    border-radius: 5px; /* Rounded corners */
    text-align: center; /* Center text */
    transition: background-color 0.3s; /* Smooth background color change */
}

a:hover {
    background-color: #2980b9; /* Darker blue on hover */
}

/* Responsive Design */
@media (max-width: 600px) {
    h1 {
        font-size: 2em; /* Adjust heading size on smaller screens */
    }

    p, ul li {
        font-size: 16px; /* Adjust paragraph and list item size on smaller screens */
    }
}

</style>
<body>
    <h1>About Us</h1>
    <p>At the Automated Trip Planning System, we believe that traveling should be an enriching experience filled with unforgettable moments. Our platform is designed to simplify the process of planning your journeys, allowing you to focus on what truly matters: the adventure ahead.</p>
    
    <p>Whether you're a solo traveler seeking new experiences, a family on vacation, or a group of friends exploring the world together, our system caters to your unique travel needs. We offer:</p>
    
    <ul>
        <li><strong>Personalized Itineraries:</strong> Create a tailor-made travel plan that fits your interests, budget, and timeline.</li>
        <li><strong>Real-Time Weather Updates:</strong> Stay informed about the weather conditions at your destination to plan your activities accordingly.</li>
        <li><strong>Comprehensive Accommodation Options:</strong> Choose from a variety of hotels that suit your preferences and budget.</li>
        <li><strong>Expert Local Guides:</strong> Enhance your experience with knowledgeable guides who can provide insider tips and cultural insights.</li>
        <li><strong>Seamless User Experience:</strong> Enjoy a user-friendly interface that makes planning your trip a breeze.</li>
    </ul>
    
    <p>Our dedicated team is passionate about travel and committed to providing you with the tools you need for a memorable journey. Join us as we embark on this exciting adventure together, making every trip a remarkable story to tell.</p>
   
    
    <a href="homepage.php">Back to Home</a>
</body>
</html>
