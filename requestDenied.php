<?php
session_start();
require 'connection.php';
global $conn;

if (!isset($_SESSION['user_id']) || !($_SESSION['user'] == "admin")) {
    header("location:login.php");
} else {

    $id = $_GET['id'];
    $date = date('Y-m-d H:i:s');

    if (mysqli_query($conn, "UPDATE request SET status = 2 WHERE request_id = '$id'")) {
        $_SESSION['approve_failed'] = true;
    }
    header("location:dashboard.php");
}
