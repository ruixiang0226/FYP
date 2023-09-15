<?php
// Database Connection & Initialization
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

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

// Simplified GitHub upload function
function uploadFileToGithub($owner, $repo, $filePath, $fileTmpPath, $token) {
    $content = file_get_contents($fileTmpPath);
    uploadToGithub($owner, $repo, $filePath, $content, $token);
}

// Data Collection & Preprocessing
$vendor_name = $_POST['vendor_name'];
$food_types_string = implode(', ', $_POST['food_type'] ?? []);
$google_maps_api_key = 'Your_API_Key';
$address_encoded = urlencode($_POST['address']);
$phone_number = $_POST['phone_number'];
$dining_option = $_POST['dining_option'] ?? 'Default Value';
$service_options_string = implode(' / ', $_POST['service_option'] ?? []);

// Begin transaction
$conn->begin_transaction();

try {
    // Generate map embed code using address
    $map_embed_code = "<iframe width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" src=\"https://www.google.com/maps/embed/v1/place?key={$google_maps_api_key}&q={$address_encoded}&zoom=19\" allowfullscreen></iframe>";
    
    // Function to parse time
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
    
    // Function to generate opening hours HTML
    function generateOpeningHoursHtml($day, $times) {
        $icon = '<div class="icon"><span class="uil uil-calender"></span></div>';
        return $times['open'] === 'Closed' ? 
        "<li><a>{$icon}" . ucfirst($day) . " - Closed</a></li>" : 
        "<li><a>{$icon}" . ucfirst($day) . " {$times['open']}-{$times['close']}</a></li>";
    }
    
    // Parsing opening hours and generating HTML
    $opening_hours = [];
    $opening_hours_html = '';
    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
        $open_time = parseTime($_POST["opening_hours_{$day}_open"] ?? '');
        $close_time = parseTime($_POST["opening_hours_{$day}_close"] ?? '');
        $opening_hours[$day] = ['open' => $open_time, 'close' => $close_time];
        $opening_hours_html .= generateOpeningHoursHtml($day, $opening_hours[$day]);
    }
    
    // Generate opening hours JSON
    $opening_hours_json = json_encode($opening_hours);

    
    // Code for file uploads, HTML generation, and database updates

    // Function to handle photo upload and return paths
    function handlePhotoUpload($file, $vendor_name, $github_owner, $github_repo, $github_token) {
        $path = '';
        $display_path = '';
        if ($file['error'] == 0) {
            $file_name = $file['name'];
            $tmp_name = $file['tmp_name'];
            $path = "vendorpage/img_vendor/vendorpage_$vendor_name/vendor_img/" . $file_name;
            $display_path = "/$path";
            $content = file_get_contents($tmp_name);
            uploadToGithub($github_owner, $github_repo, $path, $content, $github_token);
        }
        return [$path, $display_path];
    }
    
    // Function to generate image slider HTML
    function generateImageSliderHtml($display_paths) {
        $html = '';
        foreach ($display_paths as $path) {
            $html .= '<li class="splide__slide"><img src="' . $path . '" alt=""></li>';
        }
        return $html;
    }
    
    // Handle main photo
    list($main_photo_path, $main_photo_display_path) = handlePhotoUpload($_FILES['main_photo'], $vendor_name, $github_owner, $github_repo, $github_token);
    
    // Handle other photos
    $other_photos_paths = $other_photos_display_paths = [];
    if (isset($_FILES['another_picture'])) {
        foreach ($_FILES['another_picture']['tmp_name'] as $key => $tmp_name) {
            list($path, $display_path) = handlePhotoUpload($_FILES['another_picture'][$key], $vendor_name, $github_owner, $github_repo, $github_token);
            $other_photos_paths[] = $path;
            $other_photos_display_paths[] = $display_path;
        }
    }
    
    // Generate image slider HTML
    $image_slider_html = generateImageSliderHtml(array_merge([$main_photo_display_path], $other_photos_display_paths));

    // Function to handle single menu item
    function handleMenuItem($food_name, $food_price, $menu_img, $vendor_name, $github_owner, $github_repo, $github_token) {
        $menu_img_path = '';
        $menu_display = '';
        
        if (isset($menu_img['name']) && $menu_img['error'] == 0) {
            $file_name = $menu_img['name'];
            $tmp_name = $menu_img['tmp_name'];
            
            $menu_img_path = "vendorpage/img_vendor/vendorpage_$vendor_name/menu_img/" . $file_name;
            $menu_display = "/$menu_img_path";
            
            $menu_img_content = file_get_contents($tmp_name);
            uploadToGithub($github_owner, $github_repo, $menu_img_path, $menu_img_content, $github_token);
        }
        
        $menu_item_html = '<div class="menu_box"><div class="menu_detail">';
        $menu_item_html .= '<p class="not">Food Name: ' . htmlspecialchars($food_name) . '</p>';
        $menu_item_html .= '<p class="not">Food Price: ' . htmlspecialchars($food_price) . '</p>';
        $menu_item_html .= '<div class="menu_img"><img src="' . htmlspecialchars($menu_display) . '" alt=""></div>';
        $menu_item_html .= '</div></div>';
        
        return [$menu_item_html, $menu_img_path];
    }
    
    // Handle menu items
    $menu_html_array = [];
    $menu_img_paths = [];
    
    foreach ($_POST['food_name'] ?? [] as $index => $food_name) {
        $food_price = $_POST['food_price'][$index] ?? '';
        $menu_img = $_FILES['menu_img'][$index] ?? null;
        
        if (empty($food_name) || empty($food_price) || ($menu_img && $menu_img['error'] !== 0)) {
            continue;
        }

        list($menu_item_html, $menu_img_path) = handleMenuItem($food_name, $food_price, $menu_img, $vendor_name, $github_owner, $github_repo, $github_token);
        $menu_html_array[] = $menu_item_html;
        $menu_img_paths[] = $menu_img_path;
    }
    
    $menu_html = implode('', $menu_html_array);
    $menu_img_paths_json = json_encode($menu_img_paths);

    // Function to process HTML template
    function processTemplate($template_path, $replacements, $output_path, $github_owner, $github_repo, $github_token) {

        // Fetch the template
        $html_template = getFileFromGithub($github_owner, $github_repo, $template_path, $github_token);
        
        // Replace placeholders with actual data
        foreach ($replacements as $placeholder => $value) {
            $html_template = str_replace($placeholder, htmlspecialchars($value, ENT_QUOTES), $html_template);
        }
        
        // Save the new HTML file
        if (file_put_contents($output_path, $html_template) === false) {
            die("Error writing new vendor page");
        }
        
        // Upload HTML to GitHub
        uploadToGithub($github_owner, $github_repo, $output_path, $html_template, $github_token);
    }
    
    // Prepare the replacements
    $replacements = [
        '{{vendor_name}}' => $vendor_name,
        '{{food_type}}' => $food_types_string,
        '{{address}}' => $address,
        '{{map}}' => $map_embed_code,
        '{{phone_number}}' => $phone_number,
        '{{dining_option}}' => $dining_option,
        '{{open_hours}}' => $opening_hours_html,
        '{{opening_hours_json}}' => $opening_hours_json,
        '{{service_option}}' => $service_options_string,  
        '{{main_img}}' => $image_slider_html,  
        '{{thumb_img}}' => $image_slider_html,  
        '{{menu}}' => $menu_html
    ];
    
    $vendor_page_path = "vendorpage/{$vendor_name}.html";
    
    // Ensure the directory exists
    if (!is_dir('vendorpage')) {
        mkdir('vendorpage', 0777, true);
    }
    
    // Process the template
    processTemplate("vendorpage/vendorpage.html", $replacements, $vendor_page_path, $github_owner, $github_repo, $github_token);

    // Function to generate new vendor HTML
    function generateNewVendorHTML($vendorpage_id, $vendor_name, $main_photo_display_path, $dining_option, $food_types_string) {
        $html = '<li class="vendor" id="vendorpage_' . $vendorpage_id . '" data-rating="" data-stars="">';
        $html .= '<a class="vendorpage_link" href="/vendorpage/' . $vendor_name . '.html">';
        $html .= '<div class="vendor_wrapper">';
        $html .= '<div class="vendor_img"><img src="' . $main_photo_display_path . '"></div>';
        $html .= '<div class="vendor_info">';
        $html .= '<h2>' . htmlspecialchars($vendor_name) . '</h2>';
        $html .= '<span class="ratings_component" id="vendor_ratings">';
        $html .= '<div class="vendor_info_ratings">';
        $html .= '<div class="rating">';
        $html .= '<div class="star-container" id="star-container"></div>';
        $html .= '<span class="rating_label_primary">' . '/5</span>';
        $html .= '<span class="rating_label_secondary">(' . ')</span>';
        $html .= '</div>';
        $html .= '<p>' . htmlspecialchars($dining_option) . '</p>';
        $html .= '</div></span>';
        $html .= '<div class="food_type"><p>' . htmlspecialchars($food_types_string) . '</p></div>';
        $html .= '</div></div></a></li>';
        return $html;
    }
    
    // Function to update and upload file
    function updateAndUploadFile($filepath, $newContent, $github_owner, $github_repo, $github_token) {
        $content = file_get_contents($filepath);
        $updatedContent = str_replace('<!-- New vendors will be added below -->', "<!-- New vendors will be added below -->\n" . $newContent, $content);
        file_put_contents($filepath, $updatedContent);
        uploadToGithub($github_owner, $github_repo, $filepath, $updatedContent, $github_token);
    }
    
    $vendorpage_id = $conn->insert_id;
    $filePaths = ['index.html', 'user/user_account.php', 'vendor_acc/vendor_account.php', 'admin/admin.php'];
    $newVendorHTML = generateNewVendorHTML($vendorpage_id, $vendor_name, $main_photo_display_path, $dining_option, $food_types_string);
    
    foreach ($filePaths as $filepath) {
        updateAndUploadFile($filepath, $newVendorHTML, $github_owner, $github_repo, $github_token);
    }

    $opening_hours_serialized = serialize($opening_hours);
    $status = 'approved';

    // Database Update
    $sql = "INSERT INTO vendorpages (vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $vendor_name, $food_types_string, $address, $phone_number, $opening_hours_serialized, $dining_option, $service_options_string, $main_photo_path, $other_photos_names_json, $menu_food_names_json, $menu_food_prices_json, $menu_img_paths_json, $status);
    if (!$stmt->execute()) throw new Exception("Database error: " . $stmt->error);

    // Commit transaction
    $conn->commit();

    // Redirect to the new page
    header("Location: /vendorpage/{$vendor_name}.html ");
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    die("Error: " . $e->getMessage());
}
?>