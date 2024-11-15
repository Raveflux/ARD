<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Change credentials as needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check voucher code
if (isset($_POST['voucher_code'])) {
    $voucher_code = trim($_POST['voucher_code']);
    $voucher_sql = "SELECT * FROM vouchers WHERE voucher_code = ? AND status = 'active'";
    $stmt = $conn->prepare($voucher_sql);
    $stmt->bind_param("s", $voucher_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['access_granted'] = true;
        header("Location: wifi_access_granted.php");
        exit();
    } else {
        $error_message = "Invalid or inactive voucher code.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wi-Fi Voucher Portal</title>
    <link rel="stylesheet" href="portalstyle.css">
</head>
<body>

<h1>Wi-Fi Voucher Portal</h1>
<form action="wifi_portal.php" method="POST">
    <label for="voucher_code">Enter Voucher Code:</label>
    <input type="text" id="voucher_code" name="voucher_code" required>
    <button type="submit">Submit</button>
</form>

<?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

</body>
</html>
