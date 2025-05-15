<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/../shared_includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'images/';
    $recordedDate = $_POST['recorded_date'];
    $carNumber = $_POST['car_number'];

    // Function to sanitize filenames
    function sanitizeFilename($filename) {
        return preg_replace('/[^a-zA-Z0-9\-\._]/', '', $filename);
    }

    try {
        // Process uploaded files
        $files = ['front_image', 'left_image', 'back_image', 'right_image', 'dashboard_image'];
        $uploadedFiles = [];

        foreach ($files as $file) {
            if (!isset($_FILES[$file])) {
                throw new Exception("File input missing: $file");
            }

            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Upload error (".$_FILES[$file]['error'].") for: $file");
            }

            $fileName = sanitizeFilename($_FILES[$file]['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $detectedType = mime_content_type($_FILES[$file]['tmp_name']);
            if (!in_array($detectedType, $allowedTypes)) {
                throw new Exception("Invalid file type for $file (only JPEG, PNG, WEBP allowed)");
            }

            // Move the uploaded file
            if (!move_uploaded_file($_FILES[$file]['tmp_name'], $targetFilePath)) {
                throw new Exception("Failed to move uploaded file: $fileName");
            }

            $uploadedFiles[$file] = $fileName;
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO car_photos 
                              (car_number, front_image, left_image, back_image, 
                               right_image, dashboard_image, recorded_date) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $carNumber,
            $uploadedFiles['front_image'],
            $uploadedFiles['left_image'],
            $uploadedFiles['back_image'],
            $uploadedFiles['right_image'],
            $uploadedFiles['dashboard_image'],
            $recordedDate
        ]);

        if (!$success) {
            throw new Exception("Database error: ".$stmt->errorInfo()[2]);
        }

        $_SESSION['upload_success'] = "Upload completed successfully!";
        header('Location: index.php');
        exit();

    } catch (Exception $e) {
        // Cleanup any partially uploaded files
        foreach ($uploadedFiles as $filePath) {
            if (file_exists($uploadDir.$filePath)) {
                unlink($uploadDir.$filePath);
            }
        }

        $_SESSION['upload_error'] = $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['upload_error'] = "Invalid request method.";
    header('Location: index.php');
    exit();
}
?>