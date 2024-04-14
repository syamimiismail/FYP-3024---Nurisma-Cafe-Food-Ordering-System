<?php

include 'config.php';

session_start();
if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
}else{
    $user_id = '';
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rancho&effect=shadow-multiple">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200&display=swap" rel="stylesheet">

    <title>About Us</title>
</head>
<body style="background-color: #fff9f9">

    <?php include ('userheader.php'); ?>
    <div class="main">
        <div class="banner">
            <h1>about us</h1>
        </div>
        <!-- <div class="about-category">
            <section class="services">
                <h3>Our History</h3>
                <div class="title">
                    <img src="logo.png" class="logo" alt="">
                    <p>Nurisma Cafe, founded with the goal of capturing the essence of Malaysian and Indonesian culinary traditions, is a tribute
                    to a long history of passion and dedication.The idea behind our cafe's founding was to introduce Southeast Asia's colorful cuisine to the center of our neighborhood.</p>
                    <p>Our journey began with a rigorous research of historic recipes, obtaining high-quality ingredients,
                    and a dedication to preserving each dish's originality. The founders, inspired by their cultural background,
                    worked painstakingly to design a menu that not only featured cherished classics like Bakso and Nasi Kukus,
                    but also introduced a carefully chosen selection of lesser-known treasures from the surrounding area.
                    <p>Nurisma Cafe's success has been driven by its dedication to quality and the pursuit of culinary perfection.
                    </p>
                </div>
                <div class="title">
                    <p>Nurisma Cafe has evolved into a beloved culinary destination, where both locals and visitors may
                    sample the authentic cuisines of Malaysia and Indonesia. The cafe's journey is defined by delighted
                    customers' smiles, the lively sounds of discussion, and the warm scent of spices in the air.
                    As we grow, we are committed to offering a welcoming atmosphere, outstanding service, and, most importantly, delicious food.
                    <img src="menu\istockphoto-1287222582-612x612.jpg" class="abt" alt="">
                    </p>
                </div>
            </section>
        </div> -->
        <div class="containerAbt">
            <div class="missionAbt">
                <h2>Sweet things to satisfied your craving</h2>
            </div>
            <section class="about">
                <div class="about-image">
                    <img src="logo.png" alt="">
                </div>
                <div class="about-content">
                    <p>Nurisma Cafe, founded with the goal of capturing the essence of Malaysian and Indonesian culinary traditions, is a tribute
                        to a long history of passion and dedication.The idea behind our cafe's founding was to introduce Southeast Asia's colorful cuisine to the center of our neighborhood.</p>
                        <p>Our journey began with a rigorous research of historic recipes, obtaining high-quality ingredients,
                        and a dedication to preserving each dish's originality. The founders, inspired by their cultural background,
                        worked painstakingly to design a menu that not only featured cherished classics like Bakso and Nasi Kukus,
                        but also introduced a carefully chosen selection of lesser-known treasures from the surrounding area.
                        <p>Nurisma Cafe's success has been driven by its dedication to quality and the pursuit of culinary perfection.
                        </p>
                </div>
            </section>
        </div>
        <div class="missionAbt">
            <h2>Our mission is to bring you comfort through food.<br>Whatever you dream of. Wherever you are.</h2>
        </div>

        <div class="containerAbt">
            <section class="about">
                <div class="about-content">
                        <p>Nurisma Cafe has evolved into a beloved culinary destination, where both locals and visitors may
                        sample the authentic cuisines of Malaysia and Indonesia. The cafe's journey is defined by delighted
                        customers' smiles, the lively sounds of discussion, and the warm scent of spices in the air.
                        As we grow, we are committed to offering a welcoming atmosphere, outstanding service, and, most importantly, delicious food.
                        </p> 
                </div>
                <div class="about-image">
                    <img src="menu\istockphoto-1287222582-612x612.jpg" alt="">
                </div>
            </section>
        </div>
    </div>
    
    <?php include ('userfooter.php');?>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    
</body>
</html>