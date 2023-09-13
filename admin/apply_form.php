<?php
$admin_id = $_COOKIE['admin_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/web/assets/css/add_vendor.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <title>Vendor Form</title>

    <!--===google fonts link--==-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    

    <section class="register_form">
        <header>
            <div class="logo_container">
                <a id="homepage_link" href="">
                    <img src="/web/assets/img/logo.png" alt="Logo">
                    <span class="logo_title">Penang Local Food</span>
                </a>
            </div>
        </header>

        <div class="container">  
            <div class="forms">
                <form action="/api/process_admin.php" method="POST" enctype="multipart/form-data">
                <div class="form vendor">
                        <span class="title">Vendor Registration Form</span>
                    
                        <div class="column">
                            <div class="input-box">
                                <label>Vendor Name:</label>
                                <input type="text" name="vendor_name" placeholder="Enter your vendor/restaurant name" required>
                            </div>

                            <!--=== Types of Food --==-->
                            <div class="input-box">
                                <label>Type of Food:</label>
                                <div class="input-box-empty">
                                    <div class="select-btn">
                                        <span class="btn-text">Select Food Types</span>
                                        <span class="arrow-dwn">
                                            <i class="uil uil-angle-down"></i>
                                        </span>
                                    </div>
                                    <ul class="list-items">
                                        <li class="item" onclick="toggleFoodType('Halal')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Halal</span>
                                            <input type="checkbox" name="food_type[]" value="Halal" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Non-Halal')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Non-Halal</span>
                                            <input type="checkbox" name="food_type[]" value="Non-Halal" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Chicken')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Chicken</span>
                                            <input type="checkbox" name="food_type[]" value="Chicken" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Beef')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Beef</span>
                                            <input type="checkbox" name="food_type[]" value="Beef" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Rice')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Rice</span>
                                            <input type="checkbox" name="food_type[]" value="Rice" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Noodles')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Noodles</span>
                                            <input type="checkbox" name="food_type[]" value="Noodles" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Burger')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Burger</span>
                                            <input type="checkbox" name="food_type[]" value="Burger" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Seafood')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Seafood</span>
                                            <input type="checkbox" name="food_type[]" value="Seafood" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Local Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Local Food</span>
                                            <input type="checkbox" name="food_type[]" value="Local Food" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Malaysian Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Malaysian Food</span>
                                            <input type="checkbox" name="food_type[]" value="Malaysian Food" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Chinese Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Chinese Food</span>
                                            <input type="checkbox" name="food_type[]" value="Chinese Food" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Nasi Kandar')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Nasi Kandar</span>
                                            <input type="checkbox" name="food_type[]" value="Nasi Kandar" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Vegetarain Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Vegetarian Food</span>
                                            <input type="checkbox" name="food_type[]" value="Vegetarain Food" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Western Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Western Food</span>
                                            <input type="checkbox" name="food_type[]" value="Western Food" style="display:none">
                                        </li>
                                        <li class="item" onclick="toggleFoodType('Thai Food')">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">Thai Food</span>
                                            <input type="checkbox" name="food_type[]" value="Thai Food" style="display:none">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!--=== Address --==-->
                        <div class="input-box">
                            <label>Address:</label>
                            <input type="text" name="address" placeholder="Enter your vendor/restaurant location" required>
                        </div>

                        <!--=== Phone Number --==-->
                        <div class="column">
                            <div class="input-box">
                                <label>Phone Number:</label>
                                <input type="text" name="phone_number" placeholder="Enter your phone number">
                            </div>

                            <!--=== Dining Option --==-->
                            <div class="input-box dining">
                                <label>Select a Dining Option:</label>
                                <div class="select-box">
                                    <select name="dining_option">
                                        <option class="dp" hidden>Select your dining option</option>
                                        <option value="Food Count">Food Count</option>
                                        <option value="Restaurant">Restaurant</option>
                                        <option value="Hawker Stall">Hawker Stall</option>
                                        <option value="Cafe">Cafe</option>
                                        <option value="Western Restaurant">Western Restaurant</option>
                                        <option value="Chinese Restaurant">Chinese Restaurant</option>
                                        <option value="Indian Muslim Restaurant">Indian Muslim Restaurant</option>
                                        <option value="Japanese Restaurant">Japanese Restaurant</option>
                                        <option value="Fast Food Restaurant">Fast Food Restaurant</option>
                                        <option value="Vegetarian Restaurant">Vegetarian Restaurant</option>
                                    </select>
                                </div>
                            </div>
                        </div><br>

                        <!--=== Opening hours --==-->
                        <div class="open-hours">
                            <label>Opening Hours: </label>
                            <label id="small-remind">(If day off, leave it blank)</label>     
                        </div>

                        <div class="column">
                            <div class="input-box">
                                <label>Monday</label>
                                <div class="column">
                                    <div class="input-open">
                                        <input type="text" name="opening_hours_monday_open" placeholder="Open"></div>
                                        <div class="input-close">
                                            <input type="text" name="opening_hours_monday_close" placeholder="Close"></div>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label>Tuesday</label>
                                        <div class="column">
                                            <div class="input-open">
                                                <input type="text" name="opening_hours_tuesday_open" placeholder="Open"></div>
                                                <div class="input-close">
                                                    <input type="text" name="opening_hours_tuesday_close" placeholder="Close"></div>
                                                </div>
                                            </div>
                                        </div>

                        <div class="column">
                            <div class="input-box">
                                <label>Wednesday</label>
                                <div class="column">
                                    <div class="input-open">
                                        <input type="text" name="opening_hours_wednesday_open" placeholder="Open"></div>
                                        <div class="input-close">
                                            <input type="text" name="opening_hours_wednesday_close" placeholder="Close"></div>
                                        </div> 
                                    </div>
                                    <div class="input-box">
                                        <label>Thursday</label>
                                        <div class="column">
                                            <div class="input-open">
                                                <input type="text" name="opening_hours_thursday_open" placeholder="Open"></div>
                                                <div class="input-close">
                                                    <input type="text" name="opening_hours_thursday_close" placeholder="Close"></div>
                                                </div>
                                            </div>
                                        </div>

                        <div class="column">
                            <div class="input-box">
                                <label>Friday</label>
                                <div class="column">
                                    <div class="input-open">
                                        <input type="text" name="opening_hours_friday_open" placeholder="Open"></div>
                                        <div class="input-close">
                                            <input type="text" name="opening_hours_friday_close" placeholder="Close"></div>
                                        </div> 
                                    </div>
                                    <div class="input-box">
                                        <label>Saturday</label>
                                        <div class="column">
                                            <div class="input-open">
                                                <input type="text" name="opening_hours_saturday_open" placeholder="Open"></div>
                                                <div class="input-close">
                                                    <input type="text" name="opening_hours_saturday_close" placeholder="Close"></div>
                                                </div>
                                            </div> 
                                        </div>

                        <div class="input-box">
                            <label>Sunday</label>
                            <div class="column">
                                <div class="input-open" id="input-open">
                                    <input type="text" name="opening_hours_sunday_open" placeholder="Open"></div>
                                    <div class="input-close" id="input-close">
                                        <input type="text" name="opening_hours_sunday_close" placeholder="Close"></div>
                                    </div> 
                                </div>
                                
                                <!--=== Service Option --==-->
                                <div class="service-box">
                                    <label>Service Options:</label>
                                    <div class="service-option">
                                        <div class="service">
                                            <input type="checkbox" id="check-dine-in" name="service_option[]" value="Dine-In">
                                            <label for="check-dine-in">Dine-in</label>
                                        </div>
                                        <div class="service">
                                            <input type="checkbox" id="check-takeaway" name="service_option[]" value="Takeaway">
                                            <label for="check-takeaway">Takeaway</label>
                                        </div>
                                        <div class="service">
                                            <input type="checkbox" id="check-delivery" name="service_option[]" value="Delivery">
                                            <label for="check-delivery">Delivery</label>
                                        </div>
                                        <div class="service">
                                            <input type="checkbox" id="check-drive-through" name="service_option[]" value="Drive-Through">
                                            <label for="check-drive-through">Drive-through</label>
                                        </div>
                                    </div>
                                </div>
                            <div class="button">
                                <button type="button" class="prev" disabled>Prev</button>
                                <button type="button" class="next">Next</button>
                            </div>
                </div>
            
                <div class="form menu">
                    <span class="title">Upload More Details</span><br>
                    <p>Upload Picture:<p>
                        
                        <div class="column">
                            <div class="input-box">
                                <label>Main Photo</label>
                                <input type="file" id="file" name="main_photo" accept="image/*" hidden>
                                <div class="img-area" data-img="">
                                    <i class='bx bx-cloud-upload icon'></i>
                                    <h3>Upload Image</h3>
                                    <p>Image size must be less than <span>2MB</span></p>
                                </div>
                                <button class="select-image">Select Image</button>
                            </div>
                            
                            <div class="input-box">
                                    <label>Another Picture</label>
                                    <div class="wrapper">
                                        <div class="upload-file">
                                            <input type="file" id="fileInput" class="file-input" name="another_picture[]" multiple hidden accept="image/*">
                                            <i class='bx bx-cloud-upload icon'></i>
                                            <p>Browse File to Upload</p>
                                        </div>
                                        <div class="uploaded-area" id="uploadedArea"></div>
                                    </div> 
                            </div>
                        </div>

                        <div class="input-box">
                            <label>Setting Menu:</label>
                            <div class="menu-column">
                                <div class="menu-form">
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                </div>
                                <div class="menu-form">
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                </div>
                                <div class="menu-form">
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                </div>
                                <div class="menu-form">
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                </div>
                                <div class="menu-form">
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                    <div class="menu-item" id="menu-item">
                                        <label>Menu Form:</label>
                                        <div class="menu-content">
                                            <div class="menu-img-wrapper">
                                                <input class="menu-img" type="file" id="img_file" name="menu_img[]" accept="image/*" hidden>
                                                <div class="img-area" data-img="">
                                                    <i class="uil uil-plus"></i>
                                                    <span>Add Image</span>
                                                </div>
                                            </div>
                                            <div class="menu-info-wrapper">
                                                <input class="menu-info" type="text" name="food_name[]" placeholder="Food Name">
                                                <input class="menu-info" type="number" name="food_price[]" min="0" step="0.01" placeholder="Food Price">
                                            </div> 
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button">
                            <button type="button" class="prev" id="menuPrev">Prev</button>
                            <button type="submit" class="submit_form" id="submit_form">Submit</button>
                        </div>
                    </div>
                </form>          
            </div>
        </div>
    </section>
    <script src="/web/assets/js/add_vendor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let homepageLink = document.getElementById('homepage_link');
        
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(";").shift();
            }
        
            const userType = getCookie("user_type");
        
            if (userType !== "admin") {
                window.location.href = "/auth/view/login.html";
            }
        
            let vendorId = getCookie('admin_id');
        
            if (vendorId) {
                homepageLink.href = "/admin/admin.php";
            }
        });
    </script>       
</body>
</html>