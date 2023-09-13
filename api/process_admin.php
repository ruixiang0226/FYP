<?php
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// detect admin id or vendor id
$admin_id = $_GET['admin_id'] ?? null;

// vendor name
$vendor_name = $_POST['vendor_name'];

// food types
$food_types = $_POST['food_type'] ?? [];
$food_types_string = implode(', ', $food_types);

// address & generate map embed code
$address = $_POST['address'];
$google_maps_api_key = 'AIzaSyDCmzOz9J0VysdgXxUVwVwVsR85xYawDI4';
$address_encoded = urlencode($address);
$map_embed_code = "<iframe width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" src=\"https://www.google.com/maps/embed/v1/place?key={$google_maps_api_key}&q={$address_encoded}&zoom=19\" allowfullscreen></iframe>";

// phone number
$phone_number = $_POST['phone_number'];

// dining option
$dining_option = $_POST['dining_option'] ?? 'Default Value';

// opening hours
function parseTime($time) {
    if (empty(trim($time))) return 'Closed'; //
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
foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
    $open_time = parseTime($_POST["opening_hours_{$day}_open"] ?? '');
    $close_time = parseTime($_POST["opening_hours_{$day}_close"] ?? '');
    
    $opening_hours[$day] = [
        'open' => $open_time,
        'close' => $close_time,
    ];
}

$opening_hours_html = '';
foreach ($opening_hours as $day => $times) {
    if ($times['open'] === 'Closed') {
        $opening_hours_html .= '<li><a><div class="icon"><span class="uil uil-calender"></span></div>' . ucfirst($day) . ' - Closed</a></li>';
    } else {
        $opening_hours_html .= '<li><a><div class="icon"><span class="uil uil-calender"></span></div>' . ucfirst($day) . ' ' . $times['open'] . '-' . $times['close'] . '</a></li>';
    }
}

$opening_hours_json = json_encode($opening_hours);

// service options 
$selected_services = $_POST['service_option'] ?? [];
$service_order = ["Dine-in", "Takeaway", "Delivery", "Drive-through"];
$selected_services_ordered = array_intersect($service_order, $selected_services);
$service_options_string = implode(' / ', $selected_services);

// img_vendor file
$baseDir =  __DIR__ . '/../vendorpage/img_vendor'; 
$vendor_dir = $baseDir . DIRECTORY_SEPARATOR . "vendorpage_" . $vendor_name;
$vendor_img_dir = $vendor_dir . DIRECTORY_SEPARATOR . "vendor_img";
$menu_img_dir = $vendor_dir . DIRECTORY_SEPARATOR . "menu_img";

// Create directories if they don't exist
foreach ([$vendor_dir, $vendor_img_dir, $menu_img_dir] as $dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            die("Failed to create directory: $dir");
        }
    }
}

// main photo
$main_photo_path = '';
$main_photo_display_path = '';

if ($_FILES['main_photo']['error'] == 0) {
    $file_name = $_FILES['main_photo']['name'];
    $tmp_name = $_FILES['main_photo']['tmp_name'];
    $main_photo_path = "$vendor_img_dir/" . $file_name;
    $main_photo_display_path = "/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;
    move_uploaded_file($tmp_name, $main_photo_path);
}

// another photo
$other_photos_paths = [];
$other_photos_display_paths = []; 

if (isset($_FILES['another_picture'])) {
    foreach ($_FILES['another_picture']['name'] as $key => $name) {
        $tmp_name = $_FILES['another_picture']['tmp_name'][$key];
        $path = "$vendor_img_dir/" . $name;
        $display_path = "/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $name; 
        move_uploaded_file($tmp_name, $path);
        $other_photos_paths[] = $path;
        $other_photos_display_paths[] = $display_path;
    }
}
$other_photos_names_json = json_encode($other_photos_paths);

// image slider display
$image_slider_html = '';
if (!empty($main_photo_display_path)) { 
    $image_slider_html .= '<li class="splide__slide"><img src="' . $main_photo_display_path . '" alt=""></li>';
}
foreach ($other_photos_display_paths as $display_path) {
    $image_slider_html .= '<li class="splide__slide"><img src="' . $display_path . '" alt=""></li>';
}

// menu form
$menu_html = '';
$menu_imgs = $_FILES['menu_img'] ?? [];
$menu_food_names = $_POST['food_name'] ?? [];
$menu_food_prices = $_POST['food_price'] ?? [];
$menu_food_names_str = implode(", ", $menu_food_names);
$menu_food_prices_str = implode(", ", $menu_food_prices);
$menu_food_names_json = json_encode($menu_food_names);
$menu_food_prices_json = json_encode($menu_food_prices);
$menu_img_paths = [];
$menu_html_array = [];

