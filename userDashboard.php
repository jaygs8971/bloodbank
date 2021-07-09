<?php
require 'connection.php';
session_start();
if ($_SESSION['user'] != "user") {
    header("location:login.php");
    exit();
}

global $conn;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | BB</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Custom styles for this page -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand abs" href="#"><i class="bi bi-droplet-half"></i>&nbsp; Blood Bank</a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNavbar">
            <span class="navbar-toggler-icon" style="margin: 5px"></span>
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
                        <li class="nav-item">
                            <a class="nav-link active" href="userDashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <text>
                                    Hi, <?php echo $_SESSION['username']; ?></text>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i>
                                        Logout</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                }
                endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="dash-padding-outer dashboard ">
    <div class="col-md-12">
        <div class="row">

            <!--  MENU PILLS-->
            <div class="col-lg-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <a class="nav-link active" id="blood-requests-tab" data-toggle="pill" href="#blood-requests"
                       role="tab"
                       aria-controls="blood-requests" aria-selected="false"><i
                                class="bi bi-arrow-up-right-square"></i>&nbsp; Requests <span class="">
                        <?php
                        $count = 0;
                        if (($q1 = mysqli_query($conn, "SELECT  * FROM request WHERE status = 0 and hospital_id = '$_SESSION[user_id]'"))) {
                            $count = mysqli_num_rows($q1);
                            if (!$count == 0) {
                                echo "(" . $count . ")";
                            }
                        } else {
                            echo '';
                        }
                        ?>
                        </span></a>

                    <a class="nav-link" id="profile-details-tab" data-toggle="pill" href="#profile-details" role="tab"
                       aria-controls="profile-details" aria-selected="false"><i class="bi bi-person-circle"></i>&nbsp;
                        Profile</a>
                </div>
            </div>

            <div class="col-lg-10">
                <div class="my-class padding-inner dashboard-form">
                    <div class="tab-content" id="v-pills-tabContent">

                        <!--                        Blood Requests-->
                        <div class="tab-pane fade show active" id="blood-requests" role="tabpanel"
                             aria-labelledby="blood-requests-tab">
                            <h3 class="h5 text-danger my-form-name">Blood Sample Requests</h3>

                            <?php
                            global $conn;
                            $slNo = 1;
                            if (mysqli_num_rows($sql3 = mysqli_query($conn, "SELECT * FROM request WHERE user_id = '$_SESSION[user_id]'")) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mt-4">
                                        <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Blood Group</th>
                                            <th>Volume (ml)</th>
                                            <th>Hospital Name</th>
                                            <th>Contact Info</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <!--                                        <th colspan="2" class="text-center">Actions</th>-->
                                        </tr>
                                        </thead>
                                        <tbody style="text-transform: capitalize !important;">
                                        <?php
                                        while ($s2 = mysqli_fetch_assoc($sql3)) {
                                            $qry1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hospital WHERE hospital_id = $s2[hospital_id]"));
                                            $qry2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = $s2[blood_id]"));
                                            ?>
                                            <!--                                HERE CODE -->
                                            <form method="post" action="dashboard.php">
                                                <tr>
                                                    <td><?php echo $s2['request_id'] ?></td>
                                                    <td><?php echo $qry2['b_group']; ?></td>
                                                    <td><?php echo $s2['volume']; ?></td>
                                                    <td><?php echo $qry1['h_name'] ?></td>
                                                    <td style="text-transform: lowercase"><?php echo $qry1['p_number'] . ", " . $qry1['email']; ?></td>
                                                    <td><?php echo $qry1['city'] . ", " . $qry1['h_state']; ?></td>
                                                    <?php
                                                    if ($s2['status'] == 0) {
                                                        ?>
                                                        <td><?php echo '<p class="text-info">Pending</p>' ?></td>
                                                        <?php
                                                    } else if ($s2['status'] == 1) {
                                                            ?>
                                                            <td><?php echo '<p class="text-success">Approved</p>' ?></td>
                                                            <?php
                                                        } else if ($s2['status'] == 2) {
                                                                ?>
                                                                <td><?php echo '<p class="text-danger">Declined</p>' ?></td>
                                                                <?php
                                                            }
                                                    ?>
                                                </tr>
                                            </form>
                                        <?php }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo '<h6 class="mt-4 w-50">No requests found</h6>';
                            }
                            ?>

                        </div>


                        <!--                        USER PROFILE-->
                        <div class="tab-pane fade" id="profile-details" role="tabpanel"
                             aria-labelledby="profile-details-tab">
                            <?php
                            $q2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$_SESSION[user_id]'"));
                            ?>
                            <h3 class="h5 mb-4 text-danger my-form-name">My Profile</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="hospitalName">Hospital Name</label>
                                    <input style="text-transform: capitalize" type="text" id="hospitalName"
                                           class="form-control" name="hospitalName"
                                           value="<?php echo $q2['f_name'] . " " . $q2['l_name'] ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="Username">Username</label>
                                    <input type="text" id="Username" class="form-control" name="Username"
                                           value="<?php echo $q2['username'] ?>" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="phoneNumber">Phone Number</label>
                                    <input type="text" id="phoneNumber" class="form-control" name="phoneNumber"
                                           value="<?php echo $q2['p_number'] ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input type="text" id="email" class="form-control" name="email"
                                           value="<?php echo $q2['email'] ?>" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" id="city" class="form-control" name="city"
                                           value="<?php echo $q2['city'] ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">State</label>
                                    <input type="text" id="state" class="form-control" name="email"
                                           value="<?php echo $q2['u_state'] ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!--                        BLOOD REPORTS  -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="my-4 p-2 text-muted text-center text-small">
    <p class="mb-1">&copy; 2021 Blood Bank. By Jayaram G S</p>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
