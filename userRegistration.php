<?php
require 'connection.php';

$f_name = '';
$l_name = '';
$email = '';
$p_no = '';
$b_group = '';
$username = '';
$password = '';
$conf_password = '';
$city = '';
$state = '';

if (isset($_POST['register'])) {
    $f_name = $_POST['firstName'];
    $l_name = $_POST['lastName'];
    $email = $_POST['email'];
    $p_no = $_POST['phoneNumber'];
    $b_group = $_POST['bloodGroup'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conf_password = $_POST['confirmPassword'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    $flag = 0;

    if ($password != $conf_password) {
        $pwd_error = "Passwords do not match!";
        $flag = 1;
    }

    global $conn;

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'")) > 0) {
        $username_error = "Username already exists!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM hospital WHERE username ='$username'")) > 0) {
        $username_error = "Username already exists!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'")) > 0) {
        $email_error = "E-mail already exits!";
        $flag = 1;
    }

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE p_number = '$p_no'")) > 0) {
        $number_error = "Phone number already exits!";
        $flag = 1;
    }

    if ($flag == 0) {
        $pwd = md5($password);
        $date = date('Y-m-d H:i:s');
        $sql1 = mysqli_query($conn, "SELECT blood_id FROM blood WHERE b_group = '$b_group'");
        $res1 = mysqli_fetch_assoc($sql1);


        if (mysqli_query($conn, "INSERT INTO user (f_name, l_name, username, password, p_number, email, city , u_state, created_at,status, blood_id ) VALUES 
            ('$f_name','$l_name', '$username','$pwd','$p_no','$email','$city','$state','$date','user' ,'$res1[blood_id]' )")) {
            $f_name = '';
            $l_name = '';
            $email = '';
            $p_no = '';
            $b_group = '';
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
    <title>User Registration | BB</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Custom styles for this page -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
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
                        <li><a class="dropdown-item active" href="userRegistration.php"><i
                                        class="bi bi-person-plus"></i>&nbsp; User / Receiver</a></li>
                        <li><a class="dropdown-item" href="hospitalRegistration.php"><i class="bi bi-bank2"></i>&nbsp;
                                Hospital</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid register-outer user-reg reg-image">
    <div class="row align-items-center ">
        <div class="col-lg-6 padding-outer">
            <div class="align-items-center flex-column justify-content-center">
                <?php if (isset($success)) : ?>
                    <p class="alert alert-success">Registration successful. Click <a href="login.php"
                                                                                     class="alert-link">here</a>
                        to login.</p>
                <?php endif; ?>

                <form action="userRegistration.php" method="post">
                    <h3 class="h4 mb-4 text-danger my-form-name">User Registration</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" class="form-control" name="firstName" maxlength="20"
                                   minlength="3"
                                   pattern="[A-Za-z]+"
                                   value="<?php echo $f_name ?>"
                                   required
                                   style="text-transform: capitalize;"
                            >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" class="form-control" name="lastName" required
                                   value="<?php echo $l_name ?>"
                                   maxlength="20"
                                   pattern="[A-Za-z]+" style="text-transform: capitalize;"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?php echo $email ?>"
                                   maxlength="80">
                            <?php if (isset($email_error)) : ?>
                                <div class="mt-2">
                        <span>
                            <?php echo $email_error; ?> </span></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phoneNumber">Phone number</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" pattern="\d*"
                                   required
                                   minlength="10"
                                   maxlength="10"
                                   value="<?php echo $p_no ?>">
                            <?php if (isset($number_error)) : ?>
                                <div class="mt-2">

                        <span>
                            <?php echo $number_error; ?> </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="bloodGroup">Blood Group</label>
                            <select id="bloodGroup" class="form-control" name="bloodGroup" required>
                                <option selected><?php echo $b_group ?></option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="Username">Username</label>
                            <input type="text" class="form-control my-inp" id="Username" name="username" maxlength="40"
                                   required
                                   minlength="4"
                                   pattern="[A-Za-z0-9]{4,40}"
                                   oninvalid="this.setCustomValidity('Username should be a minimum of four characters and can consist of alphabets and numbers only!')"
                                   onchange="this.setCustomValidity('')"
                                   value="<?php echo $username ?>">

                            <?php if (isset($username_error)) : ?>
                                <div class="mt-2">
                        <span>
                            <?php echo $username_error; ?> </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control my-inp" id="password" name="password" required
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                   oninvalid="this.setCustomValidity('Password should contain minimum 8 characters with at least one special character, one upper case letter, one lower case letter and a number!')"
                                   onchange="this.setCustomValidity('')"
                                   maxlength="20" value="<?php echo $password; ?>">
                            <label for="showPassword" hidden>Show Password</label>
                            <input class="mt-2" type="checkbox" id="showPassword" onclick="myFunction()">&nbsp;Show Password

                            <?php if (isset($pwd_error)) : ?>
                                <div class="mt-2">
                        <span>
                            <?php echo $pwd_error; ?> </span></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control my-inp" id="confirmPassword" required
                                   name="confirmPassword" value="<?php echo $conf_password; ?>"
                                   maxlength="20" minlength="8">
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $city ?>"
                                   minlength="3"
                                   pattern="[A-Za-z]+"
                                   maxlength="20"
                                   required>
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
                                <!--                        <option selected>--><?php //echo $state ?><!--</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button name="register" type="submit" class="btn btn-outline-danger mb-1"><i
                                    class="bi bi-person-plus "></i>&nbsp;
                            Register
                        </button>
                    </div>
                </form>
                <div class="mt-2">
                    Want to register as a Hospital? <a href="hospitalRegistration.php" style="text-decoration: none">Click
                        here!</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div style="text-align: center;"><img src="images/img1.jpg" alt="img" class="img-fluid"
                                                  height="600"
                                                  width="600">
            </div>
        </div>
    </div>
</div>

<footer class="my-4 p-2 text-muted text-center text-small">
    <p class="mb-1">&copy; 2021 Blood Bank. By Jayaram G S</p>
</footer>


<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
