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

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);

    $success_msg[] = 'Updated';
}

//Add product to cart
if(isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];
    
    $qty = 1;
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $max_cart_items->execute([$user_id]);

    $varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $varify_cart->execute([$id, $product_id]);

    if ($varify_cart->rowCount() > 0){
        $warning_msg[] = 'Product already exist in your cart';
    }else if($max_cart_items->rowCount() > 10){
        $warning_msg[] = 'Your cart is full';
    }else{
        $select_price = $conn->prepare("SELECT * FROM `new_product` WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price,qty) VALUE (?,?,?,?,?)");
        $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'Product added to cart';
    }
}

//Delete item from cart
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

    $varify_delete_items = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
    $varify_delete_items->execute([$cart_id]);

    if($varify_delete_items->rowCount() > 0) {
        $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
        $delete_cart_id->execute([$cart_id]);
        $success_msg[] = "Deleted";
    }else{
        $warning_msg[] = 'cart item already deleted';
    }
}

//Empty cart
if (isset($_POST['empty_cart'])){
    $varify_empty_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $varify_empty_item->execute([$user_id]);

    if($varify_empty_item->rowCount() > 0) {
        $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);
        $success_msg[] = "Deleted";
    }else{
        $warning_msg[] = 'cart item already deleted';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Nurisma Cafe - cart</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>My cart</h1>
        </div>
        <section class="products">
            <h1 class="title">My cart</h1>
            <div class="box-container">
                <?php
                $grand_total = 0;
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0){
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                        $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id= ?");
                        $select_products->execute([$fetch_cart['product_id']]);
                        if ($select_products->rowCount() > 0){
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <form action="" method="post" class="box">
                                <input type="hidden" name="cart_id" value="<?=$fetch_cart['id']; ?>" >
                                <img src="menu_img/<?=$fetch_products['img']; ?>" alt="">
                                <h3 class="name"><?=$fetch_products['name']; ?></h3>
                                <div class="flex">
                                    <p class="price">Price RM<?=$fetch_products['price']; ?></p>
                                    <input type="number" name="qty" required min="1" value="<?=$fetch_cart['qty']; ?>" max="30" maxlength="2" class="qty" >
                                    <button type="submit" name="update_cart" class="fa fa-edit" ></button>
                                
                                    </div>
                                <p>Sub Total : <span>RM <?=$sub_total = ($fetch_cart['qty']*$fetch_cart['price']) ?></span></p>
                                <button name="delete_item" type="submit" class="btn" onclick="return confirm ('Delete this item from cart?');" >Delete</button>
                            </form>
                            <?php
                            $grand_total+=$sub_total;
                        }else{
                            echo '<p class="empty">Your cart is empty</p>';
                        }
                    }
                }else{
                    echo '<p class="empty">No product added yet</p>';
                }
                ?>
            </div>
            <?php

                if ($grand_total !=0) {
                    ?>
                    <div class="cart-total">
                        <p>Total : <span>RM <?=$grand_total; ?></span></p>
                        <div class="button">
                            <form action="" method="post">
                                <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure to empty your cart?')" >Empty cart</button>
                            </form>
                            <a href="usercheckout.php" class="btn">Checkout</a>
                        </div>
                    </div>
                    
               <?php }
            ?>
        </section>
    </div>
    <?php include 'userfooter.php'; ?></div>
    <script src="user.js"></script>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <?php include 'alert.php'; ?>
</body>
</html>