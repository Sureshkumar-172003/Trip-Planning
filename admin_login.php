<?php  session_start(); ?>
<?php include 'user_header.php'; ?>
<?php
       if (isset($_POST['sub'])) {
        $n = $_POST['username'];
        $p = $_POST['password'];

        $conn = mysqli_connect("localhost", "root", "", "suresh");
        if (!$conn) {
            die("Connection Failed:" . mysqli_connect_error());
        }

        $s = "SELECT * FROM `admin` WHERE `username` = '$n' AND `password` = '$p';";
        $r = mysqli_query($conn, $s);

        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r)) {
                if ($row['username'] == $n && $row['password'] == $p) {
                    $_SESSION['id'] = $row['username'];
                    header("location:admin_dashboard.php");
                }
            }
        } else {
            echo '<p class="error-message">Login Failed! Please try again.</p>';
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
                       color: #ddd;
        }
        .login-container {
            background-color: #222;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #fff;
            font-size: 28px;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            color: #ddd;
            font-weight: bold;
            text-align: left;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 8px;
            font-size: 16px;
            color: #fff;
            background-color: #333;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #f44336;
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }
        .create-account,
        .forgot-password {
            margin-top: 20px;
            font-size: 14px;
            color: #ddd;
        }
        .create-account a,
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .create-account a:hover,
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post" action="" autocomplete="off">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" name="sub" value="Login">
        </form>
        <div class="forgot-password">
            <p><a href="forgot_password.html">Forgot Password?</a></p>
        </div>
           </div>

    
</body>
</html>
