<!-- <?php

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

?> -->
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200&display=swap" rel="stylesheet">
<header class="header">
    <div class="flex">
        <a href=""><img src="logo.png" alt="" class="logo" style="width: 100px;"></a>
        <nav class="navbar">
            <a href="usermain.php">Home</a>
            <a href="usermenu.php">Menu</a>
            <a href="userorder.php">Order</a>
            <a href="aboutus.php">About Us</a>
            <a href="contactus.php">Contact Us</a>
            <a href="https://youtu.be/fY8UuheGY3A?si=gstNiGgs-LXfMUzn">User Manual</a>
        </nav>
        <div class="icons">
            <i class="fa-solid fa-user" id="user-btn"></i>
            <?php
                $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $count_wishlist_items->execute([$user_id]);
                $total_wishlist_items = $count_wishlist_items->rowCount();
                if([$user_id] == ''){
                    $total_wishlist_items = '0';
                }
            ?>
            <a href="userwishlist.php" class="cart-btn"><i class="fa-regular fa-heart"><sup><?=$total_wishlist_items ?></sup></i></a>
            <?php
                $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_cart_items->execute([$user_id]);
                $total_cart_items = $count_cart_items->rowCount();
                if([$user_id] == ''){
                    $total_cart_items = '0';
                }
            ?>
            <a href="usercart.php" class="cart-btn"><i class="fa-solid fa-cart-arrow-down"><sup><?=$total_cart_items ?></sup></i></a>
            <i class="fa-solid fa-bars" id="menu-btn" style="fonts-size: 2rem;"></i>
        </div>
        <div class="user-box">
            <p>Welcome <span><?php echo $_SESSION['name'];?></span>!</p>
            <p>Email: <span><?php echo $_SESSION['email'];?></span></p>
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
            <form action="" method="post">
                <button type="submit" Name="logout" class="logout-btn">Log Out</button>  
            </form>
        </div>
    </div>
</header>