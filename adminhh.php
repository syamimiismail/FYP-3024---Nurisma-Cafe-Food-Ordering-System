<?php

@include 'C:\xampp\htdocs\fyp\config.php';

// session_start();

// $id = $_SESSION['id'];
// $role_as = $_SESSION['role_as'];

// if($role_as != 0){
//     header('location: login.php');
// }

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

    <?php

    if (isset($message)){
        foreach($message as $message){
            echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display=`none` ;"></i></div>';
        };
    }
    ?>

    <?php include ('adminheader.php');?>


<script src="admin.js"></script>
    <script>
        document.querySelector('#close-edit').onclick = () =>{
            document.querySelector('.edit-form-container').style.display = 'none';
            window.location.href = 'admin.php';
        }
    </script>
</body>
</html>