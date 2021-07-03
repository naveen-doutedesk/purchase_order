<?php

require_once "connection.php";
require_once "controllerUserData.php";


$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $role_id = $fetch_info['user_role_id'];
        $user_id = $fetch_info['id'];

    }
}else{
   header('Location: login-user.php');
}


$product_price = $_POST["product_price"];
$product_name = $_POST["product_name"];


$sql = "INSERT INTO purchase_order(product_price, product_name,user_id) VALUES ('{$product_price}','{$product_name}','{$user_id}')";

if(mysqli_query($con, $sql)){
  echo 1;
}else{
  echo 0;
}

?>
