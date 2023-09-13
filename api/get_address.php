<?php
session_start();

$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

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


