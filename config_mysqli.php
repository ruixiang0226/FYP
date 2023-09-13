<?php
// Create connection mysqli
$mysqli = new mysqli("ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "wdzd5d37qxl2zori", "gnvgq0h5y6vmdhqr", "p40t91itwyub22ct");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}