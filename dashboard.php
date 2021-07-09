<?php
require 'connection.php';
session_start();
if ($_SESSION['user'] != "admin") {
    header("location:login.php");
    exit();
}

global $conn;
if (isset($_POST['add_blood'])) {
    $volume = $_POST['volume'];
    $b_group = $_POST['bloodGroup'];

    $sql1 = mysqli_query($conn, "SELECT blood_id FROM blood WHERE b_group = '$b_group'");
    $res1 = mysqli_fetch_assoc($sql1);

    $sql2 = mysqli_query($conn, "SELECT * FROM hospital WHERE username = '$_SESSION[username]'");
    $res2 = mysqli_fetch_assoc($sql2);


    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock WHERE hospital_id = '$res2[hospital_id]' AND blood_id = '$res1[blood_id]' ")) != 0) {
        if (mysqli_query($conn, "UPDATE stock SET volume = volume + '$volume' WHERE hospital_id = '$res2[hospital_id]' and blood_id = '$res1[blood_id]'")) {
            $blood_added = true;
        }
    } else {
        mysqli_query($conn, "INSERT INTO stock (volume, status, hospital_id, blood_id) VALUES ('$volume', '1','$res2[hospital_id]', '$res1[blood_id]' )");
        $blood_added = true;
    }
}

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
                    if ($_SESSION['user'] == 'admin') {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <text>
                                    Welcome, <?php echo $_SESSION['username']; ?></text>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i>
                                        Logout</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                }
                    ?>
                <?php else : ?>
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

