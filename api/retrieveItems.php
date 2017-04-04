<?php


//TODO: get login credentials from DBA Silver

require_once ('../connect_db.php');


$sql = "SELECT ProductName, UnitPrice, ProductUPC, ProductCategory from products";

$itemlist = mysqli_query($con,$sql);

$item_arr = array();

while ($row = mysqli_fetch_array($itemlist)) {
    array_push($item_arr,array(
        "itemName"=>$row['ProductName'],
        "itemUPC"=>$row['ProductUPC'],
        "itemPrice"=>$row['UnitPrice'],
        "itemCategory"=>$row['ProductCategory'])
 );
}
    
    echo json_encode($item_arr);
    mysqli_close($con)
?>
