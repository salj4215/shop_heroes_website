<?php

//rebienenstein
//Script to increment the order status codes (for testing)

if(isset($_POST['OrderID'])){
    $order_id = $_POST['OrderID'];
}else{
    exit;
}



require_once ('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$sql = "SELECT ShoppingStarted, BoxingItems, WaitingForDelivery, InRoute, Delivered, SignedFor FROM ORDERS 
                              WHERE OrderID='$order_id'";

$status = mysqli_fetch_array(mysqli_query($con, $sql));

if($status[0]==0){
    $sql = "UPDATE ORDERS SET ShoppingStarted=1 WHERE OrderID='$order_id'";
}elseif ($status[1]==0){
    $sql = "UPDATE ORDERS SET BoxingItems=1 WHERE OrderID='$order_id'";
}elseif ($status[2]==0){
    $sql = "UPDATE ORDERS SET WaitingForDelivery=1 WHERE OrderID='$order_id'";
}elseif ($status[3]==0){
    $sql = "UPDATE ORDERS SET InRoute=1 WHERE OrderID='$order_id'";
}elseif ($status[4]==0){
    $sql = "UPDATE ORDERS SET Delivered=1 WHERE OrderID='$order_id'";
}elseif ($status[5]==0){
    $sql = "UPDATE ORDERS SET SignedFor=1 WHERE OrderID='$order_id'";
}
if(mysqli_query($con,$sql)){
    echo "Success";
}else{
    echo "Fail";
}