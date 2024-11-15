<?php
session_start(); // Start the session
 // Replace 'mysql' with the service name if you're in Docker

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the submitted code and student ID
$code_input = $_POST['code'];

// Check if the student ID is set in the session
if (!isset($_SESSION['student_id'])) {
    $message = '⚠️ Error: You must be logged in to redeem a code.'; // Error message
    $type = 'error';
} else {
    $student_id = $_SESSION['student_id']; // Assuming student is logged in and ID is stored in session

    // Check if the code exists and is not redeemed
    $sql_check_code = "SELECT * FROM reward_codes WHERE code = ? AND is_redeemed = 0";
    $stmt = $conn->prepare($sql_check_code);
    $stmt->bind_param("s", $code_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Code exists and is valid, proceed with redemption
        $sql_redeem = "UPDATE reward_codes SET is_redeemed = 1 WHERE code = ?";
        $stmt_redeem = $conn->prepare($sql_redeem);
        $stmt_redeem->bind_param("s", $code_input);
        $stmt_redeem->execute();
        
        // Increment the student's points
        $sql_update_points = "UPDATE students SET points = points + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_points);
        $stmt_update->bind_param("i", $student_id);
        $stmt_update->execute();

        // Success message
        $message = '✅ Code redeemed successfully!'; 
        $type = 'success';
    } else {
        // Code is invalid or already redeemed
        $message = '❌ Oops! Invalid or already redeemed code. Try again!';
        $type = 'error';
    }
}

$conn->close();
?>

<!-- Modal HTML -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="modal-message" class="<?php echo $type; ?>"><?php echo $message; ?></p>
    </div>
</div>

<!-- Add the following JavaScript and CSS to your page -->
<style>
    .modal {
        display: flex; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.7); /* Darker background */
        justify-content: center; /* Center content */
        align-items: center; /* Center content vertically */
    }

    .modal-content {
        background-color: #fff;
        padding: 40px; /* Increased padding */
        border-radius: 10px; /* Rounded corners */
        text-align: center; /* Center text */
        width: 90%; /* Adjust width for responsiveness */
        max-width: 600px; /* Maximum width */
    }

    .modal-content p {
        margin: 0; /* Remove default margin */
        padding: 10px 0; /* Add some padding */
        font-size: 24px; /* Increase font size */
        color: #dc3545; /* Red color for error message */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: black;
        text-decoration: none;
    }
</style>

<script>
    // Function to close the modal and redirect
    const closeModal = () => {
        const modal = document.getElementById("modal");
        modal.style.display = "none";
        window.location.href = 'student.php'; // Redirect to student.php
    };

    // Function to show the modal and automatically close it after a certain duration
    const showModal = () => {
        const modal = document.getElementById("modal");
        modal.style.display = "flex"; // Show modal
        setTimeout(closeModal, 3000); // Close and redirect after 2000 milliseconds (2 seconds)
    };

    // Call the showModal function after the page loads
    window.onload = showModal;
</script>
