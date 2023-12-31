<?php
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendorpage_id = $_POST["vendorpage_id"];
$user_id = $_POST["user_id"];
$rating = $_POST["rating"];
$comment = $_POST["comment"];
$datetime = date("Y-m-d H:i:s");

$sql = "SELECT * FROM reviews WHERE vendorpage_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vendorpage_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => "You've already reviewed this vendor."]);
    exit();
}

$sql = "INSERT INTO reviews (vendorpage_id, user_id, rating, comment, review_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiss", $vendorpage_id, $user_id, $rating, $comment, $datetime);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'New record created successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to insert new record']);
    
    die("SQL Error: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
