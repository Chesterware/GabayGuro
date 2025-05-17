<?php
// Start output buffering
ob_start();

// Check if the request is a POST and contains a file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];

    // Allowed file types (image files only)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Check if the file type is allowed
    if (!in_array($file['type'], $allowedTypes)) {
        // If not, output an error message
        echo "<script>alert('Invalid file type. Only JPEG, PNG, and GIF files are allowed.'); window.location.href = 'admin_dashboard.php';</script>";
        ob_end_flush();
        exit;
    }

    // Check for errors during file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        // If there's an error, output an error message
        echo "<script>alert('Error uploading the file. Please try again.'); window.location.href = 'admin_dashboard.php';</script>";
        ob_end_flush();
        exit;
    }

    // Define the upload directory
    $uploadDir = 'uploads/profile_photos/';

    // Create the upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique file name
    $fileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $filePath = $uploadDir . $fileName;

    // Move the uploaded file to the desired location
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Redirect back to the profile page with success
        echo "<script>
            alert('Photo uploaded successfully!');
            window.location.href = 'admin_dashboard.php';
        </script>";
    } else {
        // If there was an issue saving the file, output an error message
        echo "<script>
            alert('Failed to save the uploaded file.');
            window.location.href = 'admin_dashboard.php';
        </script>";
    }
} else {
    // If no file is uploaded or invalid request
    echo "<script>
        alert('No file uploaded or invalid request.');
        window.location.href = 'admin_dashboard.php';
    </script>";
}

// End output buffering and flush the response
ob_end_flush();
?>
