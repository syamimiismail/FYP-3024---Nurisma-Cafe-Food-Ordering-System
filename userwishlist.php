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

//Delete item from wishlist
if (isset($_POST['delete_item'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING);

    $varify_delete_items = $conn->prepare("SELECT * FROM `wishlist` WHERE id = ?");
    $varify_delete_items->execute([$wishlist_id]);

    if($varify_delete_items->rowCount() > 0) {
        $delete_wishlist_id = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
        $delete_wishlist_id->execute([$wishlist_id]);
        $success_msg[] = "Deleted";
    }else{
        $warning_msg[] = 'Wishlist item already deleted';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Nurisma Cafe - wishlist</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>My wishlist</h1>
        </div>
        <section class="products">
            <h1 class="title">Your wishlist</h1>
            <div class="box-container">
                <?php
                $grand_total = 0;
                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $select_wishlist->execute([$user_id]);
                if ($select_wishlist->rowCount() > 0){
                    while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
                        $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id= ?");
                        $select_products->execute([$fetch_wishlist['product_id']]);
                        if ($select_products->rowCount() > 0){
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <form action="" method="post" class="box">
                                <input type="hidden" name="wishlist_id" value="<?=$fetch_wishlist['id']; ?>" >
                                <img src="menu_img/<?=$fetch_products['img']; ?>" alt="">
                                <div class="button">
                                    <button type="submit" name="add_to_cart" ><i class="fa-solid fa-cart-shopping"></i></button>
                                    <a href="userviewpage.php?pid=<?php echo $fetch_products['id']; ?>" class="bx fa fa-eye"></a>
                                    <button type="submit" name="delete_item" onclick="return confirm('Are you sure to delete this item?');" >
                                    <i class="fa-solid fa-trash" ></i></button>
                                </div>
                                <h3 class="name"><?=$fetch_products['name']; ?></h3>
                                <input type="hidden" name="product_id" value="<?=$fetch_products['id']; ?>">
                                <div class="flex">
                                    <p class="price">Price RM<?=$fetch_products['price']; ?></p>
                                </div>
                                <a href="checkout.php?get_id=<?=$fetch_products['id']; ?>" class="btn">Order now</a>
                            </form>
                            <?php
                            $grand_total+=$fetch_wishlist['price'];
                        }
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