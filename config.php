<?php
$db_url = getenv('mysql://wdzd5d37qxl2zori:gnvgq0h5y6vmdhqr@ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com:3306/p40t91itwyub22ct');
$db_parts = parse_url($db_url);

$hostname = $db_parts['ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com'];
$username = $db_parts['wdzd5d37qxl2zori'];
$password = $db_parts['gnvgq0h5y6vmdhqr'];
$database = ltrim($db_parts['p40t91itwyub22ct'], '/');

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully";
?>
