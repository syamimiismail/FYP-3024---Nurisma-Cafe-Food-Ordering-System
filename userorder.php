<?php

include 'config.php';

session_start();
if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
}else{
    $user_id = '';
    $_SESSION['name'] = '';
    $_SESSION['email'] = '';
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
    <link rel="stylesheet" href="user.css">
    <title>Nurisma Cafe - Order</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Order</h1>
        </div>
        <section class="order">
                <div class="title">
                    <img src="logo.png" alt="" class="logo">
                    <h1>Order summary</h1>
                </div>
                <div class="box-container">
                    <?php
                    $select_orders = $conn->prepare("SELECT * FROM `order` WHERE user_id = ?  ORDER BY `time` DESC  ");
                    $select_orders->execute([$user_id]);
                    if($select_orders->rowCount() > 0){
                        while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                            $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                            $select_products->execute([$fetch_order['product_id']]);
                            if ($select_products->rowCount() > 0){
                                while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                             ?>
                            <div class="box" <?php if($fetch_order['status'] == 'canceled'){echo 'style="border:2px solid red";';}elseif($fetch_order['status'] == 'completed'){echo 'style="border:2px solid green";';}
                                else{echo 'style="border:2px solid black";';} ?>>
                                <a href="uservieworder.php?get_id=<?= $fetch_order['id']; ?>">
                                    <p class="date"><i class="fa-regular fa-calendar-days"></i>  <span><?php echo date('d-m-Y',strtotime($fetch_order['time'])); ?></span></p>
                                    <img src="menu_img/<?= $fetch_product['img']; ?>" class="image" alt="" style="margin-bottom: 10px; display: content;">
                                    <div class="row" style="text-align: center;">
                                        <h3 class="name" style="font-size: 0.9rem;"><?= $fetch_product['name']; ?></h3>
                                        <h3 class="price">Order id : <?= $fetch_order['id']; ?></h3>
                                        <p class="price">Price: RM <?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p>
                                        <p class="status" style="color:<?php if($fetch_order['status']=='canceled'){echo 'red';}elseif($fetch_order['status'] == 'completed'){echo 'green";';}
                                        else{ echo 'black';} ?>" > <?= $fetch_order['status']; ?></p>
                                    </div>
                                </a>   
                            </div>
                            <?php
                                }
                            }
                        }
                    }else{
                        echo '<p class="empty">Your order history will be displayed here</p>';
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