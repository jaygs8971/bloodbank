<?php
require 'connection.php';
session_start();

$username = '';
$password = '';
$pwd = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $pwd = $_POST['password'];

    global $conn;
    $sql = "SELECT * FROM user WHERE username = '$username' and password = '$password'";
    $sql2 = "SELECT * FROM hospital WHERE username = '$username' and password = '$password'";

    if (mysqli_num_rows($row = mysqli_query($conn, $sql)) == 1) {
        $res = mysqli_fetch_array($row);
        $_SESSION['user'] = "user";
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $res['user_id'];
        header("location: index.php");

    } else if (mysqli_num_rows($row = mysqli_query($conn, $sql2)) == 1) {
        $res = mysqli_fetch_assoc($row);
        $_SESSION['user'] = "admin";
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $res['hospital_id'];
        header("location: dashboard.php");
    } else {
        $login_error = "Invalid Username or Password. Try again!";
        session_abort();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | BB</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Custom styles for this page -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand abs" href="#"><i class="bi bi-droplet-half"></i> &nbsp;Blood Bank</a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNavbar">
            <span class="navbar-toggler-icon" style="margin: 5px"></span>
        </button>
        <div class="navbar-collapse collapse" id="collapseNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link " href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="login.php">
                        Login
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Register
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="userRegistration.php"><i class="bi bi-person-plus"></i>&nbsp;
                                User / Receiver</a></li>
                        <li><a class="dropdown-item" href="hospitalRegistration.php"><i class="bi bi-bank2"></i>&nbsp;
                                Hospital</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container col-lg-5 padding-outer">
    <div class="align-items-center flex-column justify-content-center">
        <form class="my-class padding-inner" action="login.php" method="post">

            <div class="text-center">
                <img class="mb-4" src="images/bb_logo.png" alt="logo" width="120" height="120">
            </div>
            <div class="form-group">
                <label for="username" hidden>Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" name="username"
                       value="<?php echo $username ?>" maxlength="20" required
                       oninvalid="this.setCustomValidity('Username cannot be left blank!')"
                       onchange="this.setCustomValidity('')"
                >
            </div>
            <div class="form-group"
                <?php if (isset($login_error)) : ?>
                    class="form-error"
                <?php endif; ?>
            >
                <label for="password" hidden>Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" name="password" required
                       oninvalid="this.setCustomValidity('Password cannot be left blank!')" value="<?php echo $pwd ?>" maxlength="20"
                       onchange="this.setCustomValidity('')"
                >
                <?php if (isset($login_error)) : ?>
                    <div class="mt-2">
                    <span>
                        <?php echo $login_error; ?> </span></div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <button name="login" class="btn btn-outline-danger"><i class="bi bi-box-arrow-in-right"></i> &nbsp;Login
                </button>
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
