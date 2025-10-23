<?php
require_once("connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Adjust the SQL query to include a filter for the role 'student' and status 'present'
    $sql = "SELECT u.username, a.date, a.status 
            FROM attendance a 
            JOIN users u ON a.user_id = u.id 
            WHERE a.date BETWEEN ? AND ? AND u.role = 'student' AND a.status = 'present'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $report_data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $report_data[] = $row;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
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

        .report-container {
            background-color: #cbcaca;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #E0E0E0;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h2>Attendance Report</h2>
        <?php if (!empty($report_data)): ?>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
