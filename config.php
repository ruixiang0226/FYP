<?php

$hostname = 'ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
$username = 'wdzd5d37qxl2zori';
$password = 'gnvgq0h5y6vmdhqr';
$database = 'p40t91itwyub22ct';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully";
?>
