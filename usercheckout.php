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

if (isset($_POST['place_order'])){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $pickupdate = $_POST['pickupdate'];
    $pickupdate = filter_var($pickupdate, FILTER_SANITIZE_STRING);
    $pickuptime = $_POST['pickuptime'];
    $pickuptime = filter_var($pickuptime, FILTER_SANITIZE_STRING);
    $status = 'in progress';

    $pickup = $pickupdate . ' ' . $pickuptime;

    // Capture the current date and time
    $currentDateTime = date('Y-m-d H:i:s');

    $varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=? ");
    $varify_cart->execute([$user_id]);

    if (isset($_GET['get_id'])) {
        $get_product = $conn->prepare("SELECT * FROM `new_product` WHERE id=? LIMIT 1 "); 
        $get_product->execute([$_GET['get_id']]);

        if ($get_product->rowCount() > 0) {
            while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
                $qty = '1';
                // Generate a new unique ID
                $order_id = unique_id();
                $insert_order = $conn->prepare("INSERT INTO `order`
                ( id, user_id, name, phone, email, product_id, qty, time, pickup, price, method, status) VALUES
                ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([ $order_id, $user_id, $name, $number, $email, $fetch_p['id'], $qty, $currentDateTime, $pickup, $fetch_p['price'], $method, $status]);
                echo <<<HTML
                    <div class='order-message-container' style='height: 500px;'>
                    <div class='message-container'>
                        <h3>thank you for shopping!</h3>
                        <h2>Please save your receipt and show it to counter when you want to pickup your order</h2>
                        <div class='order-detail'>
                            <span>{$fetch_p['name']}</span>
                            <span class='total'> Total : RM{$fetch_p['price']}  </span>
                        </div>
                        <div class='customer-details'>
                            <p> your name : <span>{$name}</span> </p>
                            <p> your phone number : <span>{$number}</span> </p>
                            <p> your email : <span>{$email}</span> </p>
                            <p> your payment method : <span>{$method}</span> </p>
                        </div>
                            <a href='userorder.php' class='btn'>View Order</a>
                            <input type='button' class='btn' name='Print receipt' onClick='window.print()' value='Print receipt'>
                        </div>
                    </div> 
                    "; 
                    HTML;  header('location: userorder.php');  
                    
            }
        }else{
            $warning_msg[] = 'Something went wrong';
        }
    // }elseif ($varify_cart->rowCount() > 0){
    //     while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)){
    //         // Check if an order already exists for the same user and product
    //         $check_existing_order = $conn->prepare("SELECT * FROM `order` WHERE user_id = ? AND product_id = ? AND pickup = ?");
    //         $check_existing_order->execute([$user_id, $f_cart['id'],$pickup]);
            
    //         if ($check_existing_order->rowCount() == 0) {
    //         $insert_order = $conn->prepare("INSERT INTO `order`
    //         (id, user_id, name, phone, email, product_id, qty, time, pickup, price, method, status) VALUES
    //         (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    //         $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $f_cart['product_id'], $f_cart['qty'], $currentDateTime, $pickup, $f_cart['price'], $method, $status]);
            
    //         $varify_menu = $conn->prepare("SELECT * FROM `new_product` WHERE id=? ");
    //         $varify_menu->execute([$f_cart['id']]);
    //         if ($varify_menu->rowCount() > 0){
    //             while($f_menu = $varify_menu->fetch(PDO::FETCH_ASSOC)){
    //         echo "
    //         <div class='order-message-container'>
    //         <div class='message-container'>
    //             <h3>thank you for shopping!</h3>
    //             <h2>Please save your receipt and show it to counter when you want to pickup your order</h2>
    //             <div class='order-detail'>
    //                 <span>".$f_menu['name']."</span>
    //                 <span class='total'> total : RM".$f_cart['price']."  </span>
    //             </div>
    //             <div class='customer-details'>
    //                 <p> your name : <span>".$name."</span> </p>
    //                 <p> your phone number : <span>".$number."</span> </p>
    //                 <p> your email : <span>".$email."</span> </p>
    //                 <p> your payment method : <span>".$method."</span> </p>
    //             </div>
    //                 <a href='userorder.php' class='btn'>View Order</a>
    //                 <input type='button' class='btn' name='Print receipt' onClick='window.print()' value='Print receipt'>
    //             </div>
    //         </div>
    //         ";      }
    //             }
    //         }
    //     }
    //     if ($insert_order) {

    //         $varify_menu = $conn->prepare("SELECT * FROM `new_product` WHERE id = ?");
    //         $varify_menu->execute([$f_cart['id']]);
    //         $f_menu = $varify_menu->fetch(PDO::FETCH_ASSOC);
    //         echo "
    //         <div class='order-message-container'>
    //             <div class='message-container'>
    //                 <h3>thank you for shopping!</h3>
    //                 <h2>Please save your receipt and show it to counter when you want to pickup your order</h2>
    //                 <div class='order-detail'>
    //                     <span>".$f_menu['name']."</span>
    //                     <span class='total'> total : RM".$f_cart['price']."  </span>
    //                 </div>
    //                 <div class='customer-details'>
    //                     <p> your name : <span>".$name."</span> </p>
    //                     <p> your phone number : <span>".$number."</span> </p>
    //                     <p> your email : <span>".$email."</span> </p>
    //                     <p> your payment method : <span>".$method."</span> </p>
    //                 </div>
    //                 <a href='userorder.php' class='btn'>View Order</a>
    //                 <input type='button' class='btn' name='Print receipt' onClick='window.print()' value='Print receipt'>
    //             </div>
    //         </div>
    //         ";  
            
    //         $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    //         $delete_cart_id->execute([$user_id]);
    //         // header('location: userorder.php');
    //     }
    // 
    }elseif ($varify_cart->rowCount() > 0){
        while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)){
            // Check if an order already exists for the same user, product, and pickup time
            $check_existing_order = $conn->prepare("SELECT * FROM `order` WHERE user_id = ? AND product_id = ? AND pickup = ?");
            $check_existing_order->execute([$user_id, $f_cart['id'], $pickup]);
            
            if ($check_existing_order->rowCount() == 0) {
                // Attempt to insert the order
                try {
                    $insert_order = $conn->prepare("INSERT INTO `order`
                        (id, user_id, name, phone, email, product_id, qty, time, pickup, price, method, status) VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
                    // Generate a new unique ID
                    $order_id = unique_id();
    
                    // Execute the insertion query
                    $insert_order->execute([$order_id, $user_id, $name, $number, $email, $f_cart['product_id'], $f_cart['qty'], $currentDateTime, $pickup, $f_cart['price'], $method, $status]);

                    $varify_menu = $conn->prepare("SELECT * FROM `new_product` WHERE id=? ");
                    $varify_menu->execute([$f_cart['id']]);
                    if ($varify_menu->rowCount() > 0){
                        while($f_menu = $varify_menu->fetch(PDO::FETCH_ASSOC)){
                            $result = $insert_order->execute([$order_id, $user_id, $name, $number, $email, $f_cart['product_id'], $f_cart['qty'], $currentDateTime, $pickup, $f_cart['price'], $method, $status]);
                    // Check if insertion was successful
                        if ($result) {
                            // Display the order message container
                            
                            header('location: userorder.php');  
                        }
                        }
                    }
                }
                    catch (PDOException $e) {
                        // Handle PDOExceptions (e.g., duplicate ID error)
                        if ($e->getCode() == '23000') {
                            // Duplicate entry error occurred, increment retry count or handle as needed
                        } else {
                            // Another PDOException occurred, handle the error
                            echo "An error occurred: " . $e->getMessage();
                        }
                    }
            }else {
                $warning_msg[] = 'Wrong';
            }
        }
    
        // After processing all items in the cart
        // Delete the cart items for the user
        $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);
        header('location: userorder.php');  

    } else{
        $warning_msg[] = 'Something went wrong';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css">
    <title>Nurisma Cafe - Checkout</title>
</head>
<body>
    <?php include 'userheader.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Checkout</h1>
        </div>
        <section class="checkout">
            <div class="title">
                <img src="logo.png" alt="" class="logo">
                <h1>Checkout summary</h1>
                <p>Please check your order before checkout</p>
                <!-- <div class="row"> -->
                    <form action="" method="post" enctype="multipart/form-data">
                        <div id="row">
                            <div id="col" class="box">
                                <h3>Information details</h3>
                                <div class="input-field">
                                    <p>Name <span>*</span></p>
                                    <input type="text" name="name" required maxlength="100" placeholder="Enter your name" class="input" >
                                </div>
                                <div class="input-field">
                                    <p>Phone No <span>*</span></p>
                                    <input type="number" name="number" required maxlength="15" placeholder="Enter your phone number (without 'dash')" class="input" >
                                </div>
                                <div class="input-field">
                                    <p>Email <span>*</span></p>
                                    <input type="email" name="email" required maxlength="100" placeholder="Enter your email" class="input" >
                                </div>
                                <div class="input-field">
                                    <p>Payment method <span>*</span></p>
                                    <select name="method" class="input" id="">
                                        <option value="cash">Cash</option>
                                        <option value="fpx">Internet Banking (FPX)</option>
                                    </select>
                                </div>
                                <div class="input-field">
                                    <p>Pickup Time <span>*</span></p>
                                    <input type="date" id="date" name="pickupdate" min="<?php echo date('Y-m-d'); ?>" required>
                                    <input type="time" id="time" name="pickuptime" required>
                                </div>
                            </div>
                            <div id="col" class="summary">
                                <h3>My order</h3>
                                <div class="box-container">
                                    <?php
                                    $grand_total = 0;
                                    if(isset($_GET['get_id'])) {
                                        $select_get = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                                        $select_get->execute([$_GET['get_id']]);
                                        while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                                            $sub_total = $fetch_get['price'];
                                            $grand_total+=$sub_total;
                                        ?>
                                        <div class="flex">
                                            <img src="menu_img/<?=$fetch_get['img']; ?>" class="image" alt="">
                                            <div class="details">
                                                <h3 class="name"><?=$fetch_get['name']; ?></h3>
                                                <p class="price"><?=$fetch_get['price']; ?></p>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    }else{
                                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                                        $select_cart->execute([$user_id]);
                                        if ($select_cart->rowCount() > 0){
                                            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                                $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id = ?");
                                                $select_products->execute([$fetch_cart['product_id']]);
                                                $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                                                $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);
                                                $grand_total += $sub_total;
                                        ?>
                                        <div class="flex">
                                            <img src="menu_img/<?=$fetch_product['img']; ?>" alt="">
                                            <div class="text">
                                                <h3 class="name"><?=$fetch_product['name']; ?></h3>
                                                <p class="price">RM <?=$fetch_product['price']; ?> x <?=$fetch_cart['qty']; ?></p>
                                                <p>Sub Total : <span>RM <?=$sub_total = ($fetch_cart['qty']*$fetch_cart['price']) ?></span></p>
                                            </div>
                                        </div>
                                        <?php    
                                            }
                                        }else{
                                            echo '<p class="empty">Your cart is empty</p>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="grand-total"><span>Total: </span>RM <?=$grand_total; ?></div>
                                        
                            </div>
                        </div>
                        
                        <button type="submit" name="place_order" class="btn">Checkout</button>
                    </form>
                    
                
            </div>

        </section>
    </div>
    <?php include 'userfooter.php'; ?>
    <script src="user.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <script>
        // Get the current date and time
        var currentDate = new Date();
        var currentDateTime = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2) + 'T' + ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2);

        // Set the minimum value for the date input to today's date
        document.getElementById("date").min = currentDateTime.slice(0, 10);

        // Set the minimum value for the time input to 30 minutes from now
        currentDate.setMinutes(currentDate.getMinutes() + 30);
        var minTime = ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2);
        document.getElementById("time").min = minTime;

        function unique_id(){
        return mt_rand(1000000000, 9999999999);
        } 
    </script>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>