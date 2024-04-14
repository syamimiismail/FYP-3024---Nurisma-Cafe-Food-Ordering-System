<?php

@include 'config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];

if(($role_as == 1) || ($id == '')){
    header('location: login.php');
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

if(isset($_GET['done'])){
    $update_o_id = $_GET['done'];
    $update_o_status = 'done';

    $update_query = $conn->prepare("UPDATE `order` SET status = ? WHERE id = ?");
    $update_query->execute([$update_o_status, $update_o_id]);

    if($update_query){
        $success_msg[] = 'order updated successfully';
        header('location: adminorder.php');
    }
    else{
        $warning_msg[] = 'order could not be updated';
        header('location: adminorder.php');
    }
}

if(isset($_GET['cancel'])){
    $update_o_id = $_GET['cancel'];
    $update_o_status = 'cancel';

    $update_query = $conn->prepare("UPDATE `order` SET status = ? WHERE id = ?");
    $update_query->execute([$update_o_status, $update_o_id]);

    if($update_query){
        $success_msg[] = 'order updated successfully';
        header('location: adminorder.php');
    }
    else{
        $warning_msg[] = 'order could not be updated';
        header('location: adminorder.php');
    }
}

if(isset($_GET['Delete'])){
    $delete_id = $_GET['Delete'];
    $delete_query = $conn->prepare("DELETE FROM `order` WHERE id = ?") or die('query failed');
    $delete_query->execute([$delete_id]);
    if($delete_query){
        $success_msg[] = 'Order has been deleted';
    }
    else{
        $warning_msg[] = 'Order could not be deleted';
    };
};

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="admin.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <title>Order</title>
</head>
<body>
    
    <?php include ('ownerheader.php');?>  
    <div class="container order">
        <section class="display" style="margin-top: 10rem;">
            <div class="display-menu">
                <div class="tab-menu">
                    <button class="tablinks" onclick="order(event,'All')" id="MainDisplayy">All Order</button>
                    <button class="tablinks" onclick="order(event,'Complete')">Completed</button>
                    <button class="tablinks" onclick="order(event,'Progress')">On Going</button>
                    <button class="tablinks" onclick="order(event,'Cancel')">Cancel</button>
                </div>

                <div id="All" class="menucontent product">
                    <h3>All</h3>
                    <div class="menuu">
                        <?php
                            $select_order = $conn->prepare("SELECT * FROM `order` ORDER BY `time` DESC  ");
                            $select_order->execute();

                            if($select_order->rowCount() > 0){
                                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                                    $select_products->execute([$fetch_order['product_id']]);
                                    if ($select_products->rowCount() > 0){
                                        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-price">Customer name: <?php echo $fetch_order['name']; ?></div>                                                                
                                <div class="product-price">Date: <?php echo $fetch_order['time']; ?></div>                                                                
                                <div class="product-price">Pickup: <?php echo $fetch_order['pickup']; ?></div>
                                <div class="product-price">Status: <?php echo $fetch_order['status']; ?></div>                                                                
                                <?php if ($fetch_order['status'] == 'in progress') { ?>
                                <a href="adminorder.php?done=<?php echo $fetch_order['id']; ?>" class="draft-btn">
                                <i class="fas fa-box-archive"></i> Done</a>
                                <a href="adminorder.php?cancel=<?php echo $fetch_order['id']; ?>" class="draft-btn">                                
                                <i class="fa-solid fa-xmark"></i> Cancel</a>
                                <?php }else{ ?>
                                    <a href="adminorder.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-trash"></i> Delete</a>
                                <?php } ?>
                            </div>
                        </div>

                        <?php 
                                };
                            }
                        }
                    }else{
                                echo "<div class='empty'>No order yet</div>";
                            }

                        ?>
                    </div>
                    
                </div>
                
                <div id="Complete" class="menucontent product">
                    <h3>Complete</h3>
                    <div class="menuu">
                        <?php
                            $select_order = $conn->prepare("SELECT * FROM `order` WHERE `status` ='completed' ORDER BY `time` DESC  ");
                            $select_order->execute();

                            if($select_order->rowCount() > 0){
                                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                                    $select_products->execute([$fetch_order['product_id']]);
                                    if ($select_products->rowCount() > 0){
                                        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-price">Customer name: <?php echo $fetch_order['name']; ?></div>                                                                
                                <div class="product-price">Date: <?php echo $fetch_order['time']; ?></div>                                                                
                                <div class="product-price">Pickup: <?php echo $fetch_order['pickup']; ?></div>
                                <div class="product-price">Status: <?php echo $fetch_order['status']; ?></div>                                                                
                                <?php if ($fetch_order['status'] == 'in progress') { ?>
                                <a href="adminorder.php?done=<?php echo $fetch_order['id']; ?>" class="draft-btn">
                                <i class="fas fa-box-archive"></i> Done</a>
                                <a href="adminorder.php?cancel=<?php echo $fetch_order['id']; ?>" class="draft-btn">                                
                                <i class="fa-solid fa-xmark"></i> Cancel</a>
                                <?php }else{ ?>
                                    <a href="adminorder.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-trash"></i> Delete</a>
                                <?php } ?>
                            </div>
                        </div>

                                <?php 
                                        };
                                    }
                                }
                            }else{
                                echo "<div class='empty'>No order yet</div>";
                            }

                        ?>
                    </div>
                    
                </div>

                <div id="Progress" class="menucontent product"> 
                    <h3>In progress</h3>
                    <div class="menuu">
                        <?php
                            $select_order = $conn->prepare("SELECT * FROM `order` WHERE `status` = 'in progress' ORDER BY `time` DESC ");
                            $select_order->execute();

                            if($select_order->rowCount() > 0){
                                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                                    $select_products->execute([$fetch_order['product_id']]);
                                    if ($select_products->rowCount() > 0){
                                        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-price">Customer name: <?php echo $fetch_order['name']; ?></div>                                                                
                                <div class="product-price">Date: <?php echo $fetch_order['time']; ?></div>                                                                
                                <div class="product-price">Pickup: <?php echo $fetch_order['pickup']; ?></div>
                                <div class="product-price">Status: <?php echo $fetch_order['status']; ?></div>                                                                
                                <?php if ($fetch_order['status'] == 'in progress') { ?>
                                <a href="adminorder.php?done=<?php echo $fetch_order['id']; ?>" class="draft-btn">
                                <i class="fas fa-box-archive"></i> Done</a>
                                <a href="adminorder.php?cancel=<?php echo $fetch_order['id']; ?>" class="draft-btn">                                
                                <i class="fa-solid fa-xmark"></i> Cancel</a>
                                <?php }else{ ?>
                                    <a href="adminorder.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-trash"></i> Delete</a>
                                <?php } ?>
                            </div>
                        </div>

                                <?php 
                                        };
                                    }
                                }
                            }else{
                                echo "<div class='empty'>No order yet</div>";
                            }

                        ?>
                    </div>
                    
                </div>

                <div id="Cancel" class="menucontent product"> 
                    <h3>Cancel Order</h3>
                    <div class="menuu">
                        <?php
                            $select_order = $conn->prepare("SELECT * FROM `order` WHERE `status` ='canceled' ORDER BY `time` DESC  ");
                            $select_order->execute();

                            if($select_order->rowCount() > 0){
                                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE id=?");
                                    $select_products->execute([$fetch_order['product_id']]);
                                    if ($select_products->rowCount() > 0){
                                        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-price">Customer name: <?php echo $fetch_order['name']; ?></div>                                                                
                                <div class="product-price">Date: <?php echo $fetch_order['time']; ?></div>                                                                
                                <div class="product-price">Pickup: <?php echo $fetch_order['pickup']; ?></div>
                                <div class="product-price">Status: <?php echo $fetch_order['status']; ?></div>                                                                
                                <?php if ($fetch_order['status'] == 'in progress') { ?>
                                <a href="adminorder.php?done=<?php echo $fetch_order['id']; ?>" class="draft-btn">
                                <i class="fas fa-box-archive"></i> Done</a>
                                <a href="adminorder.php?cancel=<?php echo $fetch_order['id']; ?>" class="draft-btn">                                
                                <i class="fa-solid fa-xmark"></i> Cancel</a>
                                <?php }else{ ?>
                                    <a href="adminorder.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-trash"></i> Delete</a>
                                <?php } ?>
                            </div>
                        </div>

                                <?php 
                                        };
                                    }
                                }
                            }else{
                                echo "<div class='empty'>No order yet</div>";
                            }

                        ?>
                    </div>
                    
                </div>
            </div>
                
        </section>
    </div>
    <script>

    function order(evt, orderStatus) {
        //Declare all variables
        var i, product, tablinks;

        //Get all elements with class="product and hide them
        product = document.getElementsByClassName("product");
        for (i = 0; i < product.length; i++) {
            product[i].style.display = "none";
        }

        //Get all elements with class="product" and remove class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace("active", "");
        }

        //SHow the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(orderStatus).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element by id="MainDisplay" and click on it
    document.getElementById("MainDisplayy").click();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <?php include 'alert.php' ?>
</body>
</html>