<?php
$server = "localhost";
$dbname = "bloodbank";
$username = "root";
$password = "";

$conn = new mysqli($server, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
global $conn;
date_default_timezone_set("Asia/Calcutta");

echo '<script> console.log("Database Connected! :)") </script>';