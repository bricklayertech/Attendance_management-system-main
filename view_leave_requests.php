<?php
require_once("connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leave_id']) && isset($_POST['status'])) {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT lr.id, u.username, lr.leave_date, lr.status FROM leave_requests lr JOIN users u ON lr.user_id = u.id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Requests</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #9E9E9E;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .leave-container {
            background-color:#cbcaca;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
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
            ;
        }
        
        form {
            display: inline;
        }
       
        
        select, button {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color:#E0E0E0;
        }
        
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="leave-container">
        <h2>Leave Requests</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Leave Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['leave_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="approved" <?php if ($row['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                                    <option value="denied" <?php if ($row['status'] == 'denied') echo 'selected'; ?>>Denied</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No leave requests found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
