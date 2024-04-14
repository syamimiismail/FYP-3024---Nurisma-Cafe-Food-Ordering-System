<?php

@include 'config.php';

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

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $emailFrom = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Capture the current date and time
    $currentDateTime = date('Y-m-d H:i:s');

    $insert_contact = $conn->prepare("INSERT INTO `messages`(date, name, email, number, message) VALUES('?, ?,?,?,?')") or die('query failed') ;
    $insert_contact->execute(['$curretDateTime', '$name','$emailFrom','$phone','$message']);

    if($insert_contact){
        $success_msg[] = 'Message has been sent! Thank you for your time';
    }
    else{
        $error_msg[] = 'Message could not been sent';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Contact Us</title>
</head>
<body style="background-color: #fff9f9; margin: 0;">

    <?php include ('userheader.php');?>

    <div class="container">
        <div id="title" class="banner">
            <h2>Get In Touch<i class="fa-regular fa-paper-plane"></i></h2>
        </div>  
        <div class="box-contact">
            <div class="contact form">
                <h3>Send a Message</h3>
                <form action="" method="POST">
                    <div class="formBox">
                        <div class="row50">
                            <div class="inputBox">
                                <span>Name</span>
                                <input type="text" name="name" placeholder="Full Name" required>
                            </div>
                        </div>

                        <div class="row50">
                            <div class="inputBox">
                                <span>Email</span>
                                <input type="email" name="email" required>
                            </div>
                            <div class="inputBox">
                                <span>Phone No.</span>
                                <input type="text" name="phone" placeholder="011-12345678" required>
                            </div>
                        </div>

                        <div class="row100">
                            <div class="inputBox">
                                <span>Message</span>
                                <textarea name="message" id="message" cols="80" rows="10" placeholder="Write your message here..."></textarea>
                            </div>
                        </div>
                        <div class="row100">
                            <div class="inputBox" style="margin: auto;">
                                <input type="submit" value="Send Message" name="submit" class="btn">
                            </div>
                        </div>
                    </div>
                </form>   
            </div>
        
            <div class="contact info">
                <h3>Contact Info</h3>
                <div class="infoBox">
                    <div>
                        <span><i class="fa-solid fa-location-dot"></i></span>
                        <p>Food Court Lotus's, Kulai, Johor <br>MALAYSIA</p>
                    </div>
                    <div>
                        <span><i class="fa-solid fa-envelope"></i></span>
                        <a href="mailto:nurismacafe@gmail.com">nurismacafe@gmail.com</a>
                    </div>
                    <div>
                        <span><i class="fa-solid fa-phone"></i></span>
                        <a href="tel:+6011-12345679">011-23456789</a>
                    </div>
                    <ul class="sci">
                        <li><a href="#"><i class="fa-brands fa-square-facebook"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-square-instagram"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-square-x-twitter"></i></a></li>
                    </ul>
                </div>
            </div>

            <div class="contact map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15952.509361431861!2d103.5759157!3d1.6681905!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da79871d452841%3A0x9626b95135a83e24!2sLotus&#39;s%20Kulai!5e0!3m2!1sen!2smy!4v1710094478230!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script>
        
    // Get the current date and time
    var currentDate = new Date();
    var currentDateTime = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2) + 'T' + ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2);

    </script>
    
    <?php include ('userfooter.php');?>
    <?php include ('alert.php');?>

</body>
</html>