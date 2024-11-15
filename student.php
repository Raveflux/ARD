<?php
include_once "db_connection.php";

session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student details
$sql = "SELECT name, school_id_number, points, profile_picture FROM students WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    $student = [
        'name' => 'Unknown',
        'school_id_number' => 'N/A',
        'points' => 0,
        'profile_picture' => 'default.jpg'
    ];
}

$code_error = '';
$code_class = '';
$code_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $entered_code = trim($_POST['code']);
    
    // Check if the code is valid and not redeemed
    $sql = "SELECT is_redeemed FROM reward_codes WHERE code=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $entered_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $code_info = $result->fetch_assoc();
        if ($code_info['is_redeemed'] == false) {
            // Update student's points
            $points = 1; // Each code is worth 1 point
            $sql = "UPDATE students SET points = points + ? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $points, $student_id);
            $stmt->execute();

            // Mark the code as redeemed
            $sql = "UPDATE reward_codes SET is_redeemed=TRUE WHERE code=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $entered_code);
            $stmt->execute();

            $code_error = 'Code redeemed successfully. Points added.';
            $code_class = 'success';
        } else {
            $code_error = 'This code has already been redeemed.';
            $code_class = 'error';
        }
    } else {
        $code_error = 'Invalid code. Please try again.';
        $code_class = 'error';
    }

    $stmt->close();
}

// Fetch posts from the database
$sql = "SELECT title, content, image, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

// Function to determine badges based on points
function getBadges($points) {
    $badges = [];

    if ($points >= 100) {
        $badges[] = "recgold.png"; // Add gold badge if points >= 100
    }
    if ($points >= 50) {
        $badges[] = "rec.png"; // Add silver badge if points >= 50
    }
    if ($points >= 20) {
        $badges[] = "badge_gold.png"; // Add bronze badge if points >= 20
    }
    if ($points >= 10) {
        $badges[] = "badge_silver.png"; // Add bronze badge if points >= 20
    }
    if ($points >= 5) {
        $badges[] = "badge_bronze.png"; // Add bronze badge if points >= 20
    }
    if ($points >= 1) {
        $badges[] = "badge_no.png"; // Add bronze badge if points >= 20
    }
    if (empty($badges)) {
     
    }

    return $badges;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
    <link rel="stylesheet" href="studentstyles.css">
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
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="header">
        
        

        <div class="profile-container">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>ID Number:</strong> <?php echo htmlspecialchars($student['school_id_number']); ?></p>
                <p><strong>Points:</strong> <?php echo htmlspecialchars($student['points']); ?></p>
                <h2 class="badge">Badge</h2>

                <div class="badge">
               

    <?php
    // Fetch the badges based on points
    $badges = getBadges($student['points']);
    $badge_message = '';
if (!empty($badges)) {
    $badge_message = 'Congratulations! You have earned the following badges: ' . implode(', ', $badges);}
    
    // Display each badge side by side
    foreach ($badges as $badge) {
        echo "<img src='" . htmlspecialchars($badge) . "' alt='Badge' class='badge-icon'>";
    }
    ?>
</div>

            </div>
        </div>

        <h2>Announcement</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                    <?php if ($post['image']): ?>
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <small>Posted on <?php echo htmlspecialchars($post['created_at']); ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>

    <div class="menu-icon" onclick="toggleSidebar()">&#9776;</div>
</body>
</html>

<?php
// Close the connection here, after all queries are completed
$conn->close();
?>