<div class="dash-padding-outer dashboard ">

    <!--    SUCCESS / FAILURE MESSAGES-->
    <?php if (isset($blood_added)) : ?>
        <div class="alert alert-info alert-dismissible fade show ml-3 mr-3" role="alert">
            <i class="bi bi-info-circle"></i>&nbsp; Blood bank updated successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i style="font-size: 24px" class="bi bi-x"></i></span>
            </button>
        </div>
    <?php endif; ?>

    <?php
    if (isset($_SESSION['approve_success'])) :
        if (($_SESSION['approve_success']) == true) : ?>
            <div class="alert alert-success alert-dismissible fade show ml-3 mr-3" role="alert">
                <i class="bi bi-check2-circle"></i>&nbsp; Request for blood sample completed.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i style="font-size: 24px" class="bi bi-x"></i></span>
                </button>
            </div>
        <?php endif;
    endif;
    $_SESSION['approve_success'] = false;
    ?>

    <?php
    if (isset($_SESSION['approve_failed'])) :
        if (($_SESSION['approve_failed']) == true) : ?>
            <div class="alert alert-success alert-dismissible fade show ml-3 mr-3" role="alert">
                <i class="bi bi-check2-circle"></i>&nbsp; Request for blood sample declined.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i style="font-size: 24px" class="bi bi-x"></i></span>
                </button>
            </div>
        <?php endif;
    endif;
    $_SESSION['approve_failed'] = false;
    ?>

    <?php
    if (isset($_SESSION['stock_error'])) :
        if (($_SESSION['stock_error']) == true) : ?>
            <div class="alert alert-danger alert-dismissible fade show ml-3 mr-3" role="alert">
                <i class="bi bi-exclamation-circle"></i>&nbsp; Approval failed. Not enough blood in stock available.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i style="font-size: 24px" class="bi bi-x"></i></span>
                </button>
            </div>
        <?php endif;
    endif;
    $_SESSION['stock_error'] = false; ?>
    <!--   // SUCCESS / FAILURE  MESSAGES -->

    <div class="col-md-12 ">
        <div class="row">

            <!--  MENU PILLS-->
            <div class="col-lg-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="stock-details-tab" data-toggle="pill" href="#stock-details"
                       role="tab"
                       aria-controls="stock-details" aria-selected="false"><i class="bi bi-file-earmark-diff"></i>&nbsp;
                        Stock
                        Details</a>
                    <a class="nav-link" id="add-blood-tab" data-toggle="pill" href="#add-blood" role="tab"
                       aria-controls="add-blood" aria-selected="true"><i class="bi bi-journal-medical"></i>&nbsp;
                        Update Bank</a>

                    <a class="nav-link" id="blood-requests-tab" data-toggle="pill" href="#blood-requests" role="tab"
                       aria-controls="blood-requests" aria-selected="false"><i
                                class="bi bi-arrow-down-right-square"></i>&nbsp; Requests<span>
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

                    <a class="nav-link" id="blood-report-approved-tab" data-toggle="pill" href="#blood-report-approved"
                       role="tab"
                       aria-controls="blood-report-approved" aria-selected="false"><i class="bi bi-journal-check"></i>&nbsp;
                        Approved Requests</a>

                    <a class="nav-link" id="blood-report-denied-tab" data-toggle="pill" href="#blood-report-denied"
                       role="tab"
                       aria-controls="blood-report-denied" aria-selected="false"><i class="bi bi-journal-x"></i>&nbsp;
                        Denied Requests</a>

                    <a class="nav-link" id="profile-details-tab" data-toggle="pill" href="#profile-details" role="tab"
                       aria-controls="profile-details" aria-selected="false"><i class="bi bi-person-circle"></i>&nbsp;
                        Profile</a>
                </div>
            </div>

            <div class="col-lg-10">
                <div class="my-class padding-inner dashboard-form">
                    <div class="tab-content" id="v-pills-tabContent">
                        <!--                        Add Blood-->
                        <div class="tab-pane fade" id="add-blood" role="tabpanel"
                             aria-labelledby="add-blood-tab">
                            <form class="" method="post" action="dashboard.php">
                                <h3 class="h5 mb-4 text-danger my-form-name">Add Blood Details</h3>
                                <div class="row">
                                    <div class="col">
                                        <label for="bloodGroup">Blood type</label>
                                        <select id="bloodGroup" class="form-control" name="bloodGroup">
                                            <option selected value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="volume">Volume (ml)</label>
                                        <input class="form-control" id="volume" name="volume" type="text" pattern="\d*"
                                               maxlength="5" required>
                                    </div>
                                </div>
                                <button class="mt-3 btn btn-outline-danger"
                                        name="add_blood"><i class="bi bi-check-circle"></i> Update
                                </button>
                            </form>
                        </div>

                        <!--                        Blood Requests-->
                        <div class="tab-pane fade " id="blood-requests" role="tabpanel"
                             aria-labelledby="blood-requests-tab">
                            <h3 class="h5 text-danger my-form-name">Blood Sample Requests Pending</h3>

                            <?php
                            global $conn;
                            $slNo = 1;
                            if (mysqli_num_rows($sql3 = mysqli_query($conn, "SELECT * FROM request WHERE hospital_id = '$_SESSION[user_id]' and status = 0")) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mt-4">
                                        <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Blood Group</th>
                                            <th>Volume (ml)</th>
                                            <th>Phone No</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th colspan="2" class="text-center">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody style="text-transform: capitalize !important;">
                                        <?php
                                        while ($s2 = mysqli_fetch_assoc($sql3)) {
                                            $qry1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE user_id = $s2[user_id]"));
                                            $qry2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = $s2[blood_id]"));
                                            ?>
                                            <!--                                HERE CODE -->
                                            <form method="post" action="dashboard.php">
                                                <tr>
                                                    <td><?php echo $s2['request_id'] ?></td>
                                                    <td><?php echo $qry1['f_name'] . " " . $qry1['l_name'] ?></td>
                                                    <td><?php echo $qry2['b_group']; ?></td>
                                                    <td><?php echo $s2['volume']; ?></td>
                                                    <td><?php echo $qry1['p_number']; ?></td>
                                                    <td><?php echo $qry1['city']; ?></td>
                                                    <td><?php echo $qry1['u_state']; ?></td>

                                                    <?php echo "<td><a href=\"requestAccept.php?id=" . $s2['request_id'] . "\"><button type='button' class='btn-sm btn btn-outline-success'><i class='bi bi-check'></i>&nbsp;Approve</button></a></td>"; ?>
                                                    <?php echo "<td><a href=\"requestDenied.php?id=" . $s2['request_id'] . "\"><button type='button' class='btn-sm btn btn-outline-danger'><i class='bi bi-x'></i>&nbsp;Decline</button></a></td>"; ?>

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

                        <!--                        STOCK DETAILS-->
                        <div class=" tab-pane fade show active" id="stock-details" role="tabpanel"
                             aria-labelledby="stock-details-tab">
                            <h3 class="h5 text-danger my-form-name">Blood Bank Stock</h3>

                            <?php
                            global $conn;
                            $slNo = 1;
                            if (mysqli_num_rows($sql3 = mysqli_query($conn, "SELECT * FROM stock WHERE hospital_id = '$_SESSION[user_id]'")) > 0) {
                                ?>
                                <div class="table-responsive ">
                                    <table class="table table-hover mt-4">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Blood Group</th>
                                            <th scope="col">Available Volume (ml)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($sql4 = mysqli_fetch_assoc($sql3)) {
                                        ?>
                                        <form method="post" action="dashboard.php">

                                            <?php

                                            $sql6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$sql4[blood_id]'"));

                                            $field1name = $slNo;
                                            $field2name = $sql6["b_group"];
                                            $field3name = $sql4["volume"];
                                            $slNo++;

                                            echo '<tr> 
                                              <td>' . $field1name . '</td> 
                                              <td>' . $field2name . '</td> 
                                              <td>' . $field3name . '</td> 
                                          </tr>';
                                            }
                                            ?>
                                        </form>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else {
                                echo '<h6 class="mt-4 w-50">No records found</h6>';
                            }
                            ?>
                        </div>

                        <!--                        PROFILE-->
                        <div class="tab-pane fade" id="profile-details" role="tabpanel"
                             aria-labelledby="profile-details-tab">
                            <?php
                            $q2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hospital WHERE hospital_id = '$_SESSION[user_id]'"));
                            ?>
                            <h3 class="h5 mb-4 text-danger my-form-name">Hospital Profile</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="hospitalName">Hospital Name</label>
                                    <input type="text" id="hospitalName" class="form-control" name="hospitalName"
                                           value="<?php echo $q2['h_name'] ?>" readonly>
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
                                           value="<?php echo $q2['h_state'] ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!--                        BLOOD REPORTS APPROVED-->
                        <div class="tab-pane fade" id="blood-report-approved" role="tabpanel"
                             aria-labelledby="blood-report-approved-tab">
                            <h3 class="h5 text-danger my-form-name">Sample Requests Approved</h3>
                            <?php
                            $slNum = 1;
                            if (mysqli_num_rows($q3 = mysqli_query($conn, "SELECT * FROM request WHERE hospital_id = '$_SESSION[user_id]' AND status = 1 ORDER BY created_at DESC LIMIT 10")) > 0) {
                                ?>
                                <div class="table-responsive ">
                                    <table class="table table-hover mt-4">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Blood Group</th>
                                            <th scope="col">Volume (ml)</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Requested</th>
                                            <th scope="col">Fulfilled</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($q4 = mysqli_fetch_assoc($q3)) {
                                            ?>
                                            <?php
                                            $q5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$q4[user_id]'"));
                                            $q6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$q4[blood_id]'"));

                                            $field1name = $slNum;
                                            $field2name = $q5["f_name"] . " " . $q5["l_name"];
                                            $field3name = $q6["b_group"];
                                            $field4name = $q4["volume"];
                                            $field5name = $q5["p_number"];
                                            $field6name = $q5["email"];
                                            $field7name = $q4["created_at"];
                                            $field8name = $q4["updated_at"];
                                            $slNum++;

                                            echo '<tr> 
                                              <td>' . $field1name . '</td> 
                                              <td>' . $field2name . '</td> 
                                              <td>' . $field3name . '</td> 
                                              <td>' . $field4name . '</td> 
                                              <td>' . $field5name . '</td> 
                                              <td>' . $field6name . '</td> 
                                              <td>' . $field7name . '</td> 
                                              <td>' . $field8name . '</td> 
                                          </tr>';
                                        }
                                        ?>
                                        <caption>Displaying <?php echo $slNum - 1; ?> recent approvals.</caption>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo '<h6 class="mt-4 w-50">No records found</h6>';
                            }
                            ?>

                        </div>

                        <!--                        BLOOD REPORTS DENIED -->
                        <div class="tab-pane fade" id="blood-report-denied" role="tabpanel"
                             aria-labelledby="blood-report-denied-tab">
                            <h3 class="h5 text-danger my-form-name">Sample Requests Denied</h3>
                            <?php
                            $slNum = 1;
                            if (mysqli_num_rows($q3 = mysqli_query($conn, "SELECT * FROM request WHERE hospital_id = '$_SESSION[user_id]' AND status = 2 ORDER BY created_at DESC LIMIT 10")) > 0) {
                                ?>
                                <div class="table-responsive ">
                                    <table class="table table-hover mt-4">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Blood Group</th>
                                            <th scope="col">Volume (ml)</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Requested</th>
                                            <th scope="col">Denied</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($q4 = mysqli_fetch_assoc($q3)) {
                                            ?>
                                            <?php
                                            $q5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$q4[user_id]'"));
                                            $q6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$q4[blood_id]'"));

                                            $field1name = $slNum;
                                            $field2name = $q5["f_name"] . " " . $q5["l_name"];
                                            $field3name = $q6["b_group"];
                                            $field4name = $q4["volume"];
                                            $field5name = $q5["p_number"];
                                            $field6name = $q5["email"];
                                            $field7name = $q4["created_at"];
                                            $field8name = $q4["updated_at"];
                                            $slNum++;

                                            echo '<tr> 
                                              <td>' . $field1name . '</td> 
                                              <td>' . $field2name . '</td> 
                                              <td>' . $field3name . '</td> 
                                              <td>' . $field4name . '</td> 
                                              <td>' . $field5name . '</td> 
                                              <td>' . $field6name . '</td> 
                                              <td>' . $field7name . '</td> 
                                              <td>' . $field8name . '</td> 
                                          </tr>';
                                        }
                                        ?>
                                        <caption>Displaying <?php echo $slNum - 1; ?> recent denials.</caption>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo '<h6 class="mt-4 w-50">No records found</h6>';
                            }
                            ?>

                        </div>


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