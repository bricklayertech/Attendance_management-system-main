<?php
require_once("connection.php");

// Define the total number of days
$total_days = 5; // Example total number of days in the period

// Function to determine grade based on attendance percentage
function determine_grade($percentage) {
    if ($percentage >= 86.67) { // 26/30 = 86.67%
        return 'A';
    } elseif ($percentage >= 66.67) { // 20/30 = 66.67%
        return 'B';
    } elseif ($percentage >= 50) { // 15/30 = 50%
        return 'C';
    } elseif ($percentage >= 33.33) { // 10/30 = 33.33%
        return 'D';
    } else {
        return 'F'; // F for fail if less than 33.33% attendance
    }
}

// Fetch students' attendance and calculate grades
$sql = "SELECT u.id, u.username, COUNT(a.date) AS attended_days
        FROM users u
        JOIN attendance a ON u.id = a.user_id
        WHERE u.role = 'student' AND a.status = 'present'
        GROUP BY u.id, u.username";
$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $percentage = ($row['attended_days'] / $total_days) * 100;
        $row['grade'] = determine_grade($percentage);
        $row['attendance_percentage'] = round($percentage, 2);
        $students[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades</title>
    <link rel="stylesheet" href="styles.css">
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

        .grade-container {
            background-color: #cbcaca;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }

        h2 {
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
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #E0E0E0;
            color: black;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="number"] {
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

        button:hover {
            background-color: #0056b3;
        }

        .result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="grade-container">
        <h2>Student Grades</h2>
        <?php if (!empty($students)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Days Attended</th>
                        <th>Attendance Percentage</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['username']); ?></td>
                            <td><?php echo htmlspecialchars($student['attended_days']); ?></td>
                            <td><?php echo htmlspecialchars($student['attendance_percentage']) . '%'; ?></td>
                            <td><?php echo htmlspecialchars($student['grade']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
