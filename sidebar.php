<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <h3><?php echo isset($_SESSION['admin']) && $_SESSION['admin'] === true ? 'Admin Dashboard' : 'Student Dashboard'; ?></h3>
    
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
        <div class="sidebar">
        <h2>Reports</h2>
        <ul>
      
        <li><a href="admin.php"><i class="fas fa-users"></i> Manage Students</a></li>
        <li><a href="admindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_posts.php"><i class="fas fa-edit"></i> Manage Posts</a></li> <!-- New link -->
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="admin_voucher.php"><i class="fas fa-file-alt"></i> Voucher</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>
    <?php else: ?>
    <!-- Student Links -->
    <a href="student.php"><i class="fas fa-home"></i> Home</a>
    <a href="javascript:void(0)" onclick="openRedeemModal()"><i class="fas fa-gift"></i> Redeem Code</a>
    <a href="ranking.php"><i class="fas fa-trophy"></i> TOP 3</a>
    <a href="rewards.php"><i class="fas fa-crown"></i> Overall Leaderboard</a>
<?php endif; ?>
    
    <a href="logout.php">Logout</a>
</div>


<!-- Modal for Redeem Code -->
<div id="redeemModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRedeemModal()">&times;</span>
        <h2>Redeem Your Code</h2>
        <form action="redeem_code.php" method="POST">
    <label for="code">Enter Reward Code:</label>
    <input type="text" id="code" name="code" required>
    <button type="submit">Redeem</button>
</form>
        <?php if (!empty($code_error)): ?>
            <p class="<?php echo htmlspecialchars($code_class); ?>"><?php echo htmlspecialchars($code_error); ?></p>
        <?php endif; ?>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script>
    // Open modal function
    function openRedeemModal() {
        document.getElementById('redeemModal').style.display = 'block';
    }

    // Close modal function
    function closeRedeemModal() {
        document.getElementById('redeemModal').style.display = 'none';
    }

    // Close modal if clicked outside
    window.onclick = function(event) {
        const modal = document.getElementById('redeemModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>

<style>
    /* Modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        text-align: center;
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
        max-width: 500px; /* Maximum width */
        border-radius: 10px; /* Rounded corners */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
     /* Button styling */
     button {
        background-color: #96140a; /* Primary blue background */
        color: #fff; /* White text */
        padding: 10px 20px; /* Padding for larger click area */
        font-size: 16px; /* Larger font size */
        font-weight: bold;
        border: none; /* Remove default border */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition */
    }

    /* Button hover effect */
    button:hover {
        background-color: #c81010; /* Darker blue on hover */
        transform: scale(1.05); /* Slightly enlarges the button */
    }

    /* Button focus effect */
    button:focus {
        outline: 2px solid #c81010; /* Blue outline on focus */
    }

    /* Button active effect */
    button:active {
        background-color: #c81010; /* Even darker on click */
        transform: scale(0.98); /* Slightly reduces size when clicked */
    }

    /* Styling for the Redeem modal form input */
    #redeemModal input[type="text"] {
        width: 100%; /* Full width */
        padding: 10px; /* Padding for input */
        margin: 10px 0 20px; /* Margin around the input */
        border: 1px solid #ccc; /* Light gray border */
        border-radius: 4px; /* Rounded corners */
        font-size: 16px; /* Font size */
        box-sizing: border-box;
        text-align: center;
    }
    
</style>
