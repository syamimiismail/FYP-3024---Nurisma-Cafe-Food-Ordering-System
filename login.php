<?php
include ('config.php');

session_start();

    if (isset($_SESSION['id'])){
        $id = $_SESSION['id'];
    }else{
        $id = '';
        $_SESSION['name'] = '';
        $_SESSION['email'] = '';
    }

    if (isset($_POST['login'])){

    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    // Prepare and bind the SQL statement
    $select_user = $conn->prepare("SELECT * FROM `user_form` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $password]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

// Execute the statement
    if($select_user->rowCount()> 0){
        $_SESSION['id'] = $fetch_user['id'];
        $_SESSION['name'] = $fetch_user['name'];
        $_SESSION['email'] = $fetch_user['email'];
        $_SESSION['role_as'] = $fetch_user['role_as'];
        if($fetch_user['role_as'] == 0){
        //     $success_msg[] = "Welcome to Dashboard";
            header('Location: admin.php');
        //     // exit(); // Added exit after header redirect
        }elseif($fetch_user['role_as'] == 2){
                 $success_msg[] = "Welcome to Dashboard";
                header('Location: ownermainpage.php');
            //     // exit(); // Added exit after header redirect
            }
        else {
            $success_msg[] = "Logged in Successfully";
            header('Location: usermain.php');
        }
    }
    else {
        $warning_msg[] = 'Incorrect email or password!';
    }

}

// if (isset($_POST['login'])){
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     // Prepare and bind the SQL statement
//     $select_admin = $conn->prepare("SELECT id, password, role_as FROM `user_form` WHERE email = ?");
//     $select_admin->execute([$email]);

//     // Fetch the user from the database
//     $user = $select_admin->fetch(PDO::FETCH_ASSOC);

//     // Verify the password
//     if ($user && password_verify($password, $user['password'])) {
//         // $_SESSION['id'] = $user['id'];
//         // if ($user['role_as'] == 0) {
//         //     // $success_msg[] = "Welcome to Dashboard";
//              header('Location: ownermainpage.php');
//         //     exit(); // Added exit after header redirect
//         // } else {
//         //     // $success_msg[] = "Logged in Successfully";
//         //     header('Location: usermain.php');
//         //     exit(); // Added exit after header redirect
//         // }
//     } else {
//         $warning_msg[] = 'Incorrect email or password!';
//     }
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userLogin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Login to Your Account Now!</title>
</head>
<body>
    <div class="main">
        <section>
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <img src="logo.png" alt="" style="margin:auto;">
                    <h3>Login</h3>
                    <div class="input-field">
                        <label for="">Email <sup>*</sup></label>
                        <input type="email" name="email"  class="form-label"
                        required placeholder="Enter your email" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="">Password <sup>*</sup></label>
                        <input type="password" name="password"  class="form-label"
                        required placeholder="Enter your password" oninput="this.value.replace(/\s/g,'')">
                    </div>
                    <button type="submit" name="login" class="btn">Login Now!</button>
                    <p>Do not have an account? <a href="register.php">Register Now</a> </p>
                </form>
            </div>
        </section>
    </div>

    <script type="text/javascript" src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <?php include ('alert.php'); ?>
</body>
</html>