<?php

include 'config.php';

session_start();

$id = $_SESSION['id'];
$role_as = $_SESSION['role_as'];

if($role_as != 0){
    header('location: login.php');
}

if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}

if (isset($_POST['report'])) {
    $date = $_POST['date'];
    $sales = $_POST['sales'];
    $cost = '0';
    $report = $_POST['food_report'];
    $id = $_POST['staff_id'];
    $report = $_POST['food_report'];

    if($report == '')
    {
        $report = '-';
    }

    $insert_query = $conn->prepare("INSERT INTO `finance` (date, sales,cost, food_report, staff_id) VALUES (?, ?, ?, ?, ?)");
    $insert_query->execute([$date, $sales, $cost, $report, $id]);

    if($insert_query){
        $success_msg[] = 'Your report has been sent! Thank You';
    }
    else{
        $error_msg[] = 'Your report could not been sent. Please try again';
    }
}


if(isset($_POST['update_report'])){
    $update_p_sales = $_POST['update_p_sales'];
    $update_p_report = $_POST['update_p_report'];
    $update_staff_id = $_POST['update_staff_id'];

    $update_query = $conn->prepare("UPDATE `finance` SET date = ?, sales = ?, food_report = ?, staff_id = ? WHERE `date` = ?");
    $update_query->execute([$update_p_sales, $update_p_report, $update_staff_id]);

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
    <script src="admin.js"></script>
    <title>Finance Report</title>
</head>
<body>
    
    <?php include ('adminheader.php');?>

    <div class="container">
        <div class="box-report" style="margin-top: 10rem;">
            <form action="" method="post">
                <div class="reportBox">
                    <h3>Select Date: 
                    <input type="date" id="datePickerId" name="date"  placeholder="dd-mm-yyyy" class="datepicker"required></h3>
                    <h3>Enter today sales amount: 
                    <input type="number" id="sales" name="sales"  min="0" step="0.01"  placeholder="RM"required></h3>
                    <h3 class="report">Report: <br>
                    <textarea id="report" name="food_report" cols="60" rows="5" placeholder="Write your report here..."></textarea></h3>
                    <h3>Please choose your name: 
                    <select name="staff_id" class="box" required>
                        <option value="" disabled selected>Name</option>
                        <option value="1">Muhammad Syafiq</option>
                        <option value="2">Syahira Putri</option>
                        <option value="3">Bob</option>
                    </select></h3>
                    <input type="submit" value="Submit report" name="report" class="btn">
                </div>
            </form>
        </div>

        <div class="report-table">

            <table>
                <thead>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Sales</th>
                    <th>Report</th>
                    <th>Edit</th>
                </thead>

                <tbody>

                    <?php 
                    
                    $select_report = $conn->prepare("SELECT * FROM `finance` ORDER BY date DESC");
                    $select_report->execute();

                    if($select_report->rowCount() > 0){
                        while($fetch_report = $select_report->fetch(PDO::FETCH_ASSOC)){
                        $select_data = $conn->prepare("SELECT * FROM `finance` WHERE date = ?");
                        $select_data->execute([$fetch_report['date']]);
                        $report_id = $select_data->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <tr>
                        <td><?php echo $report_id['date']; ?></td>
                        <td><?php
                                $staff_id = $report_id['staff_id'];
                                switch ($staff_id) {
                                    case 1:
                                        echo "Muhammad Syafiq";
                                        break;
                                    case 2:
                                        echo "Syahira Putri";
                                        break;
                                    case 3:
                                        echo "Bob";
                                        break;
                                    default:
                                        echo "Unknown";
                                }
                        
                        ?>                        
                        </td>
                        <td><?php echo $report_id['sales']; ?></td>
                        <td><?php echo $report_id['food_report']; ?></td>
                        <td>
                            <a href="adminreport.php?edit=<?php echo $report_id['date']; ?>" class="option-btn">
                            <i class="fas fa-edit"></i>Update</a>
                        </td>
                    </tr>
                    <?php
                        } 
                    }else{
                        echo "<div class='empty'>No new reports</div>";
                    }
                    ?>

                </tbody>
            </table>
        </div>

        <div class="display" style="margin: 10px;">
            <section class="edit-form-container">

            <?php
                if(isset($_GET['edit'])){
                $edit_date = $_GET['edit'];
                $select_product = $conn->prepare("SELECT * FROM `finance` WHERE date = ?");
                $select_product->execute([$edit_date]);
                if($select_product->rowCount() > 0){
                    while($fetch_edit = $select_product->fetch(PDO::FETCH_ASSOC)){

            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_p_date" value="<?php echo $fetch_edit['date']; ?>">
                <input type="number" min="0" step="0.01"  class="box" required name="update_p_sales" value="<?php echo $fetch_edit['sales']; ?>">
                <input type="text"class="box" required name="update_p_report" value="<?php echo $fetch_edit['food_report']; ?>">
                <select name="update_staff_id" class="box" required>
                        <option value="" disabled selected>Name</option>
                        <option value="1">Muhammad Syafiq</option>
                        <option value="2">Syahira Putri</option>
                        <option value="3">Bob</option>
                </select>
                <input type="submit" value="Update the product" name="update_report" class="option-btn">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        document.querySelector('#close-edit').onclick = () =>{
            document.querySelector('.edit-form-container').style.display = 'none';
            window.location.href = 'admin.php';
        }
    </script>
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <?php include ('alert.php'); ?>
</body>
</html>