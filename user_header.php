
<html>
<head>
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

<body> 

<div class="header">
        <h1>Welcome to the Trip Planning System</h1>
        <div class="nav-bar">
            <a href="homepage.php">Home</a>
            <a href="booktrip.php">Choose Plan</a>
           
            
            <?php
             if(isset($_SESSION['userid'])){
                ?>
                <a href="logout.php">Logout</a> 
            <?php }else{
                echo ' <a href="user_login.php">User Login</a>';
                 echo "<a href='admin_login.php'>Admin Login</a>";
            } ?> 
        </div>
    </div>
</body>
</html>