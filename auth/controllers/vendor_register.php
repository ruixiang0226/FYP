<?php
session_start();

$mysqli = new mysqli('ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com', 'wdzd5d37qxl2zori', 'gnvgq0h5y6vmdhqr', 'p40t91itwyub22ct');

if ($mysqli->connect_error) {
    die("Failed to connect to the database: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register_vendor_submit"])) {
        $username = $_POST["vendor_username"];
        $phone = $_POST["vendor_phone"];
        $email = $_POST["vendor_email"];
        $password = $_POST["vendor_password"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $vendorName = $_POST["vendor_name"];
        $location = $_POST["location"];
        
        $status = 'pending';

        $sql = "INSERT INTO vendor (username, phone, email, password, vendor_name, location, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $stmt->bind_param("sssssss", $username, $phone, $email, $hashedPassword, $vendorName, $location, $status);

        if ($stmt->execute()) {
            $_SESSION['vendor_id'] = $mysqli->insert_id;
            $_SESSION['vendor_username'] = $username;
            header("Location: /vendor/waiting_approval.php");
        } else {
            echo "Registration failed: " . $stmt->error;
        }
    }
}


$mysqli->close();
?>