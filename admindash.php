<?php
include_once "db_connection.php";



// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}



// Load the sendEmail function
require 'mail.php'; // Adjust the path to where sendEmail is defined

// Handle deletion of student
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

// Handle deletion of post
if (isset($_GET['delete_post'])) {
    $post_id_to_delete = intval($_GET['delete_post']);
    $delete_post_sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($delete_post_sql);
    $stmt->bind_param("i", $post_id_to_delete);
    
    if ($stmt->execute()) {
        echo "<p>Post deleted successfully.</p>";
    } else {
        echo "<p>Error deleting post: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Count the total number of students
$count_sql = "SELECT COUNT(*) AS total FROM students";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_students = $count_result->fetch_assoc()['total'];
$count_stmt->close();

// Pagination and search logic for students
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

$sql = "SELECT id, name, school_id_number, points, registration_date FROM students";
if (!empty($search_query)) {
    $sql .= " WHERE name LIKE ?";
}
$sql .= " ORDER BY registration_date DESC LIMIT ? OFFSET ?";
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

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $sql = "INSERT INTO posts (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $image);
    if ($stmt->execute()) {
        $_SESSION['post_created'] = true;

        // Retrieve all student email addresses and send the email
        $email_query = "SELECT email FROM students";
        $email_result = $conn->query($email_query);

        if ($email_result->num_rows > 0) {
            $subject = "New Post Created!";
            $message = "<p>Dear Student,</p><p>A new post titled <strong>{$title}</strong> has been created by the admin. Please check the updates.</p>";

            // Send notification email to each student
            $email_sent = true; // Flag to track if all emails are sent successfully
            $error_messages = []; // To collect any errors

            while ($row = $email_result->fetch_assoc()) {
                $student_email = $row['email'];
                // Validate the email address before sending
                if (filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
                    if (!sendEmail($student_email, $subject, $message)) {
                        $email_sent = false;
                        $error_messages[] = "Failed to send email to: " . $student_email;
                    }
                } else {
                    $email_sent = false;
                    $error_messages[] = "Invalid email address: " . $student_email;
                }
            }

            // Store result in session to display on the dashboard
            if ($email_sent) {
                $_SESSION['email_status'] = 'All emails sent successfully!';
            } else {
                $_SESSION['email_status'] = 'Some emails failed to send. Please check the logs.';
                $_SESSION['email_errors'] = $error_messages;
            }
        }
    } else {
        echo "<p>Error creating post: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Retrieve posts
$posts_sql = "SELECT id, title, content, image FROM posts";
$posts_result = $conn->query($posts_sql);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom styling */
    </style>

    <script>
        function showPopupMessage(message) {
            const popup = document.getElementById('popup-message');
            popup.textContent = message;
            popup.style.display = 'block';
            
            // Hide the message after 2 seconds
            setTimeout(() => {
                popup.style.display = 'none';
            }, 2000);
        }

        // Display the popup if session variable is set
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (isset($_SESSION['post_created'])): ?>
                showPopupMessage("Post created successfully!");
                <?php unset($_SESSION['post_created']); ?>
            <?php endif; ?>
        });
        
    </script>
</head>
<body>
<div class="header">
   
  </div>

<div id="popup-message" class="popup-message"></div>

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
    <h2>Total Students with Graph</h2>
    <div class="total-students-box">
        <canvas id="studentChart" width="400" height="200"></canvas>
    </div>

    <div>
        <h2>Create a Post</h2>
    </div>

    <!-- Create Post Form -->
    <form method="POST" action="admindash.php" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Content:</label>
        <textarea name="content" required></textarea><br>

        <label>Image:</label>
        <input type="file" name="image"><br>

        <button type="submit" name="submit_post">Post</button>
    </form>

    <!-- Recent Registrations -->
    <div class="recent-registrations">
        <h2>Recent Registrations</h2>
        <table class="student-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>School ID</th>
                    <th>Points</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['school_id_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['points']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($row['registration_date'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <ul>
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const totalStudents = <?php echo $total_students; ?>;
        const target = 1000;
        const percentage = Math.min((totalStudents / target) * 100, 100);

        const ctx = document.getElementById('studentChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Current Students'],
                datasets: [{
                    label: 'Percentage of Target',
                    data: [percentage],
                    backgroundColor: '#96140a',
                    borderColor: '#c81010',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Percentage (%)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${totalStudents} students (${percentage.toFixed(1)}% of target)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
