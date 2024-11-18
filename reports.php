<?php
include_once "db_connection.php";


// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
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

// Get the start and end dates for the week, month, and year
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');
$year_start = date('Y-01-01');
$year_end = date('Y-12-31');

// SQL to get the registrations for this week, month, and year
$week_sql = "SELECT COUNT(*) AS this_week FROM students WHERE register_date BETWEEN ? AND ?";
$month_sql = "SELECT COUNT(*) AS this_month FROM students WHERE register_date BETWEEN ? AND ?";
$year_sql = "SELECT COUNT(*) AS this_year FROM students WHERE register_date BETWEEN ? AND ?";

// Fetching the count for each period
$week_stmt = $conn->prepare($week_sql);
$week_stmt->bind_param("ss", $week_start, $week_end);
$week_stmt->execute();
$week_result = $week_stmt->get_result()->fetch_assoc();

$month_stmt = $conn->prepare($month_sql);
$month_stmt->bind_param("ss", $month_start, $month_end);
$month_stmt->execute();
$month_result = $month_stmt->get_result()->fetch_assoc();

$year_stmt = $conn->prepare($year_sql);
$year_stmt->bind_param("ss", $year_start, $year_end);
$year_stmt->execute();
$year_result = $year_stmt->get_result()->fetch_assoc();

// SQL to fetch students data
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
    <title>Reports</title>
    <link rel="stylesheet" href="adminstyle.css">
    <script>
        // Reset search field and submit form
        function resetSearch() {
            document.getElementsByName('search')[0].value = '';
            document.getElementById('searchForm').submit();
        }

        // Print functionality
        function printReport(period) {
            var printContents = document.getElementById(period).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>

<div class="header">
  

</div>
<?php include('sidebar.php'); ?>
<!-- Main Content -->
<div class="main-content">
    <h2>Reports</h2>

    <!-- Registration counts for the week, month, and year -->
    <div id="registration-stats">
        <table border="1">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Registrations</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Week of <?php echo $week_start . " to " . $week_end; ?></td>
                    <td><?php echo $week_result['this_week']; ?></td>
                    <td><a href="view_students.php?period=week">View</a></td>
                </tr>
                <tr>
                    <td>Month of <?php echo date('F Y'); ?></td>
                    <td><?php echo $month_result['this_month']; ?></td>
                    <td><a href="view_students.php?period=month">View</a></td>
                </tr>
                <tr>
                    <td>Year <?php echo date('Y'); ?></td>
                    <td><?php echo $year_result['this_year']; ?></td>
                    <td><a href="view_students.php?period=year">View</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
