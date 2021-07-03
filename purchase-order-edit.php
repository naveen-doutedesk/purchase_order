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


$order_id = $_POST["id"];

$sql = "SELECT * FROM purchase_order WHERE id = {$order_id}";
$result = mysqli_query($con, $sql) or die("SQL Query Failed.");
$output = "";
if(mysqli_num_rows($result) > 0 ){

  while($row = mysqli_fetch_assoc($result)){

   

    $output .= "<div class='form-group'>
      <label for='product_name'>Product Name:</label>
      <input type='text' class='form-control' id='product_name_edit' value='{$row["product_name"]}'>
      <input  id='order_id' value='{$row["id"]}' hidden>
    </div>
    <div class='form-group'>
      <label for='product_price'>Product Price:</label>
      <input type='number' class='form-control' id='product_price_edit' value='{$row["product_price"]}'>
    </div>";
    if ($role_id == 1) {
     $checked = ($row["is_approved"] == 1) ? 'checked' : '';
     $output .="<div class='form-group form-check'>
               <label class='form-check-label'>
                <input class='form-check-input' id='is_approved' type='checkbox' $checked> Approved
               </label>
               </div>";
    }
    
    $output .="<input type='submit' class='btn btn-primary' id='edit-submit' value='save'>";

  }

    echo $output;
}else{
    echo "<h2>No Record Found.</h2>";
}

?>