foreach ($menu_food_names as $index => $food_name) {
    $food_name_bind = $food_name;
    $food_price_bind = $menu_food_prices[$index] ?? '';  
    $menu_img_path_bind = '';  

    if (empty($food_name) || empty($menu_food_prices[$index]) || $menu_imgs['error'][$index] !== 0) {
        continue;
    }

    if (isset($menu_imgs['name'][$index]) && $menu_imgs['error'][$index] == 0) {
        $file_name = $menu_imgs['name'][$index];
        $tmp_name = $menu_imgs['tmp_name'][$index];
        
        $menu_img_path = "$menu_img_dir/" . $file_name;
        $menu_display = "/vendorpage/img_vendor/vendorpage_$vendor_name/menu_img/" . $file_name;

        move_uploaded_file($tmp_name, $menu_img_path);
        $menu_img_paths[] = $menu_display;
    }

    $menu_item_html = '<div class="menu_box"><div class="menu_detail">';
    $menu_item_html .= '<p class="not">Food Name: ' . htmlspecialchars($food_name_bind) . '</p>';
    $menu_item_html .= '<p class="not">Food Price: ' . htmlspecialchars($food_price_bind) . '</p>'; 
    $menu_item_html .= '<div class="menu_img"><img src="' . htmlspecialchars($menu_display) . '" alt=""></div>';
    $menu_item_html .= '</div></div>';

    $menu_html_array[] = $menu_item_html;
}

$menu_html = implode('', $menu_html_array);
$menu_img_paths_str = implode(", ", $menu_img_paths);
$menu_img_paths_json = json_encode($menu_img_paths);

// Read the template files
$html_template = file_get_contents( __DIR__ . '/../vendorpage/vendorpage.html');

// Check if the template is read correctly
if ($html_template === false) {
    die("Error reading template");
}

// Replace placeholders with actual data
$html_template = str_replace('{{vendor_name}}', $vendor_name, $html_template);
$html_template = str_replace('{{food_type}}', htmlspecialchars($food_types_string), $html_template);
$html_template = str_replace('{{address}}', $address, $html_template);
$html_template = str_replace('{{map}}', $map_embed_code, $html_template);
$html_template = str_replace('{{phone_number}}', $phone_number, $html_template);
$html_template = str_replace('{{dining_option}}', htmlspecialchars($dining_option), $html_template);
$html_template = str_replace('{{open_hours}}', $opening_hours_html, $html_template);
$html_template = str_replace('{{opening_hours_json}}', $opening_hours_json, $html_template);
$html_template = str_replace('{{service_option}}', htmlspecialchars($service_options_string), $html_template);
$html_template = str_replace('{{main_img}}', $image_slider_html, $html_template);
$html_template = str_replace('{{thumb_img}}', $image_slider_html, $html_template);
$html_template = str_replace('{{menu}}', $menu_html, $html_template);

// Save the new HTML file
$vendor_page_path = __DIR__ . '/../vendorpage/{$vendor_name}.html';
file_put_contents($vendor_page_path, $html_template);

// Check if the file is written correctly
if (file_put_contents($vendor_page_path, $html_template) === false) {
    die("Error writing new vendor page");
}

$opening_hours_serialized = serialize($opening_hours);

$status = 'approved';

$sql = "INSERT INTO vendorpages (vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssss", $vendor_name, $food_types_string, $address, $phone_number, $opening_hours_serialized, $dining_option, $service_options_string, $main_photo_path, $other_photos_names_json, $menu_food_names_json, $menu_food_prices_json, $menu_img_paths_json, $status);

if ($stmt->execute()) {
    $vendorpage_id = $conn->insert_id;
    
    $filePaths = [
        __DIR__ . '/../index.html',
        __DIR__ . '/../user/user_account.php',
        __DIR__ . '/../vendor_acc/vendor_account.php',
        __DIR__ . '/../admin/admin.php'
    ];   

    foreach ($filePaths as $homepageFilePath) {
        $homepageContent = file_get_contents($homepageFilePath);
        
        $newVendorHTML = '<li class="vendor" id="vendorpage_' . $vendorpage_id . '" data-rating="" data-stars="">';
        $newVendorHTML .= '<a class="vendorpage_link" href="/vendorpage/' . $vendor_name . '.html">';
        $newVendorHTML .= '<div class="vendor_wrapper">';
        $newVendorHTML .= '<div class="vendor_img"><img src="' . $main_photo_display_path . '"></div>';
        $newVendorHTML .= '<div class="vendor_info">';
        $newVendorHTML .= '<h2>' . htmlspecialchars($vendor_name) . '</h2>';
        $newVendorHTML .= '<span class="ratings_component" id="vendor_ratings">';
        $newVendorHTML .= '<div class="vendor_info_ratings">';
        $newVendorHTML .= '<div class="rating">';
        $newVendorHTML .= '<div class="star-container" id="star-container"></div>';
        $newVendorHTML .= '<span class="rating_label_primary">' . '/5</span>';
        $newVendorHTML .= '<span class="rating_label_secondary">(' . ')</span>';
        $newVendorHTML .= '</div>';
        $newVendorHTML .= '<p>' . htmlspecialchars($dining_option) . '</p>';
        $newVendorHTML .= '</div></span>';
        $newVendorHTML .= '<div class="food_type"><p>' . htmlspecialchars($food_types_string) . '</p></div>';
        $newVendorHTML .= '</div></div></a></li>';
    
        $updatedHomepageContent = str_replace(
            '<!-- New vendors will be added below -->',
            "<!-- New vendors will be added below -->\n" . $newVendorHTML,
            $homepageContent
        );
    
        file_put_contents($homepageFilePath, $updatedHomepageContent);
    }
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
header("Location: /vendorpage/{$vendor_name}.html ");
?>