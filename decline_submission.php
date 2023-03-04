<?php
// Get the ID number from the AJAX request
require_once 'config.php';

if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete the record with the given ID number
    $sql = "UPDATE sites SET declined=now() WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
}
?>
