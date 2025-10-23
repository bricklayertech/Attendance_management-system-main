<?php
require_once("connection.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_picture);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];

    // If a new profile picture is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $image = $_FILES['profile_picture']['tmp_name'];
        $imgContent = file_get_contents($image); // Read image content

        // Update user data with profile picture
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, profile_picture = ? WHERE id = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssbi", $new_username, $hashed_password, $imgContent, $user_id);
    } else {
        // Update user data without profile picture
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        // Update username in session
        $_SESSION['username'] = $new_username;
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: edit_profile.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #9E9E9E;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-container {
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
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="password"], input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color:whitesmoke;
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

        button:hover {
            background-color: #0056b3;
        }

        .profile-picture {
            display: block;
            margin: 20px auto;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
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
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Profile</h2>

        <?php
        // Display success or error messages from session
        if (isset($_SESSION['success_message'])) {
            echo '<div class="message success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture">

            <?php if (!empty($profile_picture)): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" class="profile-picture" alt="Profile Picture">
            <?php endif; ?>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
