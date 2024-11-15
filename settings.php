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

// Handle deletion
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    $delete_sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_to_delete);
    
    if ($stmt->execute()) {
        echo "<p>User deleted successfully.</p>";
    } else {
        echo "<p>Error deleting user: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Pagination and search logic
$results_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $results_per_page;

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$count_sql = "SELECT COUNT(*) AS total FROM students";
if (!empty($search_query)) {
    $count_sql .= " WHERE name LIKE ?";
}
$count_stmt = $conn->prepare($count_sql);
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $count_stmt->bind_param("s", $search_param);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $results_per_page);

$sql = "SELECT id, name, school_id_number, points FROM students";
if (!empty($search_query)) {
    $sql .= " WHERE name LIKE ?";
}
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if (!empty($search_query)) {
    $stmt->bind_param("sii", $search_param, $results_per_page, $offset);
} else {
    $stmt->bind_param("ii", $results_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

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
    <title>Settings</title>
    <link rel="stylesheet" href="adminstyles.css">
    <script>
        function resetSearch() {
            document.getElementsByName('search')[0].value = '';
            document.getElementById('searchForm').submit();
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>Admin Page - Settings</h2>
        <button onclick="location.href='logout.php'" class="logout-button">Log Out</button>
    </div>

    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manage_students.php">Manage Students</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="search-bar">
            <form method="get" action="settings.php" id="searchForm">
                <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
                <button type="button" onclick="resetSearch()">Reset</button>
            </form>
        </div>
        
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
                            <a href='settings.php?delete=" . htmlspecialchars($row["id"]) . "' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No results found.</td></tr>";
            }
            ?>
        </table>
        
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='settings.php?page=" . ($page - 1) . "'>Prev</a>";
            }
            if ($page < $total_pages) {
                echo "<a href='settings.php?page=" . ($page + 1) . "'>Next</a>";
            }
            ?>
        </div>
    </div>
</body>
</html>
