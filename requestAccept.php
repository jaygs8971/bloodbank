<?php
session_start();
require 'connection.php';
global $conn;

if (!isset($_SESSION['user_id']) || !($_SESSION['user'] == "admin")) {
    header("location:login.php");
} else {

    $id = $_GET['id'];
    $date = date('Y-m-d H:i:s');

    $qry1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM request WHERE request_id = '$id'"));

    $qry2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM stock WHERE hospital_id = '$qry1[hospital_id]' AND blood_id = $qry1[blood_id] "));

    $req_vol = $qry1['volume'];
    $ava_vol = $qry2['volume'];

    if ($req_vol > $ava_vol) {
        $_SESSION['stock_error'] = true;
        $_SESSION['approve_success'] = false;

    } else {
        if ((mysqli_query($conn, "UPDATE request SET status = 1 , updated_at = CURRENT_TIMESTAMP WHERE request_id = '$id'")) &&
            (mysqli_query($conn, "UPDATE stock SET volume = (volume - '$req_vol'), updated_at = CURRENT_TIMESTAMP WHERE blood_id = '$qry1[blood_id]' AND hospital_id = '$_SESSION[user_id]'"))) {
            $_SESSION['approve_success'] = true;
            $_SESSION['stock_error'] = false;

        }
    }
    header("location:dashboard.php");
}