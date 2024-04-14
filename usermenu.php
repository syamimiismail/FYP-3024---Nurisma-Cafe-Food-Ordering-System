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

//Add products to wishlist
if(isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];

    $varify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
    $varify_wishlist->execute([$user_id, $product_id]);

    $cart_num = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $cart_num->execute([$user_id, $product_id]);

    if ($varify_wishlist->rowCount() > 0){
        $warning_msg[] = 'Product already exist in your wishlist';
    // }else if($cart_num->rowCount() > 0){
    //     $warning_msg[] = 'Product already exist in your cart';
    }else{
        $select_price = $conn->prepare("SELECT * FROM `new_product` WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`( user_id, product_id, price) VALUE (?,?,?)");
        $insert_wishlist->execute([ $user_id, $product_id, $fetch_price['price']]);
        $success_msg[] = 'Product added to wishlist';
    }
}

//Add product to cart
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $max_cart_items->execute([$user_id]);

    $varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $varify_cart->execute([$user_id, $product_id]);

    if ($varify_cart->rowCount() > 0) {
        $warning_msg[] = 'Product already exist in your cart';
    } else if ($max_cart_items->rowCount() > 10) {
        $warning_msg[] = 'Your cart is full';
    } else {
        $select_price = $conn->prepare("SELECT * FROM `new_product` WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
    
        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, product_id, price, qty) VALUES (?,?,?,?)");
        $insert_cart->execute([$user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'Product added to cart';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Nurisma Cafe - Menu</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Menu</h1>
        </div>
        <section class="products">
            <div class="box-container">
                <?php
                    $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE `status` = 'active' ");
                    $select_products->execute();

                    if($select_products->rowCount() > 0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                           ?>
                            <form action="" method="post" class="box">
                                <img src="menu_img/<?=$fetch_products['img']; ?>" class="img" alt="">
                                <div class="button">
                                    <button type="submit" name="add_to_cart"><i class="fa-solid fa-cart-shopping"></i></button>
                                    <button type="submit" name="add_to_wishlist"><i class="fa-solid fa-heart"></i></button>
                                    <a href="userviewpage.php?pid=<?php echo $fetch_products['id']; ?>" class="fa fa-eye"></a>
                                </div>
                                <h3 class="name"><?=$fetch_products['name']; ?></h3>
                                <input type="hidden" name="product_id" value="<?=$fetch_products['id']; ?>">
                                <div class="flex">
                                    <p class="price">Price RM <?=$fetch_products['price']; ?></p>
                                    <input type="number" name="qty" required min="1" value="1" max="20" maxlength="2" class="qty" >
                                </div>
                                <a href="usercheckout.php?get_id=<?=$fetch_products['id']; ?>" class="btn">Order now</a>
                                
                            </form>
                        <?php 
                        }
                    }else{
                        echo '<p class="empty">No product added yet</p>';
                    }
                ?>
            </div>
        </section>
    </div>
    <?php include 'userfooter.php'; ?></div>
    <script src="user.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <?php include 'alert.php'; ?>
</body>
</html>