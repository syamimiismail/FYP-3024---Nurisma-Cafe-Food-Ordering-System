<?php

@include 'C:\xampp\htdocs\fyp\config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];

if($role_as == 1){
    header('location: login.php');
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

if(isset($_POST['add_product'])){
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $category = $_POST['category'];
    $p_desc = $_POST['p_desc'];
    $status = 'active';
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $p_image = $_FILES['p_image']['name'];
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = 'menu_img/'.$p_image;

    $insert_product = $conn->prepare("INSERT INTO `new_product`(name, price, img, category, product_desc, status) VALUES(?,?,?,?,?,?)") or die('query failed') ;
    $insert_product->execute([$p_name, $p_price, $p_image, $category, $p_desc, $status]);
    $success_msg[] = 'Product add succesfully';
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_query = $conn->prepare("DELETE FROM `new_product` WHERE id = ?") or die('query failed');
    $delete_query->execute([$delete_id]);
    if($delete_query){
        $success_msg[] = 'Product has been deleted';
    }
    else{
        $error_msg[] = 'Product could not be deleted';
    };
};

if(isset($_GET['draft'])){
    $update_p_id = $_GET['draft'];
    $update_p_status = 'deactive';

    $update_query = $conn->prepare("UPDATE `new_product` SET status = ? WHERE id = ?");
    $update_query->execute([$update_p_status, $update_p_id]);

    if($update_query){
        $success_msg[] = 'Product updated successfully';
        header('location: admin.php');
    }
    else{
        $error_msg[] = 'Product could not be updated';
        header('location: admin.php');
    }
}

if(isset($_GET['unarchive'])){
    $update_p_id = $_GET['unarchive'];
    $update_p_status = 'active';

    $update_query = $conn->prepare("UPDATE `new_product` SET status = ? WHERE id = ?");
    $update_query->execute([$update_p_status, $update_p_id]);

    if($update_query){
        $success_msg[] = 'Product updated successfully';
        header('location: admin.php');
    }
    else{
        $error_msg[] = 'Product could not be updated';
        header('location: admin.php');
    }
}

if(isset($_POST['update_product'])){
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_price = $_POST['update_p_price'];
    $update_p_desc = $_POST['update_p_desc'];
    $update_p_category = $_POST['update_p_category'];
    $update_p_status = $_POST['update_p_status'];
    $update_p_image = $_FILES['update_p_image']['name'];
    $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
    $update_p_image_folder = 'menu_img/'.$update_p_image;

    $update_query = $conn->prepare("UPDATE `new_product` SET name = ?, price = ?, img = ?, product_desc = ?, category = ?, status = ? WHERE id = ?");
    $update_query->execute([$update_p_name, $update_p_price, $update_p_image, $update_p_desc, $update_p_category, $update_p_status, $update_p_id]);

    if($update_query){
        move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
        $success_msg[] = 'Product updated successfully';
        header('location: admin.php');
    }
    else{
        $error_msg[] = 'Product could not be updated';
        header('location: admin.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="admin.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <!-- <script src="admin.js"></script> -->
    <title>Menu</title>
</head>
<body>

    <?php include ('ownerheader.php');?>

    <div class="container" id="main adminmain">
        <div class="display" style="margin-top: 10rem;">
            <section class="add-menu">
                <form action="" method="post" class="new_product" enctype="multipart/form-data">
                    <h3>Add new product</h3>
                    <input type="text" name="p_name" placeholder="Enter the product name" class="box" required>
                    <input type="number" name="p_price" min="0" step="0.01" placeholder="Enter the product price" class="box" required>
                    <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
                    <select name="category" class="box" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="Dish">Dish</option>
                        <option value="Side Dishes">Side Dishes</option>
                        <option value="Add on">Add on</option>
                    </select>
                    <input type="text" name="p_desc" placeholder="Enter the product description" class="box" required>
                    <input type="submit" value="Add new menu" name="add_product" class="btn">
                </form>
            </section>

            <section class="display-menu">
                <div class="tab-menu">
                    <button class="tablinks" onclick="menu(event,'All')" id="MainDisplay">All Dishes</button>
                    <button class="tablinks" onclick="menu(event,'Main')">Main Dishes</button>
                    <button class="tablinks" onclick="menu(event,'Side')">Side Dishes</button>
                    <button class="tablinks" onclick="menu(event,'Other')">Add On</button>
                </div>

                <div id="All" class="menucontent">
                    <h3>All</h3>
                    <div class="menuu">
                        <?php
                            $select_products = $conn->prepare("SELECT * FROM `new_product` ORDER BY id DESC ");
                            $select_products->execute();

                            if($select_products->rowCount() > 0){
                                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-status" style="text-align:center; color: <?php if($fetch_product['status']=='active')
                                {echo "green";}else{echo "red";} ?>;"><?= $fetch_product['status']; ?></div>
                                
                                <a href="admin.php?edit=<?php echo $fetch_product['id']; ?>" class="option-btn">
                                <i class="fas fa-edit"></i> Update</a>
                                
                                <?php if ($fetch_product['status'] == 'active') { ?>
                                <a href="admin.php?draft=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                <i class="fas fa-box-archive"></i> Draft</a>
                                <?php }else{ ?>
                                    <a href="admin.php?unarchive=<?php echo $fetch_product['id']; ?>" class="draft-btn">
                                    <i class="fas fa-box-archive"></i> Unarchive</a>
                                <?php } ?>
                                <a href="admin.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>

                        <?php
                                };
                            }else{
                                echo "<div class='empty'>No product added</div>";
                            }

                        ?>
                    </div>
                    
                </div>
                
                <div id="Main" class="menucontent">
                    <h3>Main Dish</h3>
                    <div class="menuu">
                        <?php
                            $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE category='Dish' ");
                            $select_products->execute();

                            if($select_products->rowCount() > 0){
                                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-status" style="text-align:center; color: <?php if($fetch_product['status']=='active')
                                {echo "green";}else{echo "red";} ?>;"><?= $fetch_product['status']; ?></div>
                                
                                <a href="admin.php?edit=<?php echo $fetch_product['id']; ?>" class="option-btn">
                                <i class="fas fa-edit"></i> Update</a>
                                
                                <?php if ($fetch_product['status'] == 'active') { ?>
                                <a href="admin.php?draft=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                <i class="fas fa-box-archive"></i> Draft</a>
                                <?php }else{ ?>
                                    <a href="admin.php?unarchive=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-box-archive"></i> Unarchive</a>
                                <?php } ?>
                                <a href="admin.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i> Delete</a>
                                
                            </div>
                        </div>

                        <?php
                                };
                            }else{
                                echo "<div class='empty'>No product added</div>";
                            }

                        ?>
                    </div>
                    
                </div>

                <div id="Side" class="menucontent"> 
                    <h3>Side Dish</h3>
                    <div class="menuu">
                        <?php
                            $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE category ='Side Dishes' ");
                            $select_products->execute();

                            if($select_products->rowCount() > 0){
                                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-status" style="text-align:center; color: <?php if($fetch_product['status']=='active')
                                {echo "green";}else{echo "red";} ?>;"><?= $fetch_product['status']; ?></div>
                                
                                <a href="admin.php?edit=<?php echo $fetch_product['id']; ?>" class="option-btn">
                                <i class="fas fa-edit"></i> Update</a>
                                
                                <?php if ($fetch_product['status'] == 'active') { ?>
                                <a href="admin.php?draft=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                <i class="fas fa-box-archive"></i> Draft</a>
                                <?php }else{ ?>
                                    <a href="admin.php?unarchive=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-box-archive"></i> Unarchive</a>
                                <?php } ?>
                                <a href="admin.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>

                        <?php
                                };
                            }else{
                                echo "<div class='empty'>No product added</div>";
                            }

                        ?>
                    </div>
                    
                </div>

                <div id="Other" class="menucontent"> 
                    <h3>Add on</h3>
                    <div class="menuu">
                        <?php
                            $select_products = $conn->prepare("SELECT * FROM `new_product` WHERE category ='Add on'");
                            $select_products->execute();

                            if($select_products->rowCount() > 0){
                                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <div class="menulist">
                            <div class="menudisplay">
                                <img src="menu_img/<?php echo $fetch_product['img']; ?>" height="100" alt="">
                                <div class="product-name"><?php echo $fetch_product['name']; ?></div>
                                <div class="product-price">RM<?php echo $fetch_product['price']; ?></div>
                                <div class="product-status" style="text-align:center; color: <?php if($fetch_product['status']=='active')
                                {echo "green";}else{echo "red";} ?>;"><?= $fetch_product['status']; ?></div>
                                
                                <a href="admin.php?edit=<?php echo $fetch_product['id']; ?>" class="option-btn">
                                <i class="fas fa-edit"></i> Update</a>
                                
                                <?php if ($fetch_product['status'] == 'active') { ?>
                                <a href="admin.php?draft=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                <i class="fas fa-box-archive"></i> Draft</a>
                                <?php }else{ ?>
                                    <a href="admin.php?draft=<?php echo $fetch_product['id']; ?>" class="draft-btn" onclick="return confirm('Are you sure to save this product?');">
                                    <i class="fas fa-box-archive"></i> Unarchive</a>
                                <?php } ?>
                                <a href="admin.php?delete=<?php echo $fetch_product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>

                        <?php
                                };
                            }else{
                                echo "<div class='empty'>No product added</div>";
                            }

                        ?>
                    </div>
                    
                </div>
            </section>

            <section class="edit-form-container">

            <?php
                if(isset($_GET['edit'])){
                $edit_id = $_GET['edit'];
                $select_product = $conn->prepare("SELECT * FROM `new_product` WHERE id = ?");
                $select_product->execute([$edit_id]);
                if($select_product->rowCount() > 0){
                    while($fetch_edit = $select_product->fetch(PDO::FETCH_ASSOC)){

            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <img src="menu_img/<?php echo $fetch_edit['img']; ?>" height="" alt="" class="img">
                <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
                <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
                <input type="number" min="0" step="0.01" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
                <select name="update_p_category" class="box" required value="<?php echo $fetch_edit['category']; ?>">
                    <option value="" disabled selected>Select category</option>
                    <option value="Dish">Dish</option>
                    <option value="Side Dishes">Side Dishes</option>
                    <option value="Add on">Add on</option>
                </select>
                <input type="text" class="box" required name="update_p_desc" value="<?php echo $fetch_edit['product_desc']; ?>">
                <select name="update_p_status" class="box" required value="<?php echo $fetch_edit['status']; ?>">
                    <option value="" disabled selected>Select status</option>
                    <option value="active">Active</option>
                    <option value="deactive">Deactive</option>
                </select>
                <input type="file" class="box" accept="image/png, image/jpg, image/jpeg" required name="update_p_image" value="<?php echo $fetch_edit['img']; ?>">
                <input type="submit" value="Update the product" name="update_product" class="option-btn">
                <input type="reset" value="Cancel" id="close-edit" class="delete-btn">
            </form>
            <?php
                        };
                    };
                    echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script";
                };
            ?>
        

            </section>
        </div>
    </div>

    <script>
        function menu(evt, menuCat) {
        //Declare all variables
        var i, menucontent, tablinks;

        //Get all elements with class="menucontent and hide them
        menucontent = document.getElementsByClassName("menucontent");
        for (i = 0; i < menucontent.length; i++) {
            menucontent[i].style.display = "none";
        }

        //Get all elements with class="menucontent" and remove class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace("active", "");
        }

        //SHow the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(menuCat).style.display = "block";
        evt.currentTarget.className += " active";
    }

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        document.querySelector('#close-edit').onclick = () =>{
            document.querySelector('.edit-form-container').style.display = 'none';
            window.location.href = 'admin.php';
        }
    </script>
</body>
</html>