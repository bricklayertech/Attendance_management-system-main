<?php
require_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username
    if (empty($_POST['username'])) {
        $_SESSION['error_message'] = "Username is required!";
        header("Location: register.php");
        exit;
    }

    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
    $stmt->bind_param("ss", $user, $pass);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful!";
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: register.php"); // Redirect to avoid resubmission
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:#9E9E9E;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
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

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-2{
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 350px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 10px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Attendance Management System</h1>
        <h2>Register</h2>
        <?php
       
        if (isset($_SESSION['success_message'])) {
            echo '<div class="message success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <a href="login.php"><button class="btn-2">Login</button></a>

    </div>
</body>
</html>
