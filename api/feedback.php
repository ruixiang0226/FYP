<?php
session_start();

include 'config.php';

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
        $redirectPage = "/vendor/vendor_profile.php";
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
