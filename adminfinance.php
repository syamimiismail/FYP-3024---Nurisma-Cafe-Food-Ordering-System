<?php

@include 'config.php';

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="admin.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d938dc7e27.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- <script src="admin.js"></script> -->
    <title>Finance</title>
</head>
  <body>
    <div class="container">


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Cost'],

          <?php
            $query= $conn->prepare("Select * FROM finance");
            $query->execute();
            if($query->rowCount() > 0){
              while($fetch_query = $query->fetch(PDO::FETCH_ASSOC)){
                $date = $fetch_query['date'];
                $sale = $fetch_query['sales'];
                $cost = $fetch_query['cost'];
                ?>
                ['<?php echo $date;?>',<?php echo $sale;?>,<?php echo $cost;?>],
                <?php
            }
          }
            ?>
        ]);

        var options = {
          title: 'Total Sales and Budget',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
    <div id="curve_chart" style="width: 950px; height: 500px"></div>
    
     <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    

    <div id="curve_chart" style="width: 950px; height: 500px"></div> -->

    </div>
  </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</html>
