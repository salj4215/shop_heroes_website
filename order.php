<?php
//session_start();
//if Get store  or if not set
if(!isset($_SESSION['store']))
    $_SESSION['store']= '';
if(isset($_GET['store'])) {
    $_SESSION['store'] = $_GET['store'];
    $_SESSION['category']= '';
}
//if GET category or if not set,
if(!isset($_SESSION['category']))
    $_SESSION['category']= '';
if(isset($_GET['category']))
    $_SESSION['category']= $_GET['category'];

//add to cart           as string
if(isset($_GET['pid']) && $_GET['pid'] != "0") {
$productIDtoBEaddedTOcart = $_GET['pid'];
}

//print "Store: " . var_dump($_SESSION["store"]);
//print "Category: " . var_dump($_SESSION["category"]);
?>
<br>
    <ol>
        <a href="index.php?page=order&store=Kroger">Kroger</a>
        <a href="index.php?page=order&store=Meijer">Meijer</a>
        <p>-------------------------------------------------------</p>
        <a href="index.php?page=order&category=produce">Produce</a>
        <a href="index.php?page=order&category=snacks"">Snacks</a>
        <a href="index.php?page=order&category=cereal"">Cereal</a>
        <a href="index.php?page=order&category=chips"">Chips</a>
        <a href="index.php?page=order&category=dairy"">Dairy</a>
    </ol>
<br>
=======================================================================================================
<br>
    <?php
    //to select store choice.
    if(isset($_SESSION["store"]) && $_SESSION["store"] == 'Kroger')
        $storeWHERE=" WHERE STORES.StoreID = '1'";
    elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Meijer')
        $storeWHERE=" WHERE STORES.StoreID = '2'";
    else
        $storeWHERE=" WHERE STORES.StoreID LIKE '0'";

    //to redirect the qry with category seelction
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'produce')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Produce'";
    elseif(isset($_SESSION["category"]) && $_SESSION["category"] == 'snacks')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Snacks'";
    elseif(isset($_SESSION["category"]) && $_SESSION["category"] == 'cereal')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Cereal'";
    elseif(isset($_SESSION["category"]) && $_SESSION["category"] == 'chips')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Chips'";
    elseif(isset($_SESSION["category"]) && $_SESSION["category"] == 'dairy')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Dairy'";
    else
        $categoryWHERE="";


//replace category string to hold the attribute WHERE search, or blank.

	$qry = 'Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID' . $storeWHERE . $categoryWHERE;

	//display variable for output information
//	echo "==============================================================================================</br>storeWHERE == '" . $storeWHERE . "'";
//    echo "</br>categoryWHERE: == '" . $categoryWHERE . "'";
//    echo "</br>qry: == '" . $qry . "'";
//    echo "</br>store: == '" . $_SESSION['store'] . "'";
//    print "</br>category: == '" . $_SESSION['category'] . "'";
//    print "</br></br>============================================================================================<br>";

//call quuery
	$stmt = $pdo -> query( $qry );
	echo "<table>";
	while($row = $stmt->fetch())
	{   //new table row per product
        var_dump($row['ProductID']);
	    echo "<tr>";
	    $productID = $row['ProductID'];
		echo "<td>ProductName: " . $row['ProductName'] . "<br><br>ProductCategory: " . $row['ProductCategory'] . "<br><br>ProductUPC: " . $row['ProductUPC'] . "</td>";
		echo "<td>Description: " . $row['Description'] . "</td>";
        echo "<td>UnitPrice: $" . $row['UnitPrice'] . "<br><br>Quantity: " . $row['Quantity'] . "</td>";
        echo "<td><a href='index.php?page=order&pid=$productID'>ADD TO CART</a></td></tr>";

     //		echo "<form name='addToCart' action='index.php?page=order' method='POST'><input type='hidden' name='cart' value=$productID><td><input type='submit' name='addToCart' value='ADD TO CART'> </td></tr>";
//		echo "StoreID: " . $row['StoreID'] . "<br>";
//		echo "ProductName: " . $row['ProductName'] . "<br>";
//		echo "ProductUPC: " . $row['ProductUPC'] . "<br>";
//		echo "UnitPrice: $" . $row['UnitPrice'] . "<br>";
//		echo "ProductCategory: " . $row['ProductCategory'] . "<br>";
//		echo "Description: " . $row['Description'] . "<br>";
//		echo "Quantity: " . $row['Quantity'] . "<br>";
//		echo "</div></br>";
	}
	echo "</table>";



	echo "<br><br><br> this is the data of orderlineitems.";

$qry ="Select * from `OrderLineItems`";

$stmt = $pdo -> query( $qry );
while($row = $stmt->fetch())
{   //new table row per product
    var_dump($row);
    echo "<br>";
}

