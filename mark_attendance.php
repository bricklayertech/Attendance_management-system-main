<?php
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];
$date = date("Y-m-d");

// Check if attendance is already marked for today
$stmt = $conn->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['message'] = "Attendance already marked for today!";
    $stmt->close();
    $conn->close();
    header("Location: student_panel.php");
    exit();
}

$stmt->close();

// Mark attendance
$stmt = $conn->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?, ?, 'present')");
$stmt->bind_param("is", $user_id, $date);

if ($stmt->execute()) {
    $_SESSION['message'] = "Attendance marked successfully!";
} else {
    $_SESSION['message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: student_panel.php");
exit();
?>
