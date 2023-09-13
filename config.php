<?php
// Create connection conn
$conn = new mysqli("ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "wdzd5d37qxl2zori", "gnvgq0h5y6vmdhqr", "p40t91itwyub22ct");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>