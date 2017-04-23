<?php


//rebienenstein
//script for the app to insert a payment
//and update the associated order

if(isset($_POST['UserID'])){
    $user_id = $_POST['UserID'];
}else{
    exit;
}if(isset($_POST['OrderID'])){
    $order_id = $_POST['OrderID'];
}else{
    exit;
}if(isset($_POST['PayAmount'])){
    $pay_amt = $_POST['PayAmount'];
}else{
    exit;
}
if(isset($_POST['PaymentConfirmation'])){
    $pay_confirm_code = $_POST['PaymentConfirmation'];
}else{
    exit;
}



$sql = "SELECT * FROM CUSTOMERS WHERE UserID = '$user_id'";

require_once('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

$result = mysqli_fetch_array(mysqli_query($con, $sql));
if(isset($result)) {
    $cust_id = $result['CustID'];
    $pmt_name = $result['CustFirstN'].' '. $result['CustLastN'];
    $pmt_type = "Credit";
    $pmt_address = $result['CustAddress'];
    $pmt_date = date("Y-m-d H:i:s");
    $pmt_zip = $result['CustZip'];
    $pmt_state = "MI";
    $pmt_city = $result['CustCity'];
}
$sql = "INSERT INTO PAYMENTS SET CustID='$cust_id', OrderID='$order_id', PmtName='$pmt_name', PmtType='$pmt_type',
                                         PmtAmount='$pay_amt', PmtAddress='$pmt_address', PmtDate='$pmt_date',
                                         PmtZip='$pmt_zip', PmtState='$pmt_state', PmtCity='$pmt_city'";
if(mysqli_query($con, $sql)) {
    $sql = "UPDATE ORDERS SET OrderProcessed=1, Paid=1 ,PaymentConfirmationCode='$pay_confirm_code' WHERE OrderID = '$order_id'";
}if(mysqli_query($con, $sql)){
    echo "Success";
}else{
    echo "Fail";
}