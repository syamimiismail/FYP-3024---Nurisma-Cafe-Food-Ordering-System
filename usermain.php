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
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <title>Nurisma Cafe</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <section class="home-section">
            <div class="slider">
                <!-- Slide -->
                <div class="slider__slider slide1">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <a href="menu.php"><img src="menu/banner1.png" alt=""></a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <div class="slider__slider slide2">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <a href="menu.php"><img src="menu/banner2.png" alt=""></a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <div class="slider__slider slide3">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <a href="menu.php"><img src="menu/banner3.png" alt=""></a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End-->
                <div class="left-arrow"><i class="fa-solid fa-arrow-left"></i></div>
                <div class="right-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </div>
        </section>
        <section class="thumb">
            <h3>Our <span> Top </span> Menu</h3>
            <div class="box-container">
                <div class="box">
                    <img src="menu/topmenu1.png" alt="">
                    <a href="userviewpage.php?pid=17; ?>">View<i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
                <div class="box">
                    <img src="menu/topmenu2.png" alt="">
                    <a href="userviewpage.php?pid=19; ?>">View<i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
                <div class="box">
                    <img src="menu/topmenu3.png" alt="">
                    <a href="userviewpage.php?pid=2; ?>">View<i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <section class="thumb shop">
            <h3>Category</h3>
            <div class="box-container">
                <div class="box">
                    <img src="menu_img\nasikukusayam.png" alt="">
                    <span>Main Dishes</span>
                    <a href="usermenu.php" class="btn">Shop Now</a>
                    <!-- <div class="detail">
                    </div> -->
                </div>
                <div class="box">
                    <img src="menu_img\telursambal.png" alt="">
                    <div class="detail">
                    <span>Side Dishes</span>
                        <a href="usermenu.php" class="btn">Shop Now</a>
                    </div>
                </div>
                <div class="box">
                    <img src="menu_img\nasi.png" alt="">
                    <div class="detail">
                        <span>Add on</span>
                        <a href="usermenu.php" class="btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'userfooter.php'; ?></div>
    <script src="user.js"></script>
    <?php include 'alert.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</body>
</html>