<?php
session_start();
include '/config.php';

$user_id = $_GET['user_id'];

$sql = "SELECT username FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    error_log("Fetched username: " . $row['username']);
    echo json_encode(['status' => 'success', 'username' => $row['username']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}

$stmt->close();
$conn->close();
?>