<?php include('config.php');

session_start();

if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
}else{
    $user_id = '';
    $_SESSION['name'] = '';
    $_SESSION['email'] = '';
}

if(isset($_POST['register'])){

    // $id = unique_id();

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $role = '1';
    $cpass = sha1($_POST['cpassword']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `user_form` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        $warning_msg[] = 'User already exist!';
    }
    else {
        if($password != $cpass){
        $warning_msg[] = 'Password not matched!';
        }
        else {
            $insert_user = $conn->prepare("INSERT INTO `user_form`( email,name, password, role_as, phone_no) VALUES (?,?,?,?,?)");
            $insert_user->execute([$email,$name, $password, $role, $number]);
            $select_user = $conn->prepare("SELECT * FROM `user_form` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $password]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if ($select_user->rowCount() > 0){
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
            }
            
            $success_msg[] = 'Register Successful';
            header('Location: login.php');
        }
    }
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userLogin.css">
    <title>Create an Account Now!</title>
</head>
<body>
    <div class="main">
        <section>
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <img src="logo.png" alt="">
                    <h3>Register Now</h3>
                    <div class="input-field">
                        <label for="">Name <sup>*</sup></label>
                        <input type="text" name="name"  class="form-label"
                        required placeholder="Enter your name" oninput="this.value.replace(/\/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="">Email <sup>*</sup></label>
                        <input type="email" name="email"  class="form-label"
                        required placeholder="Enter your email" oninput="this.value.replace(/\/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="">Phone number <sup>*</sup></label>
                        <input type="number" name="number"  class="form-label"
                        required placeholder="Enter your number" oninput="this.value.replace(/\/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="">Password <sup>*</sup></label>
                        <input type="password" name="password"  class="form-label"
                        required placeholder="Enter your password" oninput="this.value.replace(/\/g,'')">
                    </div>
                    <div class="input-field">
                        <label for="">Confirm password <sup>*</sup></label>
                        <input type="password" name="cpassword"  class="form-label"
                        required placeholder="Confirm password" oninput="this.value.replace(/\/g,'')">
                    </div>
                    <button type="submit" name="register" class="btn">Register Now!</button>
                    <p>Already have an account? <a href="login.php">Login Now</a> </p>
                </form>
            </div>
        </section>
    </div>

    <script type="text/javascript" src="script.js"></script>
    
    <?php include('alert.php'); ?>
</body>
</html>