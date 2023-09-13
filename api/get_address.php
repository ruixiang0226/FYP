<?php
session_start();
$conn = new mysqli("ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "wdzd5d37qxl2zori", "gnvgq0h5y6vmdhqr", "p40t91itwyub22ct");

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


