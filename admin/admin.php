<?php
session_start();

if (!isset($_SESSION['admin_id'])) { 
    header("Location: /auth/view/login.html");
    exit();
}

$loggedIn = true;
$admin_id = $_SESSION['admin_id'];
$username = $_SESSION['admin_username'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Penang Local Food Website</title>

    <!---======css=====-->
    <link rel="stylesheet" type="text/css" href="/web/assets/css/style.css">
        
    <!--===box icons link--==-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!--===google fonts link--==-->
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
                <div class="icon" id="header_dashboard">
                    <a class="icon_link"href="/admin/dashboard.php">
                        <i class='bx bx-cog'></i>
                        <span class="icon_label">Dashboard</span>
                    </a>
                </div>
                <div class="icon" id="header_application">
                    <a class="icon_link"href="/admin/apply_form.php">
                        <i class='bx bx-edit'></i>
                        <span class="icon_label">Apply Vendor</span>
                    </a>
                </div>
                <div class="icon" id="header_acc">
                    <a class="icon_link">
                        <i class='bx bx-user-circle'></i>
                        <span class="icon_label"><?php echo $_SESSION['admin_username']; ?></span>
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

            <section class="img_wrapper">
                <div class="image-slider">
                    <div class="slider-text">
                        <h1>Welcome to</h1>
                        <h1>Penang Local Food</h1>
                        <h1>Website</h1>
                    </div>
                    <div class="slide-wrapper">
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Penang Bridge</h1></div>
                        <img src="/web/assets/img/homepage_img_1.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Kek Lok Si Temple</h1></div>
                        <img src="/web/assets/img/homepage_img_2.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Penang Street Art</h1></div>
                        <img src="/web/assets/img/homepage_img_3.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>George Town</h1></div>
                        <img src="/web/assets/img/homepage_img_4.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Penang Local Food</h1></div>
                        <img src="/web/assets/img/homepage_img_6.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Nasi Lemak</h1></div>
                        <img src="/web/assets/img/homepage_img_7.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Nasi Kandar</h1></div>
                        <img src="/web/assets/img/homepage_img_8.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Char Koay Teow</h1></div>
                        <img src="/web/assets/img/homepage_img_9.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Mee Goreng</h1></div>
                        <img src="http://farm8.staticflickr.com/7383/10976773445_d886c05917_b.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Koay Teow Soup</h1></div>
                        <img src="https://farm6.staticflickr.com/5536/10977403484_60f593127e_b.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Asam Laksa</h1></div>
                        <img src="http://farm3.staticflickr.com/2871/10977086434_31ec5b5cd6_b.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Cendol</h1></div>
                        <img src="http://farm6.staticflickr.com/5528/10976865095_1e6e422887_b.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Curry Mee</h1></div>
                        <img src="http://farm4.staticflickr.com/3789/10980212116_7ecb4f6483_b.jpg" alt="">
                    </div>
                    <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Hokkien Me</h1></div>
                        <img src="http://farm3.staticflickr.com/2856/10977054055_a1099149aa_b.jpg" alt="">
                    </div>
                      <div class="slide">
                        <div class="dark-overlay"></div>
                        <div class="slider-text-img"><h1>Nasi Kandar</h1></div>
                        <img src="/web/assets/img/homepage_img_10.jpg" alt="">
                    </div>
                    </div>
            </section> 
                    
            <section class="wrapper">

                <div class="search">
                    <div class="search_container">
                        <div class="search_box">
                            <div class="search_box_icon">
                                <i class="bx bx-search"></i>
                            </div>
                            <div class="search_input">
                                <input type="search" autocomplete="off" placeholder="Search Food and Location....." value>
                            </div>
                        </div>
                    </div>
                </div>

            <h2 class="heading">Trending</h2>

            <div class="view_container">
                <ul class="vendor_list" id="vendorContainer">
                    <!-- New vendors will be added below -->
<li class="vendor" id="vendorpage_10" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/RESTORAN TITI PAPAN.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_RESTORAN TITI PAPAN/vendor_img/2020-03-17.jpg"></div><div class="vendor_info"><h2>RESTORAN TITI PAPAN</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Indian Muslim Restaurant</p></div></span><div class="food_type"><p>Halal, Local Food, Malaysian Food, Nasi Kandar</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_9" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Mr. Por’s Duck Koay Chap.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Mr. Por’s Duck Koay Chap/vendor_img/1.jpg"></div><div class="vendor_info"><h2>Mr. Por’s Duck Koay Chap</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Restaurant</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_8" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Pitt Street Koay Teow Thng.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Pitt Street Koay Teow Thng/vendor_img/4ccaa6c90e4148f6a847922efde7e709.jpg"></div><div class="vendor_info"><h2>Pitt Street Koay Teow Thng</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Restaurant</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_7" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Sister Curry Mee.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Sister Curry Mee/vendor_img/11.jpg"></div><div class="vendor_info"><h2>Sister Curry Mee</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Hawker Stall</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_6" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Air Itam Asam Laksa.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Air Itam Asam Laksa/vendor_img/1.jpg"></div><div class="vendor_info"><h2>Air Itam Asam Laksa</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Restaurant</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_5" data-rating="" . data-stars=""><a class="vendorpage_link" href="/vendorpage/Kafe Ping Hooi.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Kafe Ping Hooi/vendor_img/1.jpg"></div><div class="vendor_info"><h2>Kafe Ping Hooi</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Restaurant</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food, Malaysian Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_4" data-rating="" . data-stars=""><a class="vendorpage_link" href="/vendorpage/Oh Chien at New Lane Hawker Centre.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Oh Chien at New Lane Hawker Centre/vendor_img/1.jpg.webp"></div><div class="vendor_info"><h2>Oh Chien at New Lane Hawker Centre</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Hawker Stall</p></div></span><div class="food_type"><p>Non-Halal, Seafood, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_3" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Sister Yao’s.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Sister Yao’s/vendor_img/1.jpg"></div><div class="vendor_info"><h2>Sister Yao’s</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Hawker Stall</p></div></span><div class="food_type"><p>Non-Halal, Noodles, Local Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_2" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Kheng Pin Cafe.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Kheng Pin Cafe/vendor_img/2023-08-07.jpg"></div><div class="vendor_info"><h2>Kheng Pin Cafe</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Hawker Stall</p></div></span><div class="food_type"><p>Non-Halal, Rice, Noodles, Local Food, Chinese Food</p></div></div></div></a></li>
<li class="vendor" id="vendorpage_1" data-rating="" data-stars=""><a class="vendorpage_link" href="/vendorpage/Ravi's Claypot Apom Manis.html"><div class="vendor_wrapper"><div class="vendor_img"><img src="/vendorpage/img_vendor/vendorpage_Ravi's Claypot Apom Manis/vendor_img/pulau-tikus-claypot-apom-1.jpg"></div><div class="vendor_info"><h2>Ravi&#039;s Claypot Apom Manis</h2><span class="ratings_component" id="vendor_ratings"><div class="vendor_info_ratings"><div class="rating"><div class="star-container" id="star-container"></div><span class="rating_label_primary">/5</span><span class="rating_label_secondary">()</span></div><p>Hawker Stall</p></div></span><div class="food_type"><p>Halal, Local Food, Malaysian Food</p></div></div></div></a></li>
                    <!-- Existing vendors can be here -->                                      
                </ul>
                </div>
                <div class="pagination_container">
                <ul class="pagination">
                    <li><a href=""class="prev">< Prev</a></li>
                    <li class="page_number active" ><a href="">1</a></li>
                    <li class="page_number"><a href="">2</a></li>
                    <li class="page_number"><a href="">3</a></li>
                    <li><a href=""class="next">Next ></a></li>
                </ul>
            </div>
            </section>
        </section>

        <!--===js file link--==-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="/web/assets/js/script.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
            logoutButton = document.getElementById("header_logout");
            
            logoutButton.addEventListener("click", function(event) {
                event.preventDefault();
                document.cookie = "admin_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "user_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = "/auth/view/login.html";
                fetch('/api/logout.php')
                .then(response => response.json())
                .then(data => {
                    window.location.href = '/auth/view/login.html';
                });
            });
        });
        </script>
    </body>
</html>
