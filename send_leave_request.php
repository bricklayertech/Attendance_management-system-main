<?php
require_once("connection.php");

$user_id = $_SESSION['user_id'];
$date = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO leave_requests (user_id, leave_date) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $date);

if ($stmt->execute()) {
    $_SESSION['message'] = "Leave request sent successfully!";
} else {
    $_SESSION['message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: student_panel.php");
exit();
?>
