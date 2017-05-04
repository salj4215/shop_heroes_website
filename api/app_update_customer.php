<?php

//Script to update the customer info, or create a new record
$user_id=$_POST['UserID'];
$cust_first=$_POST['CustFirstN'];
$cust_last=$_POST['CustLastN'];
$cust_address=$_POST['CustAddress'];
$cust_city=$_POST['CustCity'];
$cust_zip=$_POST['CustZip'];
$cust_phone=$_POST['CustPhone'];


$sql = "SELECT * FROM CUSTOMERS WHERE UserID = '$user_id'";

require_once ('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB);// or die('Unable to Connect');

$cust_exists = (mysqli_num_rows(mysqli_query($con,$sql)));

if($cust_exists>0) {//if they exist, update the row
    $sql = "UPDATE CUSTOMERS SET CustFirstN='$cust_first', CustLastN='$cust_last',
CustAddress='$cust_address', CustCity='$cust_city',CustZip='$cust_zip',CustPhone='$cust_phone' WHERE UserID='$user_id'";
    $result = (mysqli_query($con,$sql));
}
else {//else, insert new row
    $sql = "INSERT INTO CUSTOMERS(UserID, CustFirstN, CustLastN, CustAddress, CustCity, CustZip, CustPhone )
 VALUES($user_id,'$cust_first','$cust_last','$cust_address','$cust_city','$cust_zip','$cust_phone')";
    $result = (mysqli_query($con,$sql));
}
$sql = "SELECT * FROM CUSTOMERS WHERE UserID = '$user_id'";
$cust_exists = (mysqli_num_rows(mysqli_query($con,$sql)));
if($cust_exists>0){
    echo "Success";
}else{
    echo "Fail";
}
mysqli_close($con);