<?php
session_start();
$vendor_id = $_SESSION['vendor_id'];
$username = $_SESSION['vendor_username'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Waiting Room</title>
        <link rel="stylesheet" type="text/css" href="/web/assets/css/waiting_room.css">
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <section class="container">
            <header>
                <div class="logo_container">
                    <img src="/web/assets/img/logo.png" alt="Logo">
                    <span>Penang Local Food</span>
                </div>
                <div class="header_icons">
                    <div class="icon" id="header_acc">
                        <a class="icon_link" href="">
                            <i class='bx bx-user-circle'></i>
                            <span class="icon_label"><?php echo $_SESSION['vendor_username']; ?></span>
                        </a>
                    </div>
                    <div class="icon" id="header_logout">
                        <a class="icon_link" href="">
                            <i class='bx bx-log-out'></i>
                            <span class="icon_label">Logout</span>
                        </a>
                    </div>
                </div>
            </header>
            <section class="wrapper">
                <div class="waiting_container">
                    <div class="waiting_wrapper">
                        <i class="uil uil-process"></i>
                        <h1>Pending</h1>
                        <p>Thank you for the information you provided. Please wait for the administrator's review. Your vendor page will be created after the review is completed.</p>
                    </div>
                    <div class="button">
                        <div class="row">
                        <button type="button"><a href="/index.html">back to homepage</a></button>
                        <button type="button"><a href="#">view your vendorpage</a></button>
                    </div>
                    </div>
                <div class="provide">
                        <label>You can provide your email, after approve with notified you.</label>
                        <div class="submit">
                            <form action="email.php" method="POST">
                            <input type="text" name="address" placeholder="Your email address">
                            <button type="submit">Submit</button>
                        </form>
                        </div>
                    </div>
                </div>

            </section>
            
        </section>
        <script>
            document.addEventListener("DOMContentLoaded", function(){
                const userType = getCookie("user_type");
                
                if (userType !== "vendor" && userType !== "admin") {
                    window.location.href = "/auth/view/login.html";
                }
                
                logoutButton = document.getElementById("header_logout");
                
                logoutButton.addEventListener("click", function(event) {
                    event.preventDefault();
                    document.cookie = "vendor_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    document.cookie = "user_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    window.location.href = "/auth/view/login.html";
                    fetch('/api/logout.php')
                    .then(response => response.json())
                    .then(data => {
                        window.location.href = '/auth/view/login.html';
                    });
                });
            });
            
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(";").shift();
            }
        </script>
    </body>
</html>