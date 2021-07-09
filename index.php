<?php
require 'connection.php';
global $conn;
session_start();

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
    <title>Blood Bank</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
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
                    <a class="nav-link active" href="#">Home</a>
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
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i>
                                        &nbsp;Logout</a></li>
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
                                <text class="light">Welcome, <?php echo $_SESSION['username']; ?> </text>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i>
                                        &nbsp;Logout</a></li>
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
                            <li><a class="dropdown-item" href="userRegistration.php"><i class="bi bi-person-plus"></i>&nbsp;
                                    User / Receiver</a></li>
                            <li><a class="dropdown-item" href="hospitalRegistration.php"><i class="bi bi-bank2"></i>&nbsp;
                                    Hospital</a></li>
                        </ul>
                    </li>
                <?php
                endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="padding-outer">
    <div class="my-class padding-inner">
        <h3 class="h5 text-danger my-form-name">Available Blood Samples</h3>


        <?php
        $slNo = 1;
        if (mysqli_num_rows($sql1 = mysqli_query($conn, "SELECT * FROM stock ORDER BY stock_id DESC")) == 0) {
            echo '<h6 class="mt-4 w-50">No records found!</h6>';
        }else{
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover mt-4">
                <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hospital Name</th>
                    <th scope="col">Blood Group</th>
                    <th scope="col">City</th>
                    <th scope="col">State</th>
<!--                    <th scope="col">Quantity (ml)</th>-->
                    <?php if (isset($_SESSION['user'])) {
                        if ($_SESSION['user'] == 'admin') {
                        } else {
                            ?>
                            <th scope="col">Action</th>
                            <?php
                        }
                    } else {
                        ?>
                        <th scope="col">Action</th>
                        <?php
                    }
                    ?>
                </tr>
                </thead>
                <caption>Last updated at: <?php
                    if (mysqli_num_rows($qry1 = mysqli_query($conn, "SELECT * FROM stock ORDER BY updated_at DESC LIMIT 1")) > 0) {
                        $qry2 = mysqli_fetch_assoc($qry1);
                        echo $qry2['updated_at'];
                    } else {
                        echo "00";
                    }
                    ?></caption>
                <tbody>
                <?php
                while ($res1 = mysqli_fetch_array($sql1)) {

                $sql2 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM hospital WHERE hospital_id = '$res1[hospital_id]'"));
                $sql3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood WHERE blood_id = '$res1[blood_id]'"));

                $field1name = $slNo;
                $field2name = $sql2["h_name"];
                $field3name = $sql3["b_group"];
                $field4name = $sql2["city"];
                $field5name = $sql2["h_state"];
//                $field6name = $res1["volume"];

//                if ($field6name <= 0) {
//                    continue;
//                }

                if (isset($_SESSION['user'])) {
                    if ($_SESSION['user'] == "admin") {
                        echo '<tr > 
                  <td>' . $field1name . '</td> 
                  <td style="text-transform: capitalize">' . $field2name . '</td> 
                  <td>' . $field3name . '</td> 
                  <td>' . $field4name . '</td> 
                  <td>' . $field5name . '</td> 

              </tr>              
              ';
                        $slNo++;
                    } else {
                        ?>
                        <form action="requestSample.php" method="post">
                            <?php
                            echo '<tr> 
                  <td>' . $field1name . '</td> 
                  <td style="text-transform: capitalize">' . $field2name . '</td> 
                  <td>' . $field3name . '</td> 
                  <td>' . $field4name . '</td> 
                  <td>' . $field5name . '</td> 
                  <input type="hidden" name="stock_id" value=" ' . $res1['stock_id'] . ' ">
                  <td> <input type="submit" name="request" class="btn btn-outline-danger btn-sm" value="Request Sample"></td>
              </tr>';

                            ?>
                        </form>

                        <?php
                        $slNo++;
                    }

                } else {
                ?>
                <form action="requestSample.php" method="post">
                    <?php
                    echo '<tr> 
                  <td>' . $field1name . '</td> 
                  <td style="text-transform: capitalize">' . $field2name . '</td> 
                  <td>' . $field3name . '</td> 
                  <td>' . $field4name . '</td> 
                  <td>' . $field5name . '</td> 
                  
                  <td> <input type="submit" value="Request Sample" class="btn-sm btn btn-outline-danger" name="request"> </td> 

              </tr>';
                    ?><?php
                    $slNo++;
                    }
                    }
                    }
                    ?>
                </form>

                </tbody>
            </table>
        </div>
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
