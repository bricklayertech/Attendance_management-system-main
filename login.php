<?php
require_once("connection.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows == 1 && password_verify($pass, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        if ($role == 'admin') {
            header("Location: admin_panel.php");
        } else {
            header("Location: student_panel.php");
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid credentials!";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #9E9E9E;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #cbcaca;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn1 {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn2 {
            padding: 10px;
            background-color: #0bdf67;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            width: 330px;
            margin-top: 10px; /* Added margin-top to create space between buttons */
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        span {
            display: block;
            margin-top: 15px; /* Added margin-top to create space between the span and the button above */
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Attendance Management System</h1>
        <h2>Login</h2>
        
        <?php
        // Display error messages from session
        if (isset($_SESSION['error_message'])) {
            echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button class="btn1" type="submit">Login</button>
        </form>
        <span>New Student? Register Now!</span>
        <a href="register.php" class="btn2">Register</a>
    </div>
</body>
</html>
