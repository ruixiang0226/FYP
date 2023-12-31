<?php
session_start();

$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

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