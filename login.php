<?php
session_start();
require 'db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;  
            header("Location: index.php"); 
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .login-form {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 380px;
            
        }
        .login-form h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        .login-form input {
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
        .login-form input:focus {
            background-color: #eaeaea;
            border-color: #0071e3;
            outline: none;
        }
        .login-form button {
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
        .login-form button:hover {
            background-color: #005bb5;
        }
        .login-form p {
            margin-top: 15px;
            color: #555;
            font-size: 14px;
        }
        .login-form a {
            color: #0071e3;
            text-decoration: none;
        }
        .login-form a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #ff3b30 !important;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <form action="" method="post">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>

            <?php if ($error != '') { ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php } ?>

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>
