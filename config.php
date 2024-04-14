<?php
    $db_name = 'mysql:host=localhost;dbname=fyp';
    $user_name = 'root';
    $user_password = '';

    $conn = new PDO($db_name, $user_name, $user_password);

    function unique_id(){
        return mt_rand(1000000000, 9999999999);
    } 
?>