<?php
session_start();

$mysqli = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login_submit"])) {
    $email = $_POST["login_email"];
    $password = $_POST["login_password"];

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result_user = $mysqli->query($sql);
    
    $sql = "SELECT * FROM vendor WHERE email='$email'";
    $result_vendor = $mysqli->query($sql);

    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result_admin = $mysqli->query($sql);

    if ($result_user->num_rows == 1) {
        $row = $result_user->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            session_unset();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            setcookie("user_type", "user", 0, "/");
            setcookie("user_id", $row["id"], 0, "/");

            header("Location: /user/user_account.php");
            exit();
        } else {
            header("Location: /auth/view/login.html?error=Invalid password.");
            exit();
        }
    } elseif ($result_vendor->num_rows == 1) {
        $row = $result_vendor->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            if ($row["status"] === "approved") {
                session_unset();
                $_SESSION["vendor_id"] = $row["id"];
                $_SESSION["vendor_username"] = $row["username"];

                setcookie("user_type", "vendor", 0, "/");
                setcookie("vendor_id", $row["id"], 0, "/");

                header("Location: /vendor_acc/vendor_account.php");
                exit();
            } else {
                header("Location: /vendor_acc/waiting_approval.php");
                exit();
            }
        } else {
            header("Location: /auth/view/login.html?error=Invalid password.");
            exit();
        }
    } elseif ($result_admin->num_rows == 1) {
        $row = $result_admin->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            session_unset();
            $_SESSION["admin_id"] = $row["id"];
            $_SESSION["admin_username"] = $row["username"];

            setcookie("user_type", "admin", 0, "/");
            setcookie("admin_id", $row["id"], 0, "/");
            
            header("Location: /admin/admin.php");
            exit();
        } else {
            header("Location: /auth/view/login.html?error=Invalid password.");
            exit();
        }
    } else {
        header("Location: /auth/view/login.html?error=Email not found");
        exit();
    }
}

$mysqli->close();
?>