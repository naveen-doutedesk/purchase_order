<?php

  require_once "connection.php";
  require_once "controllerUserData.php";

  $limit_per_page = 5;

  $page = "";
  if(isset($_POST["page_no"])){
    $page = $_POST["page_no"];
  }else{
    $page = 1;
  }

  $offset = ($page - 1) * $limit_per_page;

$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $role_id = $fetch_info['user_role_id'];
        $user_id = $fetch_info['id'];
        if ($role_id == 3) {
          $getdatasql = "SELECT * FROM purchase_order WHERE user_id = {$user_id} LIMIT {$offset},{$limit_per_page}";
          $getdatasql_without_limit = "SELECT * FROM purchase_order WHERE user_id = {$user_id}";
          $button_class = "display:none";
        } elseif($role_id == 1) {
          $getdatasql = "SELECT * FROM purchase_order LIMIT {$offset},{$limit_per_page}";
          $getdatasql_without_limit = "SELECT * FROM purchase_order";
          $button_class = "";
        }
        elseif($role_id == 2) {
          $getdatasql = "SELECT * FROM purchase_order LIMIT {$offset},{$limit_per_page}";
          $getdatasql_without_limit = "SELECT * FROM purchase_order";
          $button_class = "";
        }   
    }
}else{
   header('Location: login-user.php');
}

  $datasql = $getdatasql;
  $result = mysqli_query($con,$datasql) or die("Query Unsuccessful.");
  $output= "";
  if(mysqli_num_rows($result) > 0){
    $output .= '<table class="table">
      <thead>
          <tr>
              <th>Product Name</th>
              <th>Price</th>
              <th>Delete</th>
              <th>Edit</th>
          </tr>
      </thead>';
      $output .= "<tbody>";

      while($row = mysqli_fetch_assoc($result)) {
        
        $output .= "<tr><td>{$row["product_name"]}</td><td> {$row["product_price"]}</td><td><button style='{$button_class}' class='delete-btn' data-id='{$row["id"]}'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td><td><button style='{$button_class}' class='edit-btn' data-eid='{$row["id"]}'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></td></tr>";
      }
      $output .= "</tbody>";
    $output .= "</table>";

    $sql_total = $getdatasql_without_limit;
    $records = mysqli_query($con,$sql_total) or die("Query Unsuccessful.");
    $total_record = mysqli_num_rows($records);
    $total_pages = ceil($total_record/$limit_per_page);

    $output .='<div class="pagination">';

    for($i=1; $i <= $total_pages; $i++){
      if($i == $page){
        $class_name = "active";
      }else{
        $class_name = "";
      }
      $output .= "<a class='{$class_name}' id='{$i}' href=''>{$i}</a>";
    }
    $output .='</div>';

    echo $output;
  }else{
    echo "<h2>No Record Found.</h2>";
  }
?>
