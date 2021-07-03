<?php

require_once "connection.php";

$order_id = $_POST["id"];
$product_price = $_POST["product_price"];
$product_name = $_POST["product_name"];
$is_approved = $_POST["is_approved"];


$sqlupdate = "UPDATE `purchase_order` SET `product_name` = '{$product_name}', `product_price` = '{$product_price}', `is_approved` = '{$is_approved}' WHERE `purchase_order`.`id` = {$order_id}";

if(mysqli_query($con, $sqlupdate)){
 echo 1;
}else{
 echo 0;
}

?>
