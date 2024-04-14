<?php

include 'config.php';

session_start();
if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
}else{
    $id = '';
    $_SESSION['name'] = '';
    $_SESSION['email'] = '';
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

if (isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('location: userorder.php');
}

if (isset($_POST['cancel'])) {
    $update_order = $conn->prepare("UPDATE `order` SET status = ? WHERE id = ?");
    $update_order->execute(['canceled', $get_id]);
    header('location: userorder.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Nurisma Cafe - Order</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Order Details</h1>
        </div>
        <section class="order-detail">
                <div class="title">
                    <img src="logo.png" alt="" class="logo">
                    <h1>Order details</h1>
                </div>
                <div class="box-container">
                    <?php
                    $grand_total=0;
                    $select_orders = $conn->prepare("SELECT * FROM `order` WHERE id=? LIMIT 1");
                    $select_orders->execute([$get_id]);
                    if ($select_orders->rowCount() > 0){
                        while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
                            $select_product = $conn->prepare("SELECT * FROM `new_product` WHERE id=? LIMIT 1");
                            $select_product->execute([$fetch_order['product_id']]);
                            if ($select_product->rowCount() > 0){
                                while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                                    $sub_total = ($fetch_order['price']* $fetch_order['qty']);
                                    $grand_total += $sub_total;
                                    ?>
                                    <div class="box">
                                        <div class="col">
                                            <p class="title"><i class="fa-regular fa-calendar-days"></i><?= $fetch_order['time']; ?></p>
                                            <img src="menu_img/<?= $fetch_product['img']; ?>" class="image" alt="">
                                            <!-- <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                            <p class="price">Price: RM <?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p> -->
                                            <p class="grand-total">Total: <span>RM <?= $grand_total; ?></span></p>
                                        </div>
                                        <div class="col">
                                            <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                            <p class="price">Order id :  <?= $get_id ?></p>
                                            <p class="price">Price: RM <?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p>
                                            <!-- <p class="grand-total">Total: <span>RM <?= $grand_total; ?></span></p> -->
                                            <p class="title">Pickup Information</p>
                                            <p class="user">Order time: <?= $fetch_order['time']; ?></p>
                                            <p class="user">Pickup time: <?= $fetch_order['pickup']; ?></p>
                                            <p class="user">Status: 
                                            <span class="status" style="color:<?php if ($fetch_order['status']=='completed'){echo 'green';}
                                            elseif($fetch_order['status']=='canceled'){echo 'red';}else{echo 'darkgreen';} ?>" >
                                            <?= $fetch_order['status']; ?></span></p>
                                            <?php if ($fetch_order['status']=='canceled'){ ?>
                                                <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">Re-order</a>
                                            <?php }elseif($fetch_order['status']=='in progress'){ ?>
                                                 <form action="" method="post">
                                                    <button class="btn" type="submit" name="cancel" onclick="return confirm ('Are you sure to cancel this order?')" >Cancel Order</button>
                                                 </form>

                                            <?php }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }else{
                                echo '<p class="empty"> - </p>';
                            }
                        }
                    }else{
                        echo '<p class="empty">No order yet</p>';
                    }

                    ?>
                </div>
        </section>
    </div>
    <?php include 'userfooter.php'; ?>
    <script src="user.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <?php include 'alert.php'; ?>
</body>
</html>