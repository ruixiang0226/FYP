<?php
include '/config.php';

// detect vendor id
$vendor_id = $_GET['vendor_id'] ?? null;

if ($vendor_id !== null) {
    $sql = "SELECT * FROM vendorpages WHERE vendor_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }
    
    $bind = $stmt->bind_param("s", $vendor_id);
    if (!$bind) {
        die("Bind failed: " . $stmt->error);
    }
    
    $exec = $stmt->execute();
    if (!$exec) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("You have already filled out the form.");
    }
} else {
    die("Unauthorized user.");
}

$vendor_name = $_POST['vendor_name'];
$food_types = $_POST['food_type'] ?? [];
$food_types_string = implode(', ', $food_types);
$address = $_POST['address'];
$phone_number = $_POST['phone_number'];
$dining_option = $_POST['dining_option'] ?? 'Default Value';

$selected_services = $_POST['service_option'] ?? [];
$service_options_string = implode(' / ', $selected_services);

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
$opening_hours_serialized = serialize($opening_hours);

$upload_img = "C:/xampp/htdocs/FYP/vendorpage/img_database/";

// main photo
$main_photo_name = '';
if ($_FILES['main_photo']['error'] == 0) {
    $main_photo_name = $_FILES['main_photo']['name'];
    $main_photo_tmp_name = $_FILES['main_photo']['tmp_name'];

    $upload_img_file = $upload_img . $main_photo_name;
    move_uploaded_file($main_photo_tmp_name, $upload_img_file);
}

// other photo
$other_photos_names = [];
if (isset($_FILES['another_picture'])) {
    foreach ($_FILES['another_picture']['name'] as $index => $name) {
        $other_photos_names[] = $name;
        $other_photo_tmp_name = $_FILES['another_picture']['tmp_name'][$index];

        $upload_img_file = $upload_img . $name;
        move_uploaded_file($other_photo_tmp_name, $upload_img_file);
    }
}
$other_photos_names_str = implode(", ", $other_photos_names);
$other_photos_names_json = json_encode($other_photos_names);



$menu_food_names = $_POST['food_name'] ?? [];
$menu_food_prices = $_POST['food_price'] ?? [];
$menu_food_names_str = implode(", ", $menu_food_names);
$menu_food_prices_str = implode(", ", $menu_food_prices);
$menu_food_names_json = json_encode($menu_food_names);
$menu_food_prices_json = json_encode($menu_food_prices);

$menu_img_names = [];
if (isset($_FILES['menu_img']['name'])) {
    foreach ($_FILES['menu_img']['name'] as $index => $name) {
        if ($_FILES['menu_img']['error'][$index] == 0) {
            $menu_img_names[] = $name;
            $menu_img_tmp_name = $_FILES['menu_img']['tmp_name'][$index];
            
            $upload_img_file = $upload_img . $name;
            move_uploaded_file($menu_img_tmp_name, $upload_img_file);
        }
    }
}
$menu_img_paths_str = implode(", ", $menu_img_names);
$menu_img_paths_json = json_encode($menu_img_names);

$status = 'pending';

$sql = "INSERT INTO vendorpages (vendor_id, vendor_name, food_type, address, phone_number, opening_hours, dining_option, service_option, main_photo_path, other_photos_paths, food_name, food_price, menu_img_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssss", $vendor_id, $vendor_name, $food_types_string, $address, $phone_number, $opening_hours_serialized, $dining_option, $service_options_string, $main_photo_name, $other_photos_names_json, $menu_food_names_json, $menu_food_prices_json, $menu_img_paths_json , $status);

if ($stmt->execute()) {
    header("Location: /vendor/waiting_room.php");
} else {
    die("Error: " . $stmt->error);
}

$stmt->close();
?>