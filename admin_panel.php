<?php
require_once("connection.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    $_SESSION['message'] = 'Access denied. Admins only.';
    header("Location: login.php");
    exit();
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
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

        .admin-container {
            background-color: #cbcaca;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            margin-top: 50px;
            display: inline-grid;
            justify-content: center;
        }

        .message {
            background-color: #ffdddd;
            color: #d8000c;
            border: 1px solid #d8000c;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="date"], button {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color:whitesmoke;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
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

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Welcome to Admin Panel</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="view_students.php" method="POST">
            <button type="submit">View Students</button>
        </form>
        <form action="view_leave_requests.php" method="POST">
            <button type="submit">View Leave Requests</button>
        </form>
        <form action="generate_report.php" method="POST">
            <label for="from_date">From:</label>
            <input type="date" id="from_date" name="from_date" required>
            <label for="to_date">To:</label>
            <input type="date" id="to_date" name="to_date" required>
            <button type="submit">Generate Report</button>
        </form>
        <a href="manage_grades.php" class="btn">Manage Grades</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
