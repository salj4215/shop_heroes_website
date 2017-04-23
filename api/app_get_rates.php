<?php

//rebienenstein
//php script to get the shipping and membership rates from the Products table

require_once ('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');


$sql = "SELECT ProductName, UnitPrice  from PRODUCTS WHERE StoreID = 0";


$itemlist = mysqli_query($con,$sql);

$item_arr = array();


while ($row = mysqli_fetch_array($itemlist)) {
    array_push($item_arr,array(
            "itemName"=>$row['ProductName'],
            "itemPrice"=>$row['UnitPrice'],
            )
    );
}

echo json_encode($item_arr);
mysqli_close($con);