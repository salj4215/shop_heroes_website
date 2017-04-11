<?php

//Script to grab customer info (Android)
//Called from ActivityMain on app start,
//after login to check for missing customer info
$user_id = $_POST['userID'];
echo $user_id;

$sql = "SELECT * FROM CUSTOMERS WHERE UserID='$user_id'";
				 
require_once('connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');



$result = mysqli_query($con,$sql);
$res_arr = array();

while ($row = mysqli_fetch_array($result)) {
    array_push($res_arr,array(
            "custID"=>$row['CustID'],
            "firstN"=>$row['CustFirstN'],
            "lastN"=>$row['CustLastN'],
            "address"=>$row['CustAddress'],
            "city"=>$row['CustCity'],
            "zip"=>$row['CustZip'],
            "state"=>$row['CustState'],
            "phone"=>$row['CustPhone'],
            "membershipExp"=>$row['MembershipExpiration'])
    );
}
echo json_encode($res_arr);
mysqli_close($con);