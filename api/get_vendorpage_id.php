<?php
session_start();
$conn = new mysqli("ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "wdzd5d37qxl2zori", "gnvgq0h5y6vmdhqr", "p40t91itwyub22ct");

$vendor_name = $_GET['vendor_name'];

$sql = "SELECT id FROM vendorpages WHERE vendor_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vendor_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION["vendorpage_id"] = $row["id"]; 
    echo json_encode(['status' => 'success', 'vendorpage_id' => $row['id']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Vendor not found']);
}

$stmt->close();
$conn->close();
?>