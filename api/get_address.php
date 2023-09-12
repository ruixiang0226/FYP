<?php
session_start();
$conn = new mysqli("localhost", "root", "", "penang_local_food");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];

$sql = "SELECT id, address FROM vendorpages WHERE address LIKE ?";
$stmt = $conn->prepare($sql);
$searchQuery = "%" . $query . "%";
$stmt->bind_param("s", $searchQuery);
$stmt->execute();


$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>


