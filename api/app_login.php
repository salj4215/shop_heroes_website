<?php	 
$user = $_POST['Username'];
$pass = $_POST['Password'];

$sql = "SELECT * FROM USERS WHERE Username='$user' AND Password='$pass'";
		
		 
require_once('connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


		
$result = mysqli_query($con,$sql);
		
		
$check = mysqli_fetch_array($result);
				
	if(isset($check)){
		$outcome = $check[0];
	}else{
		$sql =  "SELECT * FROM USERS WHERE Username='$user'";
		$result = mysqli_query($con,$sql);
		$check = mysqli_fetch_array($result);
			
		if(isset($check)){
			$outcome =  "Invalid password";
		}else{
			$outcome = "Account not found";
		}
	}
echo $outcome;
mysqli_close($con);