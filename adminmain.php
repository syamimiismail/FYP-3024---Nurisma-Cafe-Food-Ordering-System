<?php 

@include 'config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];
$name = $_SESSION['name'];

if($role_as != 0){
    header('location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="admin.css"> -->
    <!-- <link rel="stylesheet" href="owner.css"> -->
    <link rel="stylesheet" href="admin.css">
    <title>Dashboard</title>
</head>
<body>
    <?php include 'adminheader.php'; ?>
    <div class="mainn">
        <div class="banner">
            <h1>Welcome <?= $fetch_profile['name']; ?> !</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">Home / </a><span>Dashboard</span>
        </div>
        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>
            <div class="mainbox-container">
                <div class="box">
                    <?php
                    $select_product = $conn->prepare("SELECT * FROM `new_product` ");
                    $select_product->execute();
                    $num_of_products = $select_product->rowCount();
                    ?>
                    <h3><?= $num_of_products; ?></h3>
                    <p>Products added</p>
                    <a href="admin.php" class="mainbtn">Add new menu</a>
                </div>
                <div class="box">
                    <?php
                    $select_report = $conn->prepare("SELECT * FROM `finance` ");
                    $select_report->execute();
                    $num_of_reports = $select_report->rowCount();
                    ?>
                    <h3><?= $num_of_reports; ?></h3>
                    <p>Report</p>
                    <a href="adminfinance.php" class="mainbtn">Sales Report</a>
                </div>
                <div class="box">
                    <?php
                    $select_order = $conn->prepare("SELECT * FROM `order`");
                    $select_order->execute();
                    $num_of_order = $select_order->rowCount();
                    ?>
                    <h3><?= $num_of_order; ?></h3>
                    <p>Total orders placed</p>
                    <a href="adminorder.php"class="mainbtn">View orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_message = $conn->prepare("SELECT * FROM `message`");
                    $select_message->execute();
                    $num_of_message = $select_message->rowCount();
                    ?>
                    <h3><?= $num_of_message; ?></h3>
                    <p>Unread message</p>
                    <a href="adminmessage.php" class="mainbtn">View message</a>
                </div>
            </div> 
        </section>
    </div>

    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="admin.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>