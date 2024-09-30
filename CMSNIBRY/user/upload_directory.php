<?php
// Path to the uploads directory
$upload_dir = __DIR__ . '/../uploads/';

// Check if the uploads directory exists
if (!is_dir($upload_dir)) {
    // Attempt to create the directory
    if (mkdir($upload_dir, 0777, true)) {
        echo "Uploads directory created successfully.";
    } else {
        echo "Failed to create uploads directory.";
    }
} 
?>
