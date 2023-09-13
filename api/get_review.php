<?php
$conn = new mysqli("ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "wdzd5d37qxl2zori", "gnvgq0h5y6vmdhqr", "p40t91itwyub22ct");

$vendorpage_id = $_GET["vendorpage_id"];

$sql = "SELECT reviews.*, user.username FROM reviews 
JOIN user ON reviews.user_id = user.id 
WHERE vendorpage_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendorpage_id);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

echo json_encode($reviews);

$stmt->close();
$conn->close();
?>