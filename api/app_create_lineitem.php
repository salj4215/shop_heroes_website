<?php

//rebienenstein
//Script for the app to write a row to OrderLineItems, after creating an order.


if(isset($_POST['OrderID'])){
    $order_id = $_POST['OrderID'];
}else{
    exit;
}
if(isset($_POST['StoreID'])){
    $store_id=$_POST['StoreID'];
}else{
    exit;
}
if(isset($_POST['ProductUPC'])){
    $upc = $_POST['ProductUPC'];
}else{
    exit;
}if(isset($_POST['ProductQuantity'])){
    $product_quantity = $_POST['ProductQuantity'];
}else{
    exit;
}

$product_id;
$product_price;
$line_price;

$sql = "SELECT * FROM PRODUCTS WHERE ProductUPC = '$upc' AND StoreID = '$store_id'";

require_once('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$result = mysqli_fetch_array(mysqli_query($con, $sql));
if(isset($result)){
    $product_id=$result['ProductID'];
    $product_price=$result['UnitPrice'];
    $line_price = $product_price*$product_quantity;
}
$sql = "INSERT INTO ORDER_LINE_ITEMS SET OrderID='$order_id', StoreID='$store_id', ProductID='$product_id',
 ProductPrice = '$product_price', ProductQuantity='$product_quantity', LinePrice='$line_price'";
if(mysqli_query($con, $sql)){
    echo "Success";
}else {
    echo "Fail";
}
mysqli_close($con);