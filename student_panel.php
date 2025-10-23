<?php
require_once("connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Panel</title>
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

        .panel-container {
            background-color: #cbcaca;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            display: inline-block;
            width: 94%;
            text-decoration: none;
            margin-top: 10px;
        }


        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h2>Welcome to Student Panel</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?= strpos($_SESSION['message'], 'Error') === false ? '' : 'error' ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="mark_attendance.php" method="POST">
            <button type="submit">Mark Attendance</button>
        </form>
        <form action="view_attendance.php" method="POST">
            <button type="submit">View Attendance</button>
        </form>
        <form action="send_leave_request.php" method="POST">
            <button type="submit">Mark Leave</button>
        </form>
       
        <form action="edit_profile.php" method="GET">
            <button type="submit">Edit Profile</button>
        </form>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
