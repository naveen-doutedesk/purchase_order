<?php

require_once "connection.php";

$purchase_order_id = $_POST["id"];


$sql = "DELETE FROM `purchase_order` WHERE `purchase_order`.`id` = {$purchase_order_id}";

if(mysqli_query($con, $sql)){
  echo 1;
}else{
  echo 0;
}

 ?>