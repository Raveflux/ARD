<?php
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

// Get user ID from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch user details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "<p>No student found.</p>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="view_prof.css">
</head>
<body>
    <div class="header">
        <h2>View Profile</h2>
        <button onclick="location.href='admin.php'" class="back-button">Back to Admin Page</button>
    </div>
    
    <div class="profile-container">
        <h3>Student Details</h3>
        <!-- Display Profile Picture -->
        <?php if (!empty($student['profile_picture']) && file_exists($student['profile_picture'])) : ?>
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
            </div>
        <?php else : ?>
            <p>No profile picture available.</p>
        <?php endif; ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p> <!-- Added email here -->
<p><strong>Birthdate:</strong> <?php echo htmlspecialchars($student['birthdate']); ?></p>
<p><strong>School ID Number:</strong> <?php echo htmlspecialchars($student['school_id_number']); ?></p>
<p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
<p><strong>Points:</strong> <?php echo htmlspecialchars($student['points']); ?></p>

    </div>
</body>
</html>
