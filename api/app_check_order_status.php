<?php

//rebienenstein
//Script for the app to pull the status and other information for open orders tied to the account

if(isset($_POST['UserID'])){
    $user_id = $_POST['UserID'];
}else{
    exit;
}

require_once ('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$sql = "SELECT CustID FROM CUSTOMERS WHERE UserID='$user_id'";
$result = mysqli_fetch_array(mysqli_query($con, $sql));
if(isset($result)){
    $cust_id = $result['CustID'];
}else{
    echo "Customer Not Found";
    exit;
}

$sql = "SELECT OrderID FROM ORDERS WHERE CustID='$cust_id' AND SignedFor !=1 AND StoreID !=0";



$open_orders = mysqli_query($con,$sql);


$order_arr = array();


while ($row = mysqli_fetch_array($open_orders)) {
    $id = $row['OrderID'];

    $sql = "SELECT OrderID, ProductID, ProductQuantity FROM ORDER_LINE_ITEMS WHERE OrderID='$id'";
    $order_items = mysqli_query($con,$sql);

    while ($item = mysqli_fetch_array($order_items)){
        $p_id = $item['ProductID'];
        $sql_upc = "SELECT ProductUPC FROM PRODUCTS WHERE ProductID='$p_id'";
        $upc = mysqli_fetch_array(mysqli_query($con, $sql_upc));

        $sql_order_status = "SELECT ShoppingStarted, BoxingItems, WaitingForDelivery, InRoute, Delivered FROM ORDERS 
                              WHERE OrderID='$id'";

        $status = mysqli_fetch_array(mysqli_query($con, $sql_order_status));

        //Just by adding the status codes we are interested in, we can determine
        //the current status by the integer in the app.
        //It saves having to run many string.equals checks, but assumes the status codes
        //will progress linearly.
        $status_number = $status[0]+$status[1]+$status[2]+$status[3]+$status[4];

        array_push($order_arr,array(
                "orderID"=>$id,
                "statusNumber"=>$status_number,
                "upc"=>$upc[0],
                "quantity"=>$item['ProductQuantity']
        )
    );
    }
}
echo json_encode($order_arr);
mysqli_close($con);