<?php
session_start();

$mysqli = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if (!isset($_COOKIE['user_id'])) { 
    header("Location: /auth/view/login.html");
    exit();
}

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

    $sql = "SELECT username, email FROM user WHERE id='$user_id'";
    $result = $mysqli->query($sql); 

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email_address = $row['email'];
    } else {
        echo "No vendor found with the given ID.";
    }

    $sql1 = "SELECT reviews.vendorpage_id, reviews.user_id, reviews.rating, reviews.comment, vendorpages.vendor_name FROM reviews
    JOIN vendorpages ON reviews.vendorpage_id = vendorpages.id
    WHERE reviews.user_id = '$user_id'";
    $result1 = $mysqli->query($sql1);
    
    $reviews = array();
    
    if ($result1->num_rows > 0) {
        while($row = $result1->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    $sql2 = "SELECT views_history FROM user WHERE id = ?";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    $view_history = [];
    if ($result2->num_rows > 0) {
        $row = $result2->fetch_assoc();
        $view_history = json_decode($row['views_history'], true);
    }
} else {
    echo "Not found the user ID";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recommended Local Food Website</title>

    <!---======css=====-->
    <link rel="stylesheet" type="text/css" href="/web/assets/css/profile.css">
        
    <!--===box icons link--==-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!--===google fonts link--==-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">  
</head>
<body>
    <section>
        <header>
            <div class="logo_container">
                <img src="/web/assets/img/logo.png" alt="Logo">
                <span>Penang Local Food</span>
            </div>
            <div class="header_icons">
                <div class="icon" id="header_acc">
                    <a class="icon_link">
                        <i class='bx bx-user-circle'></i>
                        <span class="icon_label"><?php if (isset($username)) echo "$username";?></span>
                    </a>
                </div>
                <div class="icon" id="header_bth">
                    <a class="icon_link" id="homepage_link" href="">
                        <i class='bx bx-home'></i>
                        <span class="icon_label">Home</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="wrapper">
                <div class="navbar">
                    <ul>
                        <li class="active" id="profileNav">
                            <a href="#">
                                <i class='bx bx-user icon'></i>
                             </a>
                        </li>
                        <li>
                            <a href="#" id="reviewNav">
                                <i class='bx bx-comment'></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="historyNav">
                                <i class='bx bx-history'></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="feedbackNav">
                                <i class='bx bx-edit'></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="content-areas">
                    <div class="column content-section active" id="profile_content">
                        <div class="column_content">
                            <div class="profile">
                                <div class="user_portrait">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div class="user_name">
                                    <label id="username-label"><?php if (isset($username)) echo "$username";?></label>
                                </div>
                                <div class="phone_number">
                                    <label id="email-label"><?php if (isset($email_address)) echo "$email_address";?></label>
                                </div> 
                                <button type="button" id="logout"><i class='bx bx-log-out'></i><p>Logout</p></button>
                            </div>
                        </div>
                    </div>

                    <div class="column content-section" id="review_content">
                        <div class="column_content">
                            <div class="review">
                                <label>Reviews</label>   
                                <ul class="review_content">
                                <?php 
                                $counter = 0;
                                foreach ($reviews as $review):
                                $counter++;
                                ?>
                                    <li class="review_box">
                                        <div class="review_header">
                                            <p><?php echo htmlspecialchars($review['vendor_name']); ?></p>
                                            <div class="star-container" id="star-container-<?php echo $counter; ?>" data-rating="<?php echo $review['rating']; ?>"></div>
                                        </div>
                                        <div class="review_body">
                                            <p><?php echo htmlspecialchars($review['comment']); ?></p>
                                        </div>
                                    </li>
                                <?php 
                                endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="column content-section" id="history_content">
                        <div class="column_content">
                            <div class="history">
                                <label>Views History</label>
                                <ul class="history_content">
                                <?php
                                 foreach ($view_history as $vendorpage_id => $timestamp) {
                                    $sql = "SELECT vendor_name FROM vendorpages WHERE id = ?";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bind_param("i", $vendorpage_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $vendor_name = $row['vendor_name'];
                                        $formatted_time = date('Y-m-d H:i:s', $timestamp);
                                        echo "<li class='history_box'>";
                                        echo "<a class='vendorpage_link' href='/FYP/vendorpage/$vendor_name.html'>";
                                        echo "<div class='name_wrp'>";
                                        echo "<p id='name'>$vendor_name</p>";
                                        echo "</div>";
                                        echo "<div class='time_wrp'>";
                                        echo "<p id='time'>$formatted_time</p>";
                                        echo "</div>";
                                        echo "</a>";
                                        echo "</li>";
                                    }
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="column content-section" id="feedback_content">
                        <div class="column_content">
                            <div class="feedback">
                                <div class="feedback_content">
                                    <label>Feedback</label>
                                    <form method="POST" action="feedback.php">
                                        <textarea class="form" name="feedback" placeholder="Write feedback here..."></textarea>
                                        <button type="submit" name="feedback_submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/web/assets/js/user_profile.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let homepageLink = document.getElementById('homepage_link');
                
            function getCookie(name) {
                let value = "; " + document.cookie;
                let parts = value.split("; " + name + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
            }
                
            let userId = getCookie('user_id');
            let userType = getCookie('user_type'); 
                
            if (userId && userType === 'user') {
                homepageLink.href = "/user/user_account.php";
            }
        });
        
        $(document).ready(function() {
            $('.vendorpage_link').click(function(e) {
                e.preventDefault();
                var vendorName = $(this).find('#name').text();
                window.location.href = '/FYP/vendorpage/' + vendorName + '.html';
            });
        });

        logoutButton = document.getElementById("logout");

        logoutButton.addEventListener("click", function(event) {
            event.preventDefault();
            document.cookie = "user_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "auth/view/login.html";
            fetch('/api/logout.php')
            .then(response => response.json())
            .then(data => {
                window.location.href = '/auth/view/login.html';
            });
        });
    </script>
</body>
</html>
