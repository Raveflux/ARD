<?php
include_once "db_connection.php";


$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'student_rewards');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    $delete_sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to avoid resubmission issues
    header("Location: admin.php");
    exit();
}

// Handle approval
if (isset($_GET['approve'])) {
    $id_to_approve = intval($_GET['approve']);
    $approve_sql = "UPDATE students SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($approve_sql);
    $stmt->bind_param("i", $id_to_approve);
    $stmt->execute();
    $stmt->close();
}

// Handle rejection
if (isset($_GET['reject'])) {
    $id_to_reject = intval($_GET['reject']);
    $reject_sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($reject_sql);
    $stmt->bind_param("i", $id_to_reject);
    $stmt->execute();
    $stmt->close();
}

// Pagination setup for approved students only
$rows_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$start = ($page - 1) * $rows_per_page;

// Search functionality
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = $search_query ? " WHERE name LIKE ? AND status = 'approved'" : " WHERE status = 'approved'";

// Count total rows for pagination (only approved students)
$count_sql = "SELECT COUNT(id) AS total FROM students" . $search_sql;
$count_stmt = $conn->prepare($count_sql);
if ($search_query) {
    $search_term = '%' . $search_query . '%';
    $count_stmt->bind_param("s", $search_term);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $rows_per_page);

// Fetch approved student data with optional search and order by points
$sql = "SELECT id, name, school_id_number, points, status FROM students" . $search_sql . " ORDER BY points DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
if ($search_query) {
    $stmt->bind_param("sii", $search_term, $start, $rows_per_page);
} else {
    $stmt->bind_param("ii", $start, $rows_per_page);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch pending registrations (no pagination)
$pending_sql = "SELECT id, name, school_id_number, points, status FROM students WHERE status = 'pending'";
$pending_result = $conn->query($pending_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Students</title>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
<div class="header"> 

</div>
<div class="sidebar">
    <h2>Manage Students</h2>
    <ul>
        <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
        <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li>
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="admin_voucher.php"><i class="fas fa-file-alt"></i> Voucher</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
</div>

<div class="main-content">
    <!-- Pending Registrations (no pagination) -->
    <h2>Pending Registrations</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>School ID Number</th>
            <th>Points</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $pending_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['school_id_number']); ?></td>
                <td><?php echo htmlspecialchars($row['points']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <a href="?approve=<?php echo $row['id']; ?>">Approve</a> |
                    <a href="?reject=<?php echo $row['id']; ?>" onclick="return confirm('Reject this user?');">Reject</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br><br><br><br><br>
    <h2>Manage Students</h2>
    <div class="search-bar">
        <form method="get" action="admin.php" id="searchForm">
            <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
            <button type="button" onclick="resetSearch()">clear</button>
        </form>
    </div>

    <!-- Approved Students Table -->
    <table>
        <tr>
            <th>Name</th>
            <th>School ID Number</th>
            <th>Points</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["school_id_number"]) . "</td>
                    <td>" . htmlspecialchars($row["points"]) . "</td>
                    <td>
                        <a href='view_profile.php?id=" . htmlspecialchars($row["id"]) . "' class='view-button'>View Profile</a> |
                        <a href='?delete=" . htmlspecialchars($row["id"]) . "' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No results found.</td></tr>";
        }
        ?>
    </table>

    <!-- Pagination Links (only for approved students) -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search_query); ?>">Prev</a>
        <?php endif; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search_query); ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<script>
    // JavaScript to toggle the sidebar
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    hamburger.addEventListener('click', function() {
        sidebar.classList.toggle('active'); // Toggle the sidebar's active class
        mainContent.classList.toggle('shifted'); // Shift content when sidebar is open
    });
});
</script>

</body>
</html>
