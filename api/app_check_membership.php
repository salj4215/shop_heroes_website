<?php

//rebienenstein
//Script to check if a customer has a valid membership

/**
 * Returns their membership expiration if current.
 *
 * Expired or null membership returns "Nope"
 */
if(isset($_POST['UserID'])){
    $user_id = $_POST['UserID'];
}else{
    exit;
}
$date  = date("Y-m-d H:i:s");
$date_obj = date_create($date);

$date_diff;


$sql = "SELECT MembershipExpiration FROM CUSTOMERS WHERE UserID = '$user_id'";

require_once('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$result = mysqli_fetch_array(mysqli_query($con, $sql));
if(isset($result)) {
    $date_diff = date_create($result[0]);
    if($date_diff > $date_obj) {
        echo $result[0];
    }else {
        echo "Nope";
        }
}else echo "Nope";
