<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id = $_POST['vendor_id'];
    $decision = $_POST['decision'];
    
    $sql = "UPDATE vendorpages SET status=? WHERE vendor_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $decision, $vendor_id);
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    if ($decision === 'approve') {
        $sql = "UPDATE vendorpages SET status='approved' WHERE vendor_id = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $vendor_id);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    
        $sql = "SELECT id, vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path FROM vendorpages WHERE vendor_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $vendor_id);
        if (!$stmt->execute()) {
            die("Second execute failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $get_vendor_datas = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $get_vendor_datas [] = $row;
            }
        } else {
            echo "error";
        }

        if (is_array($get_vendor_datas) || is_object($get_vendor_datas)) {
            foreach($get_vendor_datas as $vendor_data){
                var_dump($get_vendor_datas);
                $vendorpage_id = $vendor_data['id'];
                $vendor_name = $vendor_data['vendor_name'];
                $address_encoded = $vendor_data['address'];
                $google_maps_api_key = 'AIzaSyDCmzOz9J0VysdgXxUVwVwVsR85xYawDI4';
                $map_embed_code = "<iframe width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" src=\"https://www.google.com/maps/embed/v1/place?key={$google_maps_api_key}&q={$address_encoded}&zoom=19\" allowfullscreen></iframe>";
                    
                $vendor_dir = "C:/xampp/htdocs/FYP/vendorpage/img_vendor/vendorpage_" . $vendor_name;
                $vendor_img_dir = $vendor_dir . "/vendor_img";
                
                if (!is_dir($vendor_dir)) {
                    mkdir($vendor_dir, 0755, true);
                }                
                if (!is_dir($vendor_img_dir)) {
                    mkdir($vendor_img_dir, 0755, true);
                }               

                $main_photo_name = $vendor_data['main_photo_path'];
                $main_photo_tmp_path = "C:/xampp/htdocs/FYP/vendorpage/img_database/" . $main_photo_name;
                $main_photo_dest_path = $vendor_img_dir . "/" . $main_photo_name;
                if (file_exists($main_photo_tmp_path)) {
                    rename($main_photo_tmp_path, $main_photo_dest_path);
                }
                $main_photo_display_path = "/FYP/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $main_photo_name;

                $other_photos_names = json_decode($vendor_data['other_photos_paths'], true);
                $other_photos_display_paths = [];
                
                if (is_array($other_photos_names)) {
                    foreach ($other_photos_names as $name) {
                        $other_photo_tmp_path = "C:/xampp/htdocs/FYP/vendorpage/img_database/" . $name;
                        $other_photo_dest_path = $vendor_img_dir . "/" . $name;
                        
                        if (file_exists($other_photo_tmp_path)) {
                            rename($other_photo_tmp_path, $other_photo_dest_path);
                        }
                        
                        $display_path = "/FYP/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $name;
                        $other_photos_display_paths[] = $display_path;
                    }
                }
                
                $image_slider_html = '';
                if (!empty($main_photo_display_path)) { 
                    $image_slider_html .= '<li class="splide__slide"><img src="' . $main_photo_display_path . '" alt="Main Photo"></li>';
                } 
                if (is_array($other_photos_display_paths)) {
                    foreach ($other_photos_display_paths as $display_path) {
                        $image_slider_html .= '<li class="splide__slide"><img src="' . $display_path . '" alt="Other Photo"></li>';
                    } 
                }

                $menu_img_paths = json_decode($vendor_data['menu_img_path'], true);
                $menu_img_display_paths = [];

                if (is_array($menu_img_paths)) {
                    foreach ($menu_img_paths as $path) {
                        $name = basename($path);
                        $menu_photo_tmp_path = "C:/xampp/htdocs/FYP/vendorpage/img_database/" . $name;
                        $menu_photo_dest_path = $venndor_img_dir . "/" . $name;
                        
                        if (file_exists($menu_photo_tmp_path)) {
                            rename($menu_photo_tmp_path, $menu_photo_dest_path);
                        }
                        
                        $menu_display = "/FYP/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $name;
                        $menu_img_display_paths[] = $menu_display;
                    }
                }

                $menu_food_names = json_decode($vendor_data['food_name'], true);
                $menu_food_prices = json_decode($vendor_data['food_price'], true);
                $menu_html_array = [];
                $menu_html = '';

                if (is_array($menu_food_names)) {
                    foreach ($menu_food_names as $index => $food_name) {
                        if (empty($food_name) || empty($menu_food_prices[$index])) {
                            continue;
                        }
                        
                        $food_price = $menu_food_prices[$index];
                        $menu_img_path = $menu_img_display_paths[$index] ?? '';
                        $menu_item_html = '<div class="menu_box"><div class="menu_detail">';
                        $menu_item_html .= '<p class="not">Food Name: ' . htmlspecialchars($food_name) . '</p>';
                        $menu_item_html .= '<p class="not">Food Price: ' . htmlspecialchars($food_price) . '</p>';
                        
                        if (!empty($menu_img_path)) {
                            $menu_item_html .= '<div class="menu_img"><img src="' . htmlspecialchars($menu_img_path) . '" alt="Menu Item"></div>';
                        }
                        $menu_item_html .= '</div></div>';
                        $menu_html_array[] = $menu_item_html;
                    } 
                } $menu_html = implode('', $menu_html_array);

                $opening_hours = $vendor_data['opening_hours'];
                function parseTime($time) {
                    if (empty(trim($time))) return 'Closed';
                    if ($time === '24') return '24 Hours';
                    if (preg_match('/(\d{1,2})(?::\d{2})?\s*(am|pm)?/', $time, $matches)) {
                        $hour = intval($matches[1]);
                        $ampm = $matches[2] ?? '';
                        if ($ampm === 'pm' && $hour < 12) $hour += 12;
                        if ($ampm === 'am' && $hour == 12) $hour = 0;
                        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                    }
                return 'Invalid';
                }
                
                $opening_hours = [];
                if (is_array($opening_hours)) {
                    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                        $open_time = parseTime($_POST["opening_hours_{$day}_open"] ?? '');
                        $close_time = parseTime($_POST["opening_hours_{$day}_close"] ?? '');
                        
                        $opening_hours[$day] = [
                            'open' => $open_time,
                            'close' => $close_time,
                        ];
                    }
                }
 
                $opening_hours_html = '';
                if (is_array($opening_hours)) {
                    foreach ($opening_hours as $day => $times) {
                        if ($times['open'] === 'Closed') {
                            $opening_hours_html .= '<li><a><div class="icon"><span class="uil uil-calender"></span></div>' . ucfirst($day) . ' - Closed</a></li>';
                        }  elseif ($times['open'] === '24 Hours') {
                            $opening_hours_html .= '<li><a><div class="icon"><span class="uil uil-calender"></span></div>' . ucfirst($day) . ' - Open 24 Hours</a></li>';
                        } else {
                            $opening_hours_html .= '<li><a><div class="icon"><span class="uil uil-calender"></span></div>' . ucfirst($day) . ' ' . $times['open'] . '-' . $times['close'] . '</a></li>';
                        }
                    }
                }
                $opening_hours_json = json_encode($opening_hours);
                
                $html_template = file_get_contents('C:/xampp/htdocs/FYP/vendorpage/vendorpage.html');
                $html_template = str_replace('{{vendor_name}}', htmlspecialchars($vendor_data['vendor_name']), $html_template);
                $html_template = str_replace('{{food_type}}', htmlspecialchars($vendor_data['food_type']), $html_template);
                $html_template = str_replace('{{address}}', htmlspecialchars($vendor_data['address']), $html_template);
                $html_template = str_replace('{{map}}', $map_embed_code, $html_template);
                $html_template = str_replace('{{phone_number}}', htmlspecialchars($vendor_data['phone_number']), $html_template);
                $html_template = str_replace('{{dining_option}}', htmlspecialchars($vendor_data['dining_option']), $html_template);
                $html_template = str_replace('{{open_hours}}', $opening_hours_html, $html_template);
                $html_template = str_replace('{{opening_hours_json}}', $opening_hours_json, $html_template);
                $html_template = str_replace('{{service_option}}', htmlspecialchars($vendor_data['service_option']), $html_template);
                $html_template = str_replace('{{main_img}}', $image_slider_html, $html_template);
                $html_template = str_replace('{{thumb_img}}', $image_slider_html, $html_template);
                $html_template = str_replace('{{menu}}', $menu_html, $html_template);
                
                $vendor_page_path = "C:/xampp/htdocs/FYP/vendorpage/{$vendor_name}.html";
                file_put_contents($vendor_page_path, $html_template);    
                
                
                $filePaths = [
                    'C:/xampp/htdocs/FYP/index.html',
                    'C:/xampp/htdocs/FYP/user/user_account.php',
                    'C:/xampp/htdocs/FYP/vendor/vendor_account.php',
                    'C:/xampp/htdocs/FYP/admin/admin.php'
                ];

                if (is_array($filePaths)) {
                    foreach ($filePaths as $homepageFilePath) {
                        $homepageContent = file_get_contents($homepageFilePath);
                        
                        $newVendorHTML = '<li class="vendor" id="vendorpage_' . $vendorpage_id . '" data-rating="" data-stars="">';
                        $newVendorHTML .= '<a class="vendorpage_link" href="/FYP/vendorpage/' . $vendor_name . '.html">';
                        $newVendorHTML .= '<div class="vendor_wrapper">';
                        $newVendorHTML .= '<div class="vendor_img"><img src="' . $main_photo_display_path . '"></div>';
                        $newVendorHTML .= '<div class="vendor_info">';
                        $newVendorHTML .= '<h2>' . htmlspecialchars($vendor_data['vendor_name']) . '</h2>';
                        $newVendorHTML .= '<span class="ratings_component" id="vendor_ratings">';
                        $newVendorHTML .= '<div class="vendor_info_ratings">';               
                        $newVendorHTML .= '<div class="rating">';                 
                        $newVendorHTML .= '<div class="star-container" id="star-container"></div>';              
                        $newVendorHTML .= '<span class="rating_label_primary">' . '/5</span>';               
                        $newVendorHTML .= '<span class="rating_label_secondary">('  . ')</span>';               
                        $newVendorHTML .= '</div>';                  
                        $newVendorHTML .= '<p>' . htmlspecialchars($vendor_data['dining_option']) . '</p>';                  
                        $newVendorHTML .= '</div></span>';                    
                        $newVendorHTML .= '<div class="food_type"><p>' . htmlspecialchars($vendor_data['food_type']) . '</p></div>';                   
                        $newVendorHTML .= '</div></div></a></li>';
                        
                        $updatedHomepageContent = str_replace(                  
                            '<!-- New vendors will be added below -->',                   
                            "<!-- New vendors will be added below -->\n" . $newVendorHTML,                    
                            $homepageContent                               
                        );                                 
                        file_put_contents($homepageFilePath, $updatedHomepageContent);                
                    }
                }
            }
        }
        
    } 
    elseif ($decision === 'reject') {
        $sql = "UPDATE vendorpages SET status='rejected' WHERE vendor_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $vendor_id);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }
    $conn->close();
}
header("Location: /admin/dashboard.php");
?>
