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
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if the form was submitted and the file exists
if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {

    // Check if the file is an actual image (optional, you can adjust this check)
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Limit the file size (e.g., 2MB)
    if ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
        echo "File is too large. Maximum file size is 2MB.";
        exit;
    }

    // Only allow certain file formats (e.g., JPG, PNG, GIF)
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        exit;
    }

    // Ensure the file doesn't already exist in the target directory
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        exit;
    }

    // Rename the file to avoid name conflicts
    $new_file_name = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars($new_file_name) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
}
?>
