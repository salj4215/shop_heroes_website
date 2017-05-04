<?php


require_once ('../connect_db.php');

$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

$sql = "SELECT StoreID, ProductName, UnitPrice, ProductUPC, ProductCategory from PRODUCTS WHERE StoreID != 0";

$itemlist = mysqli_query($con,$sql);

$item_arr = array();

while ($row = mysqli_fetch_array($itemlist)) {
    array_push($item_arr,array(
        "storeID"=>$row['StoreID'],
        "itemName"=>$row['ProductName'],
        "itemUPC"=>$row['ProductUPC'],
        "itemPrice"=>$row['UnitPrice'],
        "itemCategory"=>$row['ProductCategory'])
 );
}
    
    echo json_encode($item_arr);
    mysqli_close($con)
?>
