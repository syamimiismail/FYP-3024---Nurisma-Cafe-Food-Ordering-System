<?php

@include 'C:\xampp\htdocs\fyp\config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];

if(($role_as != 0) || ($id == '')){
    header('location: login.php');
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

$get_id = $_GET['post_id'];

if(isset($_POST['delete'])){

    $p_id = $_POST['product_id'];
    // $delete_id = $_GET['delete'];
    $delete_image = $conn->prepare("DELETE FROM `new_product` WHERE id = ?") or die('query failed');
    $delete_image->execute([$delete_id]);

    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
    if($fetch_delete_image['image'] != ''){
        unlink('../imenu_img/'.$fetch_delete_image['img']);
    }

    $delete_product = $conn->prepare("DELETE FROM `new_product` WHERE id = ?");
    $delete_product->execute([$p_id]);

    header('location: admin.php');
};

if(isset($_POST['draft'])){
    $update_p_id = $_POST['update_p_id'];
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
    <link href="userLogin.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <!-- <script src="admin.js"></script> -->
    <title>View Product</title>
</head>
<body>

    <?php include ('adminheader.php');?>

    <div class="container" id="main adminmain" style="">
        <div class="container-menu">

            <section class="display-menu">
                <?php
                $select_product = $conn->prepare("SELECT * FROM `new_product` WHERE id = ?");
                $select_product->execute([$get_id]);

                if($select_product->rowCount() > 0) {
                    while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                
            ?>
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?= $fetch_product['id'];?>">
                <div class="status" style="color: <?php if($fetch_product['status']=='active')
                {echo "green";}else{echo "red";} ?>"><?= $fetch_product['status'];?>
                </div>
                <?php if($fetch_product['img'] != ''){ ?>
                    <img src="menu_img/<?= $fetch_product['img']; ?>" alt="" class="image">
                    <?php
                }?>
                <div class="title"><?= $fetch_product['name']; ?></div>
                <div class="price">RM<?= $fetch_product['price']; ?></div>
                <div class="content"><h3><?= $fetch_product['product_desc']; ?></h3></div>
                <div class="flex-btn">
                    <a href="edit_product.php?id=<?= $fetch_product['id']; ?>" class="btn">Edit</a>
                    <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this item?');">Delete</button>
                    <a href="admin.php?id=<?= $get_id; ?>" class="btn">Go back</a>
                </div>
            </form>
            <?php
                }
                }
            ?>
            </section>

            <section class="edit-form-container">

            <?php
                if(isset($_GET['edit'])){
                $edit_id = $_GET['id'];
                $edit_query = $conn->prepare("SELECT * FROM `new_product` WHERE id = ?");
                $edit_query->execute([$edit_id]);
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

<script src="admin.js"></script>
</body>
</html>