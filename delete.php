<?php
if (isset($_GET['image'])) {
    $dir = 'uploaded_images/';
    $imageToDelete = $_GET['image'];

    // Make sure the image filename is valid to prevent malicious requests
    if (preg_match('/^[a-zA-Z0-9._-]+$/', $imageToDelete)) {
        $filePath = $dir . $imageToDelete;
        
        if (file_exists($filePath)) {
            unlink($filePath);
            header('Location: index.php');
            exit;
        } else {
            echo 'Image not found';
        }
    } else {
        echo 'Invalid image filename';
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    exit;
}
?>
