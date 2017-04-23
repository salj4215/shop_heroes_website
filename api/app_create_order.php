<?php
//reienenstein
//Script for the app to create an order
//if successful we should get the new OrderID back

/**
 * Expected Inputs
 *
 * UserID
 * StoreID
 * ShipType
 * ShipAddress
 * OrderCity
 * OrderZip
 * OrderTotal
 *
 */
if(isset($_POST['UserID'])){
    $user_id = $_POST['UserID'];
}else{
    exit;
}
if(isset($_POST['StoreID'])){
    $store_id=$_POST['StoreID'];
}else{
    exit;
}
if(isset($_POST['ShipType'])){
    $ship_type=$_POST['ShipType'];
}else{
    exit;
}
if(isset($_POST['ShipAddress'])) {
    $ship_address = $_POST['ShipAddress'];
}else{
    exit;
}

if(isset($_POST['OrderCity'])){
    $order_city = $_POST['OrderCity'];
}else{
    exit;
}
if(isset($_POST['OrderZip'])){
    $order_zip = $_POST['OrderZip'];
}else{
    exit;
}
if(isset($_POST['OrderTotal'])){
    $order_total = $_POST['OrderTotal'];
}else{
    exit;
}

$order_date = date("Y-m-d H:i:s");



$cust_id;//to be looked up
//set all of the order status codes to 0
$order_processed = 0;
$shopping_started = 0;
$boxing_items = 0;
$waiting_delivery = 0;
$in_route = 0;
$delivered = 0;
$signed_for = 0;
$paid = 0;



$sql = "SELECT `CustID` FROM CUSTOMERS WHERE UserID = '$user_id'";

require_once('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$result = mysqli_fetch_array(mysqli_query($con, $sql));
$cust_id = $result[0];
if(isset($cust_id)){//if we found a CustID from the UserID

    $sql = "INSERT INTO ORDERS(CustID, StoreID, ShipType, ShipAddress, OrderDate, OrderCity, OrderZip,
                                OrderProcessed, ShoppingStarted, BoxingItems, WaitingForDelivery, InRoute, Delivered, SignedFor, Paid,
                                SignedTime, OrderTotal, PaymentConfirmationCode)
                        VALUES('$cust_id', '$store_id', '$ship_type', '$ship_address', '$order_date', '$order_city', '$order_zip',
                                '$order_processed', '$shopping_started', '$boxing_items', '$waiting_delivery', '$in_route', '$delivered', '$signed_for', '$paid',
                                null, '$order_total', null)";

    if(mysqli_query($con, $sql)){
        $sql = "SELECT OrderID FROM ORDERS WHERE CustID='$cust_id' AND OrderDate='$order_date'";
        $result = mysqli_fetch_array(mysqli_query($con, $sql));
        if(isset($result)) {
            echo $result[0];
        }else{
            echo "Order creation failed";
        }
    }else{
        echo "Order creation failed";
    }
}else{
    echo "Failed to find customer";
}mysqli_close($con);