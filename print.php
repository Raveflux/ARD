<?php
include_once "db_connection.php";

session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$period = isset($_GET['period']) ? $_GET['period'] : 'week';

// SQL query to fetch students based on the selected period
if ($period == 'week') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEARWEEK(register_date, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($period == 'month') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEAR(register_date) = YEAR(CURDATE()) AND MONTH(register_date) = MONTH(CURDATE())";
} elseif ($period == 'year') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEAR(register_date) = YEAR(CURDATE())";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminstyle.css">
    <title>Print Students Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        /* Back Button Styling */
        .back-button {
            display: inline-block;
            background-color: #96140a;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .back-button:hover {
            background-color: #c81010;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="view_students.php" class="back-button">Back to View Students</a>

    <h2>Students Registered in <?php echo ucfirst($period); ?></h2>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>School ID</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['school_id_number']; ?></td>
                    <td><?php echo $row['points']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>

</body>
</html>
