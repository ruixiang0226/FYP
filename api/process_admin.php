<?php
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function get file from Github
function getFileFromGithub($owner, $repo, $filePath, $token) {
    $filePath = urlencode($filePath);
    $api_url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: PHP",
        "Authorization: token $token",
        "Accept: application/vnd.github.v3.raw"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpcode != 200) {
        die("Failed to get file from GitHub, HTTP code: $httpcode");
    }
    
    return $response;
}

// Function upload file to Github
function uploadToGithub($owner, $repo, $filePath, $content, $token) {
    $filePath = urlencode($filePath);
    $api_url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    $data = [
        "message" => "Add file",
        "content" => base64_encode($content)
    ];
    $options = [
        "http" => [
            "header" => [
                "User-Agent: PHP",
                "Authorization: token $token",
                "Content-Type: application/json",
                "Accept: application/vnd.github.v3+json"
            ],
            "method" => "PUT",
            "content" => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    if ($response === FALSE) {
        var_dump($http_response_header);
        die("Something went wrong while uploading to GitHub");
    }
}

$github_token = getenv('GITHUB_TOKEN');
$github_repo = "FYP";
$github_owner = "ruixiang0226";


// Data Collection & Preprocessing
$vendor_name = $_POST['vendor_name'];
$food_types_string = implode(', ', $_POST['food_type'] ?? []);
$address = $_POST['address'];
$google_maps_api_key = 'AIzaSyDCmzOz9J0VysdgXxUVwVwVsR85xYawDI4';
$address_encoded = urlencode($address);
$phone_number = $_POST['phone_number'];
$dining_option = $_POST['dining_option'] ?? 'Default Value';
$service_options_string = implode(' / ', $_POST['service_option'] ?? []);
$map_embed_code = "<iframe width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" src=\"https://www.google.com/maps/embed/v1/place?key={$google_maps_api_key}&q={$address_encoded}&zoom=19\" allowfullscreen></iframe>";
$status = 'approved';

// Opening Hours
function parseTime($time) {
    if (empty(trim($time))) return 'Closed';
    if (preg_match('/(\d{1,2})(?::\d{2})?\s*(am|pm)?/', $time, $matches)) {
        $hour = intval($matches[1]);
        $ampm = $matches[2] ?? '';
        if ($ampm === 'pm' && $hour < 12) $hour += 12;
        if ($ampm === 'am' && $hour == 12) $hour = 0;
        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
    }
    return 'Invalid';
}

$days_of_week = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
$opening_hours = [];

foreach ($days_of_week as $day) {
    $opening_hours[$day] = [
        'open' => parseTime($_POST["opening_hours_{$day}_open"] ?? ''),
        'close' => parseTime($_POST["opening_hours_{$day}_close"] ?? ''),
    ];
}

$opening_hours_html = join('', array_map(function($day, $times) {
    return "<li><a><div class='icon'><span class='uil uil-calender'></span></div>" .
           ucfirst($day) . ' ' .
           ($times['open'] === 'Closed' ? 'Closed' : $times['open'] . '-' . $times['close']) .
           "</a></li>";
}, array_keys($opening_hours), $opening_hours));

$opening_hours_json = json_encode($opening_hours);
$opening_hours_serialized = serialize($opening_hours);


if ($_FILES['main_photo']['error'] == 0) {
    $file_name = $_FILES['main_photo']['name'];
    $tmp_name = $_FILES['main_photo']['tmp_name'];
    $main_photo_display_path = "/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;

    $main_photo_path = "vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;
    $main_photo_content = file_get_contents($tmp_name);
    uploadToGithub($github_owner, $github_repo, $main_photo_path, $main_photo_content, $github_token);
}

// another photo
$other_photos_paths = [];
$other_photos_display_paths = []; 

if (isset($_FILES['another_picture'])) {
    foreach ($_FILES['another_picture']['name'] as $key => $name) {
        $tmp_name = $_FILES['another_picture']['tmp_name'][$key];
        $display_path = "/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $name; 

        $path = "vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $name;
        $content = file_get_contents($tmp_name);
        uploadToGithub($github_owner, $github_repo, $path, $content, $github_token);

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
        
        $menu_img_path = "vendorpage/img_vendor/vendorpage_$vendor_name/menu_img/" . $file_name;
        $menu_display = "/vendorpage/img_vendor/vendorpage_$vendor_name/menu_img/" . $file_name;

        $menu_img_content = file_get_contents($tmp_name);
        uploadToGithub($github_owner, $github_repo, $menu_img_path, $menu_img_content, $github_token);
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
$relative_vendor_page_template_path = "vendorpage/vendorpage.html";
$html_template = getFileFromGithub($github_owner, $github_repo, $relative_vendor_page_template_path, $github_token);

// Data for replacing placeholders
$replace_data = [
    '{{vendor_name}}' => $vendor_name,
    '{{food_type}}' => htmlspecialchars($food_types_string),
    '{{address}}' => $address,
    '{{map}}' => $map_embed_code,
    '{{phone_number}}' => $phone_number,
    '{{dining_option}}' => htmlspecialchars($dining_option),
    '{{open_hours}}' => $opening_hours_html,
    '{{opening_hours_json}}' => $opening_hours_json,
    '{{service_option}}' => htmlspecialchars($service_options_string),
    '{{main_img}}' => $image_slider_html,
    '{{thumb_img}}' => $image_slider_html,
    '{{menu}}' => $menu_html,
];

foreach ($replace_data as $placeholder => $value) {
    $html_template = str_replace($placeholder, $value, $html_template);
}

if (!is_dir('vendorpage')) {
    mkdir('vendorpage', 0755, true);
}

// Save the new HTML file
$vendor_page_path = "vendorpage/{$vendor_name}.html";
if (file_put_contents($vendor_page_path, $html_template) === false) {
    die("Error writing new vendor page");
}

// Upload HTML to GitHub
uploadToGithub($github_owner, $github_repo, $vendor_page_path, $html_template, $github_token);


$sql = "INSERT INTO vendorpages (vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssss", $vendor_name, $food_types_string, $address, $phone_number, $opening_hours_serialized, $dining_option, $service_options_string, $main_photo_path, $other_photos_names_json, $menu_food_names_json, $menu_food_prices_json, $menu_img_paths_json, $status);

if ($stmt->execute()) {
    $vendorpage_id = $conn->insert_id;
    
    $filePaths = [
        'index.html',
        'user/user_account.php',
        'vendor_acc/vendor_account.php',
        'admin/admin.php'
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
        uploadToGithub($github_owner, $github_repo, $homepageFilePath, $updatedHomepageContent, $github_token);
    }
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
header("Location: /vendorpage/{$vendor_name}.html ");
?>