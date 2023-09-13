<?php
session_start();

$mysqli = new mysqli('ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com', 'wdzd5d37qxl2zori', 'gnvgq0h5y6vmdhqr', 'p40t91itwyub22ct');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["feedback_submit"])) {
    $feedbackContent = $_POST["feedback"];
    $timestamp = date("Y-m-d H:i:s");
    
    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];
        $stmt = $mysqli->prepare("INSERT INTO feedback (user_id, feedback_content, feedback_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userID, $feedbackContent, $timestamp);
        $redirectPage = "/user/user_profile.php";
    } elseif (isset($_SESSION['vendor_id'])) {
        $vendorID = $_SESSION['vendor_id'];
        $stmt = $mysqli->prepare("INSERT INTO feedback (vendor_id, feedback_content, feedback_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $vendorID, $feedbackContent, $timestamp);
        $redirectPage = "/vendor_acc/vendor_profile.php";
    } 
    
    if ($stmt->execute()) {
        $_SESSION['feedback_message'] = "Feedback submitted successfully.";
        header("Location: " . $redirectPage);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $mysqli->close();
}
?>
