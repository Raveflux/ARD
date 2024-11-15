<?php
include_once "db_connection.php";
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards');
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

// Pagination variables
$rows_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$start = ($page - 1) * $rows_per_page;

// SQL query to fetch students based on the selected period
$sql = ""; // Initialize the SQL variable

if ($period == 'week') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEARWEEK(register_date, 1) = YEARWEEK(CURDATE(), 1) LIMIT ?, ?";
} elseif ($period == 'month') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEAR(register_date) = YEAR(CURDATE()) AND MONTH(register_date) = MONTH(CURDATE()) LIMIT ?, ?";
} elseif ($period == 'year') {
    $sql = "SELECT id, name, school_id_number, points FROM students WHERE YEAR(register_date) = YEAR(CURDATE()) LIMIT ?, ?";
} else {
    // Default fallback if an invalid period is passed
    $sql = "SELECT id, name, school_id_number, points FROM students LIMIT ?, ?";
}

if ($sql != "") {
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $start, $rows_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Count total rows to calculate pagination
$count_sql = "SELECT COUNT(id) AS total FROM students WHERE ";
if ($period == 'week') {
    $count_sql .= "YEARWEEK(register_date, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($period == 'month') {
    $count_sql .= "YEAR(register_date) = YEAR(CURDATE()) AND MONTH(register_date) = MONTH(CURDATE())";
} elseif ($period == 'year') {
    $count_sql .= "YEAR(register_date) = YEAR(CURDATE())";
} else {
    // Default fallback if an invalid period is passed
    $count_sql .= "1"; // This will return the count of all students
}

$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $rows_per_page);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header"> 
    <button onclick="location.href='logout.php'" class="logout-button">Log Out</button>
</div>
<div class="sidebar">
    <h2>Manage Students</h2>
    <ul>
        <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
        <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li>
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
</div>
<h3 class="report-title">Students Registered in <?php echo ucfirst($period); ?></h3>

<button onclick="location.href='reports.php'" class="logout-button">Back to Reports</button>

<div class="main-content">
    <table border="1">
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

    <!-- Pagination Links (Horizontal) -->
    <div style="text-align: center; margin-top: 20px;">
        <?php if ($page > 1): ?>
            <a href="?period=<?php echo $period; ?>&page=<?php echo $page - 1; ?>" class="pagination-button">Previous</a>
        <?php endif; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?period=<?php echo $period; ?>&page=<?php echo $page + 1; ?>" class="pagination-button">Next</a>
        <?php endif; ?>
    </div>

</div>

<!-- Centered Print Button -->
<div style="text-align: center; margin-top: 20px;">
    <a href="print.php?period=<?php echo $period; ?>" class="print-button">
        <button>Print All</button>
    </a>
</div>

</body>
</html>
