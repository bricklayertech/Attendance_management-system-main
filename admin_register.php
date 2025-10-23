<?php
require_once("connection.php");
// Admin credentials
$adminUsername = 'admin';
$adminPassword = 'admin1234';

// Hash the password
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->bind_param("ss", $adminUsername, $hashedPassword);

// Execute the statement
if ($stmt->execute()) {
    echo "Admin user inserted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
</body>
</html>