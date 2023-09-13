<?php
session_start();

$mysqli = new mysqli('ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com', 'wdzd5d37qxl2zori', 'gnvgq0h5y6vmdhqr', 'p40t91itwyub22ct');

if ($mysqli->connect_error) {
    die("Failed to connect to the database: " . $mysqli->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
        
require 'FYP/PHPMailer/src/Exception.php';
require 'FYP/PHPMailer/src/PHPMailer.php';
require 'FYP/PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_user_submit"])) {
    $username = $_POST["register_username"]; 
    $email = $_POST["register_email"];
    $password = $_POST["register_password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate a 6-digit verification code
    $verification_code = rand(100000, 999999);

    // Get the current time
    $current_time = new DateTime();
    $current_time = date('Y-m-d H:i:s');

    $sql = "INSERT INTO email_verification_code (username, email, password,  verification_code, created_at) VALUES ('$username', '$email', '$hashedPassword', '$verification_code', '$current_time')";
    
    if ($mysqli->query($sql) === true) {
        $_SESSION['user_id'] = $mysqli->insert_id;
        $_SESSION['username'] = $username;

        $mail = new PHPMailer(true);
        
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'laowang2015@gmail.com';
            $mail->Password = 'ojsqvfupqjitrgpw';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('laowang2015@gmail.com', 'Mailer');
            $mail->addAddress($email, 'User');
            
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Your verification code is: " . $verification_code;
            
            $mail->send();
            
            echo 'Verification code has been sent. Please check your gmail!!';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Registration failed: " . $mysqli->error;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_code_submit"])) {
    $submitted_code = $_POST["submitted_code"];

    $sql = "SELECT * FROM email_verification_code WHERE verification_code = '$submitted_code'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $stored_code = $row['verification_code'];
        $code_time = new DateTime($row['created_at']);
        $current_time = new DateTime();
        $interval = $code_time->diff($current_time);

        if ($interval->i * 60 + $interval->s > 120) {
            echo "The code has expired.";
            $sql = "DELETE FROM email_verification_code WHERE email = '$email'";
            $mysqli->query($sql);
        } else {
            if ($submitted_code === $stored_code) {
                echo "Verification successful!";
                
                $sql = "INSERT INTO user (username, email, password) VALUES ('{$row['username']}', '{$row['email']}', '{$row['password']}')";
                if ($mysqli->query($sql) === true) {
                    header("Location: /auth/view/login.html?success=Registration successful");
                } else {
                    echo "Failed to move to user table: " . $mysqli->error;
                }
                $sql = "DELETE FROM email_verification_code WHERE email = '$email'";
                $mysqli->query($sql);
            } else {
                header("Location: /auth/view/login.html?error=Incorrect verification code");
            }
        }
    } else {
        header("Location: /auth/view/login.html?error=Email not found");
    }
}

$mysqli->close();
?>