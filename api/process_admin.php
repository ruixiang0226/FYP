<?php
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get file from Github
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
    
    // Check if the curl_exec() failed
    if ($response === false) {
        $errorInfo = curl_error($ch);
        error_log("Failed to execute cURL: $errorInfo");
        curl_close($ch);
        return null;
    }
    
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpcode != 200) {
        error_log("Failed to get file from GitHub. HTTP Code: $httpcode");
        return null;
    }
    
    return $response;
}

function uploadToGithub($owner, $repo, $filePath, $content, $token) {
    $filePath = urlencode($filePath);
    $api_url = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
    
    $data = [
        "message" => "Add file",
        "content" => base64_encode($content)
    ];

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: PHP",
        "Authorization: token $token",
        "Content-Type: application/json",
        "Accept: application/vnd.github.v3+json"
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode >= 400 || $response === false) {
        return "Something went wrong while uploading to GitHub. HTTP Code: $httpcode";
    }

    // Check the response for success
    $responseData = json_decode($response, true);
    if (isset($responseData['content'])) {
        return "File uploaded successfully.";
    } else {
        return "Failed to upload file to GitHub. Response: " . $response;
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


function handlePhotoUpload($fileInfo, $github_owner, $github_repo, $github_token, $vendor_name) {
    $paths = [];
    $displayPaths = [];
    
    if ($fileInfo['error'] == 0) {
        $file_name = basename($fileInfo['name']); // Use basename for security
        $tmp_name = $fileInfo['tmp_name'];
        
        $display_path = "/vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;
        $path = "vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;
        
        $content = file_get_contents($tmp_name);
        uploadToGithub($github_owner, $github_repo, $path, $content, $github_token);
        
        $paths[] = $path;
        $displayPaths[] = $display_path;
    }
    
    return [$paths, $displayPaths];
}

// Main photo
list($main_photo_paths, $main_photo_display_paths) = handlePhotoUpload($_FILES['main_photo'], $github_owner, $github_repo, $github_token, $vendor_name);
$main_photo_path = $main_photo_paths[0] ?? '';
$main_photo_display_path = $main_photo_display_paths[0] ?? '';

// Another photo
$other_photos_paths = [];
$other_photos_display_paths = [];

if (isset($_FILES['another_picture'])) {
    foreach ($_FILES['another_picture']['error'] as $key => $error) {
        if ($error == 0) {
            $fileInfo = [
                'name' => $_FILES['another_picture']['name'][$key],
                'tmp_name' => $_FILES['another_picture']['tmp_name'][$key],
                'error' => $error
            ];
            list($paths, $displayPaths) = handlePhotoUpload($fileInfo, $github_owner, $github_repo, $github_token, $vendor_name);
            $other_photos_paths = array_merge($other_photos_paths, $paths);
            $other_photos_display_paths = array_merge($other_photos_display_paths, $displayPaths);
        }
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

uploadToGithub($github_owner, $github_repo, $vendor_page_path, $html_template, $github_token);


$sql = "INSERT INTO vendorpages (vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssss", $vendor_name, $food_types_string, $address, $phone_number, $opening_hours_serialized, $dining_option, $service_options_string, $main_photo_path, $other_photos_names_json, $menu_food_names_json, $menu_food_prices_json, $menu_img_paths_json, $status);

if ($stmt->execute()) {
    $vendorpage_id = $conn->insert_id;
    
    function handleFileFromGithub($owner, $repo, $token, $filePath) {
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $content = getFileFromGithub($owner, $repo, $filePath, $token);
        
        if ($content === null) {
            error_log("Failed to get content for $filePath");
            return false;
        }
        
        error_log("Received content for $filePath: " . substr($content, 0, 100));  // Print first 100 chars
        return $content;
    }
    
    $filePaths = [
        'index.html',
        'user/user_account.php',
        'vendor_acc/vendor_account.php',
        'admin/admin.php'
    ];
    
    foreach ($filePaths as $filePath) {
        $content = handleFileFromGithub($github_owner, $github_repo, $github_token, $filePath);
        if ($content === false) {
            continue;  // Skip this iteration if fetching failed
        }
        
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
    
        // Assume you have the new vendor HTML content in $newVendorHTML
        if ($content !== null && $filePath !== null) {
            $updatedContent = str_replace(
            '<!-- New vendors will be added below -->',
            "<!-- New vendors will be added below -->\n" . $newVendorHTML,
            $content
        );
        
        if ($filePath !== null) {
            file_put_contents($filePath, $updatedContent);
        } else {
            error_log("filePath is null");
        }
        
        uploadToGithub($github_owner, $github_repo, $filePath, $updatedContent, $github_token);
    } else {
        error_log("Either content or filePath is null");
    }
} 
}else {
    die("Error: " . $stmt->error);
}

$stmt->close();
header("Location: /vendorpage/{$vendor_name}.html ");
?>