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

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_query = $conn->prepare("DELETE FROM `user_form` WHERE id = ?") or die('query failed');
    $delete_query->execute([$delete_id]);
    if($delete_query){
        $success_msg[] = 'Account has been deleted';
    }
    else{
        $error_msg[] = 'Account could not be deleted';
    };
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="admin.css"> -->
    <link rel="stylesheet" href="admin.css">
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <title>User Account</title>
</head>
<body>
    <?php include 'ownerheader.php'; ?>
    <div class="container main">
        <section class="mesage" style="margin-top: 10rem;">
        
            <div class="report-table">

                <table>
                    <thead>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Edit</th>
                    </thead>

                    <tbody>

                        <?php 
                        
                        $select_users = $conn->prepare("SELECT * FROM `user_form` WHERE `role_as` = 1");
                        $select_users->execute();

                        if($select_users->rowCount() > 0){
                            while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <tr>
                                    <td><?= $fetch_users['id']; ?></td>
                                    <td><?= $fetch_users['name']; ?></td>
                                    <td><?= $fetch_users['email']; ?></td>
                                    <td><?= $fetch_users['phone_no']; ?></td>
                                    <td>
                                        <a href="useracc.php?delete=<?php echo $fetch_users['id']; ?>" class="delete-btn"  onclick="return confirm('Are you sure you want to delete this account?');">
                                        <i class="fas fa-trash"></i>Delete</a>
                                    </td>
                                </tr>
                                <?php

                            }
                        }else{
                            echo '<div class="empty">
                            <p>No registered user account yet!</p>
                            </div> ';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="admin.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>