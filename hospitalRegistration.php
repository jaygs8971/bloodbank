<?php
require 'connection.php';

$h_name = '';
$email = '';
$p_no = '';
$username = '';
$password = '';
$conf_password = '';
$city = '';
$state = '';

if (isset($_POST['register'])) {
    $h_name = $_POST['hospitalName'];
    $email = $_POST['email'];
    $p_no = $_POST['phoneNumber'];
    $username = $_POST['username'];
    $password = ($_POST['password']);
    $conf_password = ($_POST['confirmPassword']);
    $city = $_POST['city'];
    $state = $_POST['state'];

    $flag = 0;


    if (ctype_space($h_name)) {
        $name_error = "Invalid hospital name provided!";
        $flag = 1;
    }

    if (ctype_space($username)) {
        $username_error = "Invalid username provided!";
        $flag = 1;
    }

    if ($password != $conf_password) {
        $pwd_error = "Passwords do not match!";
        $flag = 1;
    }

    global $conn;

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM hospital WHERE username ='$username'")) > 0) {
        $username_error = "Username already exists!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'")) > 0) {
        $username_error = "Username already exists!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM hospital WHERE email = '$email'")) > 0) {
        $email_error = "E-mail already exits!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM hospital WHERE p_number = '$p_no'")) > 0) {
        $number_error = "Phone number already exits!";
        $flag = 1;
    }

    if ($flag == 0) {
        $date = date('Y-m-d H:i:s');
        $pwd = md5($password);

        if (mysqli_query($conn, "INSERT INTO hospital (h_name, username, password, p_number, email, city , h_state, created_at) VALUES 
            ('$h_name', '$username','$pwd    ','$p_no','$email','$city','$state','$date')")) {
            $h_name = '';
            $email = '';
            $p_no = '';
            $username = '';
            $password = '';
            $conf_password = '';
            $city = '';
            $state = '';
            $success = true;

        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hospital Registration | BB</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Custom styles for this page -->
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #ffffff !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand abs" href="#"><i class="bi bi-droplet-half"></i> &nbsp;Blood Bank</a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNavbar">
            <span class="navbar-toggler-icon" style="margin: 5px"></span>
        </button>
        <div class="navbar-collapse collapse" id="collapseNavbar">
            <ul class="navbar-nav  ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        Login
                    </a>
                </li>
                <li class="nav-item dropdown ">
                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Register
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item " href="userRegistration.php"><i
                                        class="bi bi-person-plus"></i>&nbsp; User / Receiver</a></li>
                        <li><a class="dropdown-item active" href="hospitalRegistration.php"><i class="bi bi-bank2"></i>&nbsp;
                                Hospital</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid register-outer">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <div style="text-align: center;"><img src="images/img2.jpg" alt="img" class="img-fluid" height="600px"
                                                  width="600px">
            </div>
        </div>
        <div class="col-lg-6 padding-outer">
            <div class="align-items-center flex-column justify-content-center">
                <?php if (isset($success)) : ?>
                    <p class="alert alert-success mb-4">Registration successful. Click <a href="login.php"
                                                                                          class="alert-link">here</a>
                        to login.</p>
                <?php endif; ?>
                <form method="post" action="hospitalRegistration.php">
                    <h3 class="h4 mb-4 text-danger my-form-name">Hospital Registration</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hospitalName">Hospital Name</label>
                            <input type="text" id="hospitalName" class="form-control my-inp" name="hospitalName"
                                   maxlength="50"
                                   minlength="3"
                                   pattern="[A-Za-z0-9 ]+"
                                   required
                                   style="text-transform: capitalize;"
                                   value="<?php echo $h_name ?>">
                            <?php if (isset($name_error)) : ?>
                                <div class="mt-2">
                        <span>
                            <?php echo $name_error; ?> </span></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" maxlength="20"
                                   required
                                   pattern="[A-Za-z0-9]+"
                                   minlength="4"
                                   value="<?php echo $username ?>">
                            <?php if (isset($username_error)) : ?>
                                <div class="mt-2">
                        <span>
                            <?php echo $username_error; ?> </span></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo $email ?>"
                                   required
                                   maxlength="30">
                            <?php if (isset($email_error)) : ?>
                                <div class="mt-2">

                        <span>
                            <?php echo $email_error; ?> </span></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phoneNumber">Phone number</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" maxlength="11"
                                   required
                                   pattern="\d*"
                                   value="<?php echo $p_no ?>">
                            <?php if (isset($number_error)) : ?>
                                <div class="mt-2">

                        <span>
                            <?php echo $number_error; ?> </span></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" maxlength="20"
                                   required value="<?php echo $password ?>"
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                   oninvalid="this.setCustomValidity('Password should contain minimum 8 characters with at least one special character, one upper case letter, one lower case letter and a number!')"
                                   onchange="this.setCustomValidity('')"
                            >
                            <?php if (isset($pwd_error)) : ?>
                                <div class="mt-2">

                        <span>
                            <?php echo $pwd_error; ?> </span></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                   required value="<?php echo $conf_password ?>"
                                   maxlength="20">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $city ?>"
                                   required
                                   minlength="3"
                                   pattern="[A-Za-z]+"
                                   maxlength="20">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="state">State</label>
                            <select id="state" class="form-control" name="state" required>
                                <!--                        <option>Choose...</option>-->
                                <?php if ($state == "Karnataka") { ?>
                                    <option value="Karnataka" selected>Karnataka</option>
                                <?php } else { ?>
                                    <option value="Karnataka">Karnataka</option>
                                <?php } ?>

                                <?php if ($state == "Tamil Nadu") { ?>
                                    <option value="Tamil Nadu" selected>Tamil Nadu</option>
                                <?php } else { ?>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                <?php } ?>

                                <?php if ($state == "Kerala") { ?>
                                    <option value="Kerala" selected>Kerala</option>
                                <?php } else { ?>
                                    <option value="Kerala">Kerala</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-outline-danger" name="register"><i class="bi bi-bank2"></i>
                            Register
                        </button>
                    </div>
                </form>
                <div class="mt-2">
                    Want to register as a User? <a href="userRegistration.php" style="text-decoration: none">Click
                        here!</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="my-4 p-2 text-muted text-center text-small">
    <p class="mb-1">&copy; 2021 Blood Bank. By Jayaram G S</p>
</footer>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
