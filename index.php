<?php

include_once "db_connection.php";
$conn = new mysqli('localhost', 'root', '', 'student_rewards'); // Replace 'mysql' with the service name if you're in Docker

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Student Rewards System</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Header -->
<!-- Header -->
<div class="header">
    <img src="scc.jpg" alt="Header Logo" class="header-logo"> <!-- Header Logo -->
    <span class="header-title">St.Cecilia's College Cebu, Inc.</span>
</div>

<!-- Main Container -->
<div class="landing-container">
    <div class="logo-container">
        <img src="wifi.jpg" alt="App Logo" class="container-logo"> <!-- Main Container Logo -->
        <h2>Welcome</h2>
    </div>
    <p class="welcome-message">Rewarding students for sustainable actions on campus</p>
    <div class="button-container">
        <a href="login.php" class="button">Sign In</a>
        <a href="register.php" class="button sign-up">Sign Up</a>
    </div>
</div>


</body>
</html>
