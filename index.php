<?php 
require_once "controllerUserData.php";
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        $role_id = $fetch_info['user_role_id'];

        if($status == "verified"){
            if($code != 0){
                header('Location: reset-code.php');
            }
        }else{
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: login-user.php');
}
?>


<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
    <title><?php echo $fetch_info['name'] ?> | Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
         @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    nav{
        padding-left: 100px!important;
        padding-right: 100px!important;
        background: #6665ee;
        font-family: 'Poppins', sans-serif;
    } 
    nav a.navbar-brand{
        color: #fff;
        font-size: 30px!important;
        font-weight: 500;
    }
    button a{
        color: #6665ee;
        font-weight: 500;
    }
    button a:hover{
        text-decoration: none;
    }

.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

#success-message{
  background: #DEF1D8;
  color: green;
  padding: 10px;
  margin: 10px;
  display: none;
  position: absolute;
  right: 15px;
  top: 15px;
}
#error-message{
  background: #EFDCDD;
  color: red;
  padding: 10px;
  margin: 10px;
  display: none;
  position:absolute;
  right: 15px;
  top: 15px;
}
</style>
</head>
<body>
<nav class="navbar">
    <a class="navbar-brand" href="#">Purchase Order </a>
    <button type="button" class="btn btn-light"><a href="logout-user.php">Logout</a></button>
</nav>

<h2 style="text-align: center;">Welcome <?php echo $fetch_info['name'] ?></h2>
<h1 style="text-align: center;color:green">You are login as 
<?php if ($role_id==1) {
  echo "HOD";
} elseif($role_id==2) {
  echo "Manager";
}else{
  echo "Executive";
}
 ?></h1>

<?php 
$output = "";
if (!($role_id==1)) {
 $output = '<div class="save-order container">
  <div class="form-group">
    <label for="product_name">Product Name:</label>
    <input type="text" class="form-control" placeholder="Enter Product Name" id="product_name">
  </div>
  <div class="form-group">
    <label for="product_price">Product Price:</label>
    <input type="number" class="form-control" placeholder="Enter Product Price" id="product_price">
  </div>
  <div>
    <input type="text" id="captcha_input">
    <img id="captcha_code" src="captcha_code.php" />
    <button id="btnRefresh"">Refresh Captcha</button>
  </div>
  <br>
  <input type="submit" class="btn btn-primary" id="save-button" value="Save">
</div>';
}
echo $output;

?>

<br>
<div id="error-message"></div>
<div id="success-message"></div>
<div id="purchaseData" class="container">
    
</div>

 <!-- The Modal -->
  <div class="modal myModalEdit" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Update Order</h4>
          <button type="button" class="close close-btn-edit" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body" id="update-order">
          <!-- Modal body.. -->
        </div>
        
      </div>
    </div>
  </div>

 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">

  $(document).ready(function() {
    function loadPurchaseOrder(page){
      $.ajax({
        url: "load-purchase-order.php",
        type: "POST",
        data: {page_no :page },
        success: function(data) {
          $("#purchaseData").html(data);
        }
      });
    }
    loadPurchaseOrder();

    //Pagination
    $(document).on("click",".pagination a",function(e) {
      e.preventDefault();
      var page_id = $(this).attr("id");

      loadPurchaseOrder(page_id);
    })

//Delete order
    $(document).on("click",".delete-btn", function(){
      if(confirm("Do you really want to delete this record ?")){
        var orderId = $(this).data("id");
        var element = this;

        $.ajax({
          url: "delete-purchase.php",
          type : "POST",
          data : {id : orderId},
          success : function(data){
              if(data == 1){
                $(element).closest("tr").fadeOut();
              }else{
                alert('Not deleted successfully');
              }
          }
        });
      }
    });

    //Show Modal Box
    $(document).on("click",".edit-btn", function(){
        $(".myModalEdit").show();
      var orderId = $(this).data("eid");

      $.ajax({
        url: "purchase-order-edit.php",
        type: "POST",
        data: {id: orderId },
        success: function(data) {
          $("#update-order").html(data);
        }
      })
    });

  //Hide Modal Box
    $(".close-btn-edit").on("click",function(){
      $(".myModalEdit").hide();
    });

     //Save Update order
      $(document).on("click","#edit-submit", function(){
        var order_id = $("#order_id").val();
        var product_price = $("#product_price_edit").val();
        var product_name = $("#product_name_edit").val();
        if ($('#is_approved').prop('checked')) {
         var is_approved = 1;
        }
        else{
         var is_approved = 0;
        }

        $.ajax({
          url: "purchase-order-update.php",
          type : "POST",
          data : {id: order_id, product_price: product_price, product_name: product_name,is_approved:is_approved},
          success: function(data) {
            if(data == 1){
              $(".myModalEdit").hide();
              $("#success-message").html("Update successfully.").slideDown();
              loadPurchaseOrder();
            }else{
                $("#error-message").html("Can't Save.").slideDown();
            }
          }
        })
      });

    

      $("#btnRefresh").on("click",function(e){
        $("#captcha_code").attr('src','captcha_code.php');
      });

      // Insert New order
    $("#save-button").on("click",function(e){
      e.preventDefault();
        var product_price = $("#product_price").val();
        var product_name = $("#product_name").val();
        var captcha_input = $("#captcha_input").val();
      if(product_price == "" || product_name == "" || captcha_input == ""){
        $("#error-message").html("All fields are required.").slideDown();
        $("#success-message").slideUp();
      }else{
        $.ajax({
          url: "purchase-order-insert.php",
          type : "POST",
          data : {product_price:product_price, product_name: product_name,captcha_input:captcha_input},
          success : function(data){
            if(data == 1){
              $("#success-message").html("Saved successfully.").slideDown();
              loadPurchaseOrder();
            }else if(data === 'captcha-error'){
              $("#error-message").html("code not mached").slideDown();
              
            }
            else{
              $("#error-message").html("Can't Save.").slideDown();
              
            }

          }
        });
      }

    });

  });


  i
  


  


</script>

</body>
</html>
