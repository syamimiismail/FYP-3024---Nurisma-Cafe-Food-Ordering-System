<?php 

@include 'config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];
$name = $_SESSION['name'];

if($role_as == 1){
    header('location: login.php');
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="admin.css"> -->
    <link rel="stylesheet" href="owner.css">
    <title>Dashboard</title>
</head>
<body>
    <?php include 'ownerheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Welcome <?= $fetch_profile['name']; ?> !</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">Home / </a><span>Dashboard</span>
        </div>
        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>
            <div class="box-container">
                <div class="box">
                    <?php
                    $select_report = $conn->prepare("SELECT * FROM `finance` ");
                    $select_report->execute();
                    $num_of_reports = $select_report->rowCount();
                    ?>
                    <h3><?= $num_of_reports; ?></h3>
                    <p>Finance</p>
                    <a href="adminfinance.php" class="btn">View Finance</a>
                </div>
                <div class="box">
                    <?php
                    $select_product = $conn->prepare("SELECT * FROM `new_product` ");
                    $select_product->execute();
                    $num_of_products = $select_product->rowCount();
                    ?>
                    <h3><?= $num_of_products; ?></h3>
                    <p>Products added</p>
                    <a href="admin.php" class="btn">Add new menu</a>
                </div>
                <div class="box">
                    <?php
                    $select_active_product = $conn->prepare("SELECT * FROM `new_product` WHERE status = ? ");
                    $select_active_product->execute(['active']);
                    $num_of_active_products = $select_active_product->rowCount();
                    ?>
                    <h3><?= $num_of_active_products; ?></h3>
                    <p>Total available menu</p>
                    <a href="admin.php" class="btn">View available menu</a>
                </div>
                <div class="box">
                    <?php
                    $select_deactive_product = $conn->prepare("SELECT * FROM `new_product` WHERE status = ? ");
                    $select_deactive_product->execute(['deactive']);
                    $num_of_deactive_products = $select_deactive_product->rowCount();
                    ?>
                    <h3><?= $num_of_deactive_products; ?></h3>
                    <p>Total unavailable menu</p>
                    <a href="admin.php" class="btn">View unavailable menu</a>
                </div>
                <div class="box">
                    <?php
                    $select_users = $conn->prepare("SELECT * FROM `user_form` WHERE role_as ='1'");
                    $select_users->execute();
                    $num_of_users = $select_users->rowCount();
                    ?>
                    <h3><?= $num_of_users; ?></h3>
                    <p>Registered users</p>
                    <a href="adminuseracc.php" class="btn">View users</a>
                </div>
                <div class="box">
                    <?php
                    $select_admin = $conn->prepare("SELECT * FROM `user_form` WHERE role_as='0' ");
                    $select_admin->execute();
                    $num_of_admin = $select_admin->rowCount();
                    ?>
                    <h3><?= $num_of_admin; ?></h3>
                    <p>Registered admin</p>
                    <a href="adminacc.php" class="btn">View admin</a>
                </div>
                <div class="box">
                    <?php
                    $select_order = $conn->prepare("SELECT * FROM `order`");
                    $select_order->execute();
                    $num_of_order = $select_order->rowCount();
                    ?>
                    <h3><?= $num_of_order; ?></h3>
                    <p>Total orders placed</p>
                    <a href="adminorder.php"class="btn">View orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_confirm_order = $conn->prepare("SELECT * FROM `order` WHERE status = ?");
                    $select_confirm_order->execute(['in progress']);
                    $num_of_confirm_order = $select_confirm_order->rowCount();
                    ?>
                    <h3><?= $num_of_confirm_order; ?></h3>
                    <p>Total confirm orders</p>
                    <a href="adminorder.php"class="btn">View confirm orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_canceled_order = $conn->prepare("SELECT * FROM `order` WHERE status = ?");
                    $select_canceled_order->execute(['canceled']);
                    $num_of_canceled_order = $select_canceled_order->rowCount();
                    ?>
                    <h3><?= $num_of_canceled_order; ?></h3>
                    <p>Total canceled orders</p>
                    <a href="adminorder.php"class="btn">View canceled orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_message = $conn->prepare("SELECT * FROM `message`");
                    $select_message->execute();
                    $num_of_message = $select_message->rowCount();
                    ?>
                    <h3><?= $num_of_message; ?></h3>
                    <p>Unread message</p>
                    <a href="adminmessage.php" class="btn">View message</a>
                </div>
                
        </section>
    </div>

    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="admin.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>