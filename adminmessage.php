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

if(isset($_POST['delete'])){
    $delete_id = $_POST['delete_id'];
    $delete_query = $conn->prepare("SELECT * FROM `message` WHERE id = ?") or die('query failed');
    $delete_query->execute([$delete_id]);
    if($delete_query->rowCount() > 0){
        $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
        $delete_message->execute(['$delete_id']);
        $success_msg[] = 'Message has been deleted';
    }
    else{
        $warning_msg[] = 'Message could not be deleted';
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
    <title>Contact Us Form</title>
</head>
<body>
    <?php 
    include 'ownerheader.php'; ?>
    <div class="container main">
        <section class="message"  style="margin-top: 10rem;">
            <h1 class="heading">Unread message</h1>
        </section>
        <div class="report-table">

                <table>
                    <thead>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Edit</th>
                    </thead>

                    <tbody>

                        <?php 
                        
                        $select_message = $conn->prepare("SELECT * FROM `message`");
                        $select_message->execute();

                        if($select_message->rowCount() > 0) {
                            while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
                        ?>

                        <tr>
                            <td><?php echo $fetch_message['date']; ?></td>
                            <td><?php echo $fetch_message['name']; ?></td>
                            <td><?php echo $fetch_message['email']; ?></td>
                            <td><?php echo $fetch_message['subject']; ?></td>
                            <td><?php echo $fetch_message['message']; ?></td>
                            <td>
                                <a href="adminmessage$select_message.php?delete=<?php echo $fetch_message['date']; ?>" class="option-btn">
                                <i class="fas fa-trash"></i>Delete</a>
                            </td>
                        </tr>
                        <?php
                            } 
                        }else{
                            echo "<div class='empty'>No new message$select_messages</div>";
                        }
                        ?>

                    </tbody>
                </table>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="admin.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>