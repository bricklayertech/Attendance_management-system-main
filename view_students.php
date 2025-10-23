<?php
require_once("connection.php");

function fetchStudents($conn) {
    $sql = "SELECT id, username FROM users WHERE role = 'student'";
    return $conn->query($sql);
}

function fetchAttendance($conn) {
    $sql = "SELECT a.id, u.username, a.date, a.status FROM attendance a JOIN users u ON a.user_id = u.id WHERE u.role = 'student'";
    return $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_attendance'])) {
        $user_id = $_POST['user_id'];
        $date = $_POST['date'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $date, $status);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit_attendance'])) {
        $attendance_id = $_POST['attendance_id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $attendance_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_attendance'])) {
        $attendance_id = $_POST['attendance_id'];

        $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
        $stmt->bind_param("i", $attendance_id);
        $stmt->execute();
        $stmt->close();
    }
}

$students = fetchStudents($conn);
$attendance = fetchAttendance($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students and Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #9E9E9E;
            margin: 0;
            padding: 20px;
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
        form {
            display: flex;
            flex-direction: column;
        }
        label, select, input[type="text"], input[type="date"] {
            margin-bottom: 10px;
            background-color:#E0E0E0;
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
    </style>
</head>
<body>
    <h1>Login Students</h1>
    <?php if ($students->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>
            <?php while($row = $students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>

    <h1>Attendance</h1>
    <?php if ($attendance->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $attendance->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="attendance_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="present" <?php if ($row['status'] == 'present') echo 'selected'; ?>>Present</option>
                                <option value="absent" <?php if ($row['status'] == 'absent') echo 'selected'; ?>>Absent</option>
                                <option value="leave" <?php if ($row['status'] == 'leave') echo 'selected'; ?>>Leave</option>
                            </select>
                            <button type="submit" name="edit_attendance">Edit</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="attendance_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_attendance">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No attendance records found.</p>
    <?php endif; ?>

    <h1>Add Attendance</h1>
    <form method="POST">
        <label for="user_id">Student:</label>
        <select name="user_id" id="user_id" required>
            <?php
            $students->data_seek(0); // Reset pointer to the start
            while($row = $students->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
            <?php endwhile; ?>
        </select>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="leave">Leave</option>
        </select>
        <button type="submit" name="add_attendance">Add Attendance</button>
    </form>
</body>
</html>
