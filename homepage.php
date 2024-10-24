<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Planning Homepage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .header {
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 20px;
            position: fixed;
            width: 100%;
            height:200px auto;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .nav-bar {
            display: flex;
            justify-content: center;
            padding: 10px;
            margin-top: 60px;
        }
        .nav-bar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        .nav-bar a:hover {
            text-decoration: underline;
        }
        main{
           width:100%;
           height:auto;
           top:200px;
          background-color:pink;
    }
        .carousel {
             width:100%;
            top:100px;
            margin: 100px auto 50px;
            width: 80%;
        }
        .carousel img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .content {
            text-align: center;
            color: #fff;
            padding: 100px 20px;
            margin-top: 50px;
        }
        .content h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .content p {
            font-size: 20px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .content ul {
            list-style-type: none;
            padding: 0;
        }
        .content ul li {
            font-size: 18px;
            margin-bottom: 15px;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 10px;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }
        .button-container a {
            display: inline-block;
            padding: 15px 25px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
                gap: 10px;
            }
            .content {
                padding: 50px 20px;
            }
        }
    </style>
</head>
<body>
    <?php session_start();include 'user_header.php'; ?>
   <main>
    <!-- Carousel -->
    <div id="tripCarousel" class="carousel slide" data-ride="carousel">
        <!-- Carousel Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#tripCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#tripCarousel" data-slide-to="1"></li>
            <li data-target="#tripCarousel" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="image3.jpg" class="d-block w-100" alt="Discover Beautiful Places">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Explore Beautiful Destinations</h5>
                    <p>Plan your trip to amazing destinations with ease.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="image1.webp" class="d-block w-100" alt="Adventure Awaits">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Adventure Awaits</h5>
                    <p>Find the best places for adventure and fun.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="image2.jpg" class="d-block w-100" alt="Create Your Journey">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Create Your Perfect Journey</h5>
                    <p>Choose your destinations, hotels, and schedule with a few clicks.</p>
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <a class="carousel-control-prev" href="#tripCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#tripCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="content">
        <h2>Plan Your Perfect Trip</h2>
        <p>Explore a variety of destinations and travel options to make your next trip memorable. Whether you're looking for adventure, relaxation, or cultural experiences, we've got you covered.</p>
        
        <ul>
            <li><strong>Popular Destinations:</strong> Discover top travel spots around the world, including stunning beaches, vibrant cities, and scenic countryside.</li>
            <li><strong>Travel Tips:</strong> Get insider advice on packing, budgeting, and staying safe while traveling. Make your journey smooth and hassle-free.</li>
            <li><strong>Customizable Itineraries:</strong> Tailor your travel plans to suit your preferences. Choose from various activities, accommodations, and dining options.</li>
            <li><strong>Exclusive Deals:</strong> Take advantage of special offers on flights, hotels, and vacation packages. Save more while enjoying the best experiences.</li>
            <li><strong>Travel Guides:</strong> Access comprehensive guides to help you navigate new destinations. Learn about local culture, customs, and must-see attractions.</li>
        </ul>
        
            </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</main>
</body>
</html>

