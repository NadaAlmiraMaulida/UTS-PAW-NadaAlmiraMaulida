<?php
session_start();
require 'db.php';

$message = ''; // Initialize the message variable

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  

    $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        $message = "Username already exists. Please choose another username.";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $message = "Register successfully. Please <a href='login.php'>login</a>.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background-color: #f5f5f7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-form {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 380px;
        }
        .register-form h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        .register-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #d1d1d6;
            font-size: 16px;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }
        .register-form input:focus {
            background-color: #eaeaea;
            border-color: #0071e3;
            outline: none;
        }
        .register-form button {
            width: 100%;
            padding: 12px;
            background-color: #0071e3;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .register-form button:hover {
            background-color: #005bb5;
        }
        .register-form p {
            margin-top: 15px;
            color: #555;
            font-size: 14px;
        }
        .register-form .message {
            color: #ff3b30;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <form action="" method="post">
            <h1>Register</h1>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>

            <?php if ($message != '') { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } else { ?>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>
