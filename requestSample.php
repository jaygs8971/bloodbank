<?php
require 'connection.php';
session_start();
global $conn;
if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit();
}
if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] == "admin") {
        header("location:dashboard.php");
        exit();
    }
}
if (isset($_POST['request'])) {
    $stock_id = $_POST['stock_id'];
    $sql1 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM stock WHERE stock_id = '$stock_id'"));
    $sql2 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM hospital WHERE hospital_id = '$sql1[hospital_id]'"));
    $sql3 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$sql1[blood_id]'"));
    $sql4 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE username= '$_SESSION[username]'"));
}

if (isset($_POST['confirmRequest'])) {

    $stock_id = $_POST['stock_id'];
    $sql1 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM stock WHERE stock_id = '$stock_id'"));
    $sql2 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM hospital WHERE hospital_id = '$sql1[hospital_id]'"));
    $sql3 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$sql1[blood_id]'"));
    $sql4 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE username= '$_SESSION[username]'"));

    $volume = $_POST['volumeRequired'];
    $date = date('Y-m-d H:i:s');

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM request WHERE user_id = '$_SESSION[user_id]' AND blood_id = '$sql3[blood_id]' AND hospital_id = '$sql2[hospital_id]' AND status = 0 ")) != 0) {
        $exists_error = true;
    } else {
        if (mysqli_query($conn, "INSERT INTO request (user_id, blood_id,hospital_id, volume, status) 
    VALUES ('$sql4[user_id]', '$sql1[blood_id]','$sql1[hospital_id]','$volume','0') ")) {
            $request_success = true;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Sample | Blood Bank</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand abs" href="#">Blood Bank</a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="collapseNavbar">
            <ul class="navbar-nav  ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php
                if (isset($_SESSION['user'])): {
                    if ($_SESSION['user'] == 'user') {
                        ?>
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Hi, <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link " href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <span style="text-transform: capitalize">Welcome, <?php echo $_SESSION['username']; ?> </span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                        <?php
                    }

                    ?>
                <?php } else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            Login
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Register
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="userRegistration.php">User / Receiver</a></li>
                            <li><a class="dropdown-item" href="hospitalRegistration.php">Hospital</a></li>
                        </ul>
                    </li>
                <?php
                endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container col-lg-6 padding-outer">
    <?php if (isset($request_success)) : ?>
        <p class="alert alert-info"><i class="bi bi-info-circle"></i>&nbsp; Sample requested. We will get back to you
                shortly!</i></p>
    <?php endif; ?>

    <div class="align-items-center flex-column justify-content-center">
        <?php if (isset($exists_error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show " role="alert"><i class="bi bi-exclamation-circle">
                </i> &nbsp; Cannot request for another sample. Request already exits.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="bi bi-x" style="font-size: 24px"></i></span>
                    </button>
            </div>
        <?php endif; ?>
        <form class="my-class col-md-12 padding-inner" action="requestSample.php" method="post">
            <span><a href="index.php" class="btn-sm btn btn-outline-secondary"><i
                            class="bi bi-chevron-left"></i> Go Back</a></span>
            <input type="hidden" value="<?php echo $stock_id; ?>" name="stock_id">
            <h3 class="h5 mb-4 mt-4 text-danger my-form-name">Request Blood Sample</h3>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">Hospital Name</label>
                    <input type="text" readonly id="firstName" class="form-control" name="firstName"
                           value="<?php
                           echo $sql2['h_name'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Blood Group</label>
                    <input type="text" id="lastName" class="form-control" name="lastName" value="<?php

                    echo $sql3['b_group'] ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo $sql2['email'] ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="phoneNumber">Phone number</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                           value="<?php echo $sql2['p_number'] ?> " readonly>
                </div>
            </div>

            <div class="form-row ">
                <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo $sql2['city'] ?>"
                           readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="state">State</label>
                    <input type="text" class="form-control" id="state" name="city"
                           value="<?php echo $sql2['h_state'] ?>" readonly>
                </div>
            </div>
            <hr/>

            <div class="form-row mt-4">
                <div class="form-group col-md-6">
                    <label for="volumeRequired" hidden></label>
                    <input type="text" class="form-control" id="volumeRequired" name="volumeRequired" pattern="\d*"
                           maxlength="3"
                           required placeholder="Enter the volume (ml) required">
                </div>
                <div class="form-group col-md-6">
                    <button name="confirmRequest" type="submit" class="btn btn-outline-danger"><i
                                class="bi bi-arrow-up-circle"></i> Request Sample
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
<footer class="my-4 p-2 text-muted text-center text-small">
    <p class="mb-1">&copy; 2021 Blood Bank. By Jayaram G S</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
