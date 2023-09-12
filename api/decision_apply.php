<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "penang_local_food");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["decision_acc"])) {
    $decision = $_POST["decision_acc"];
    $vendor_id = $_POST["id"];
    
    if ($decision === "approve") {
        $sql = "UPDATE vendor SET status='approved' WHERE id = ?";
    } elseif ($decision === "reject") {
        $sql = "UPDATE vendor SET status='rejected' WHERE id = ?";
    } else {
        echo "Invalid decision value";
        exit();
    }
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("i", $vendor_id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
}
header("Location: /admin/dashboard.php");
$mysqli->close();3
?>