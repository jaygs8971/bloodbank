<?php
session_start();
require 'connection.php';
$volume = '';

if (isset($_POST['add_blood'])) {
    $volume = $_POST['volume'];
    $b_group = $_POST['bloodGroup'];

    global $conn;
    $sql1 = mysqli_query($conn, "SELECT blood_id FROM blood WHERE b_group = '$b_group'");
    $res1 = mysqli_fetch_assoc($sql1);

    $sql2 = mysqli_query($conn, "SELECT * FROM hospital WHERE username = '$_SESSION[username]'");
    $res2 = mysqli_fetch_assoc($sql2);

    if (mysqli_query($conn, "INSERT INTO stock (volume, status, hospital_id, blood_id) VALUES ('$volume', '1','$res2[hospital_id]', '$res1[blood_id]' )")) {
        $blood_added = true;
        header("location:dashboard.php");
    }
}