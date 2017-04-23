<?php

//rebienenstein
//Script to renew a Customer's ShopHeroes Membership

/**
 * Expects:
 *  UserID
 *  MembershipTerm -- "Monthly" or "Yearly"
 *
 * echos the new MembershipExpiration date,  or "Fail"
 */
if(isset($_POST['UserID'])){
    $user_id = $_POST['UserID'];
}else{
    exit;
}
if(isset($_POST['MembershipTerm'])){
    $m_term = $_POST['MembershipTerm'];
}else{
    exit;
}


//current date
$thedate = date("Y-m-d H:i:s");
$date_obj = date_create($thedate);
if($m_term=="Monthly") {
    date_modify($date_obj, "+30 days");
}else if($m_term=="Yearly"){
    date_modify($date_obj, "+1 year");
}
$expiration = date_format($date_obj, "Y-m-d H:i:s");

$sql = "UPDATE CUSTOMERS SET MembershipExpiration='$expiration'WHERE UserID ='$user_id'";

require_once('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

if(mysqli_query($con, $sql)){
    $sql = "SELECT MembershipExpiration FROM CUSTOMERS WHERE UserID = '$user_id'";
    $result = mysqli_fetch_array(mysqli_query($con, $sql));
    if(isset($result)){
        echo $result[0];
    }else{
        echo "Fail";
    }
}else{
    echo "Fail";
}
mysqli_close($con);