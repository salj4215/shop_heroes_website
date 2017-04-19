<?php

//rebienenstein
//Script to get the list of Stores
require_once ('../connect_db.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

$sql = "SELECT StoreID, StoreName, StoreAddress from STORES WHERE StoreID != 0";

$storelist = mysqli_query($con,$sql);

$store_arr = array();

while ($row = mysqli_fetch_array($storelist)) {
    array_push($store_arr,array(
            "storeID"=>$row['StoreID'],
            "storeName"=>$row['StoreName'],
            "storeAddress"=>$row['StoreAddress'])
    );
}
echo json_encode($store_arr);
mysqli_close($con);