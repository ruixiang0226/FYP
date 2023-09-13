<?php
session_start();

include 'config.php';

$user_id = $_POST['user_id'] ?? null;
$vendor_id = $_POST['vendor_id'] ?? null;
$vendorpage_id = $_POST['vendorpage_id'] ?? null;

if (($user_id || $vendor_id) && $vendorpage_id) {

    function updateViews($mysqli, $table, $id, $vendorpage_id) {
        $sql = "SELECT views_history FROM $table WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $existing_views = []; 
            $row = $result->fetch_assoc();
            $existing_views = json_decode($row['views_history'], true) ?: [];
            
            $existing_views[$vendorpage_id] = time();

            $new_views = json_encode($existing_views);
            $sql = "UPDATE $table SET views_history = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $new_views, $id);
            $stmt->execute();
        } else {
            $new_views = json_encode([$vendorpage_id => time()]);
            $sql = "INSERT INTO $table (id, views_history) VALUES (?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("is", $id, $new_views);
            $stmt->execute();
        }
    }

    if ($user_id) {
        updateViews($mysqli, "user", $user_id, $vendorpage_id);
    }

    if ($vendor_id) {
        updateViews($mysqli, "vendor", $vendor_id, $vendorpage_id);
    }
} else {
    echo "Either vendor_id, vendorpage_id, or user_id is missing.";
}

$mysqli->close();
?>