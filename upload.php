<?php
// Directory to be created
$target_dir = "uploads/";

// Check if the directory exists
if (!is_dir($target_dir)) {
    // Attempt to create the directory
    if (mkdir($target_dir, 0755, true)) {
        echo "Directory created successfully.";
    } else {
        echo "Failed to create directory.";
    }
} else {
    echo "Directory already exists.";
}

// Proceed with file upload code
$target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

// The rest of your upload code here
?>
