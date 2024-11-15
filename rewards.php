<?php
include_once "db_connection.php";
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current page number from the URL, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Number of students to show per page
$offset = ($current_page - 1) * $limit; // Calculate the offset for SQL query

// Prepare and execute query for rankings with LIMIT and OFFSET
$sql = "SELECT students.id, name, points, profile_picture FROM students ORDER BY points DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Fetch rankings for the current page
$rankings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

// Get total number of students for pagination
$total_sql = "SELECT COUNT(*) as total FROM students";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_students = $total_row['total'];
$total_pages = ceil($total_students / $limit); // Calculate total pages

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards Page</title>
 
    <link rel="stylesheet" href="studentstyles.css"> <!-- Student-specific CSS -->
    <style>
       
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('sidebar-open');
            content.classList.toggle('content-open');
        }
    </script>
</head>
<body>
     <!-- Hamburger Icon for toggling the sidebar -->
     <div class="menu-icon" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h2>Ranking Leaderboard</h2>
        </div>

        <!-- Rewards Container -->
       

<div class="rewards-container">
    <h3>Top Students</h3>
    <ul>
        <?php foreach ($rankings as $index => $student) : ?>
            <li>
                <!-- Display Trophy Icon for Top 3 -->
              
                
                <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
                <div class="name"><?php echo htmlspecialchars($student['name']); ?></div>
                <div class="points"><?php echo htmlspecialchars($student['points']); ?> Points</div>
            </li>
        <?php endforeach; ?>
    </ul>

            <!-- Pagination -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>
