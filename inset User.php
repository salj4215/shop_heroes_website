<?php
$user = $_POST['Username'];
$pass = $_POST['Password'];

//Script to register a new User
//It returns the new auto-generated UserID if successful

//Encrypt the password using SHA512.
$pass = hash("SHA512", $pass, false);

$sql = "INSERT INTO USERS (Username, Password)VALUES('$user','$pass')";


require_once("connect_db.php");
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

if (mysqli_query($con, $sql)) {
    $sql =  "SELECT UserID FROM USERS WHERE Username='$user'";
    $result = mysqli_query($con,$sql);
    $res_id = mysqli_fetch_array($result);
    echo $res_id[0];
} else {
    echo "Fail";
}
mysqli_close($con);
