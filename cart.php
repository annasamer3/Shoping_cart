<?php
require('connect.php'); ?>
<?php
session_start();
if(isset($_POST["add"])){
    if(isset($_SESSION["cart"])){
        $item_array_id = array_column($_SESSION["cart"], "product_id");
        if(!in_array($_GET["id"], $item_array_id)){
            $count = count($_SESSION["cart"]);
            $item_array= array(
                'product_id' => $_GET["id"],
                'item_name' => $_POST["pname"],
                'product_price' => $_POST["price"],
                'item_quantity' => $_POST["quantity"],

            );
            $_SESSION["cart"][$count]= $item_array;
            echo '<script>window.location="cart.php"</script>';
        }
        else{
         echo '<script>alert("product is already added to cart")</script>';
         echo '<script>window.location="cart.php"</script>';
        } 

    }
    else{
        $item_array= array(
            'product_id' => $_GET["id"],
                'item_name' => $_POST["pname"],
                'product_price' => $_POST["price"],
                'item_quantity' => $_POST["quantity"],
        );
        $_SESSION["cart"][0]= $item_array;
    }

}
if(isset($_GET["action"])){
    if($_GET["action"]=="delete"){
        foreach ($_SESSION["cart"] as $key => $value) {
            if ($value["product_id"]== $_GET["id"]) {
                unset($_SESSION["cart"][$key]);
                echo '<script>alert("product has been removed")</script>';
                echo '<script>window.location="cart.php"</script>';
                # code...
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
<div class="container" style="width: 65%">
    <h2>Shopping cart</h2>
    <?php
    $query= "SELECT * FROM `product` ORDER BY id ASC";
    $result= mysqli_query($conn,$query);
    if(mysqli_num_rows($result) > 0){
        while($row= mysqli_fetch_array($result))
        {

    ?>
    <div class="col-md-3" style="float: left">
        <form method="post" action="cart.php?action=add&id=<?php echo $row['id']?>">
            <div class="product">
                <img src="<?php echo $row['image']?>" class="img-responsive">
                <h5 class="text-info"> <?php  echo $row['pname']; ?></h5>
                <h5 class="text-danger"> <?php echo $row['price']; ?></h5> 
                <input type="text" name="quantity" value="1" class="form-control">
                <input type="hidden" name="pname" value="<?php echo $row['pname'] ?>">
                <input type="hidden" name="price" value="<?php echo $row['price'] ?>">
                <input type="submit" name="add" style="margin-top: 5px" class="btn btn-success" value="ADD TO CART">
            </div>
        </form>
    </div>
    <?php 
            }
        }
    ?>
    <div style="clear:both">
        
        <h3 class="title2">Shopping Cart details</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
            <tr>
                <th width="30%">Product Name</th>
                <th width="10%">Quantity</th>
                <th width="10%">Price</th>
                <th width="13%">Total price</th>
                <th width="17%">Item Removed</th>
            </tr>  
            <?php 
            if(!empty($_SESSION["cart"])){
                $total=0;
                foreach ($_SESSION["cart"] as $key => $value) {
                    # code...
            ?>
            <tr>
                <td><?php echo $value["item_name"]; ?></td>
                <td><?php echo $value["item_quantity"]; ?></td>
                <td>$<?php echo $value["product_price"]; ?></td>
                <td>$<?php echo ($value["item_quantity"] * $value["product_price"]); ?></td>
                <td>
                    <a href="cart.php?action=delete&id=<?php echo $value["product_id"]; ?>"><span class="text-danger">REMOVE ITEM</span></a>
                </td>
            </tr>
            <?php
            $total = $total + ($value["item_quantity"] * $value["product_price"]);
        }
             ?>
             <tr>
                 <td colspan="3" align="right">Total</td>
                 <th align="right">$<?php echo number_format ($total) ?></th>
                 <td></td>
             </tr>
             <?php
             } ?>
        </div>
</table>
    </div>
</div>
</body>
</html>