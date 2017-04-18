<?php//set store to blank
if(! isset($_GET['store']))
    $store = "";
else
    $store = $_GET['store'];
if(! isset($_GET['category']))
    $category = "";
else
    $category = $_GET['category'];
?>
<div id="prod_navigation">
    //store filter
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">STORES</button>
        <div class="dropdown-content">
            <a href="index.php?page=order&store=Kroger&category=<?php$category;?>">Kroger</a>
            <a href="index.php?page=order&store=Meijer&category=<?php$category;?>"">Meijer</a>
        </div>
    </div>
    //category filter
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">Products</button>
        <div class="dropdown-content">
            <a href="index.php?page=order&store=/<?php print $store;?>&category=produce">Produce</a>
            <a href="index.php?page=order&store=<?php print $store;?>&category=snacks"">Snacks</a>
            <a href="index.php?page=order&store=<?php print $store;?>&category=cereal"">Cereal</a>
            <a href="index.php?page=order&store=<?php print $store;?>&category=chips"">Chips</a>
			<a href="index.php?page=order&store=<?php print $store;?>&category=dairy"">Dairy</a>
        </div>
    </div>
    <button class="chkoutbtn">Check-Out</button>
</div>

    <?php
    //to select store choice.
    if(isset($_GET['store']) && $_GET['store'] == 'Kroger')
        $storeWHERE=" WHERE STORES.StoreID = '1'";
    elseif(isset($_GET['store']) && $_GET['store'] == 'Meijer')
        $storeWHERE=" WHERE STORES.StoreID = '2'";
    else
        $storeWHERE=" WHERE STORES.StoreID LIKE '%'";


    //to redirect the qry with category seelction
    if(isset($_GET['category']) && $_GET['category'] == 'produce')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Produce'";
    elseif(isset($_GET['category']) && $_GET['category'] == 'snacks')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Snacks'";
    elseif(isset($_GET['category']) && $_GET['category'] == 'cereal')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Cereal'";
    elseif(isset($_GET['category']) && $_GET['category'] == 'chips')
        $categoryWHERE=" AND PRODUCTS.ProductCategory = 'Chips'";
    elseif(isset($_GET['category']) && $_GET['category'] == 'dairy')
        $categoryWHERE=" AND ProductCategory = 'Dairy'";
    else
        $categoryWHERE="";


    $qry = "Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID  WHERE STORES.StoreID = '1' AND PRODUCTS.ProductCategory = 'Produce'";

    $stmt = $pdo -> query( $qry );

    while($row = $stmt->fetch())
    {
        echo "ProductID: " . $row['ProductID'] . "<br>";
        echo "StoreID: " . $row['StoreID'] . "<br>";;
        echo "ProductName: " . $row['ProductName'] . "<br>";;
        echo "ProductUPC: " . $row['ProductUPC'] . "<br>";;
        echo "UnitPrice: $" . $row['UnitPrice'] . "<br>";;
        echo "ProductCategory: " . $row['ProductCategory'] . "<br>";;
        echo "Description: " . $row['Description'] . "<br>";;
        echo "Quantity: " . $row['Quantity'] . "<br>";;
        echo "</div></br>";
    }
//replace category string to hold the attribute WHERE search, or blank.

	$qry = 'Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID' . $storeWHERE . $categoryWHERE;
echo "</br>storeWHERE: ";
   var_dump($storeWHERE);
echo "</br>categoryWHERE: ";
var_dump($categoryWHERE);
echo "</br>qry: ";
var_dump($qry);
echo "</br>store: ";
print $store;
print "</br>category: ";
var_dump($category);

	$stmt = $pdo -> query( $qry );
	
	while($row = $stmt->fetch())
	{
		echo "ProductID: " . $row['ProductID'] . "<br>";
		echo "StoreID: " . $row['StoreID'] . "<br>";;
		echo "ProductName: " . $row['ProductName'] . "<br>";;
		echo "ProductUPC: " . $row['ProductUPC'] . "<br>";;
		echo "UnitPrice: $" . $row['UnitPrice'] . "<br>";;
		echo "ProductCategory: " . $row['ProductCategory'] . "<br>";;
		echo "Description: " . $row['Description'] . "<br>";;
		echo "Quantity: " . $row['Quantity'] . "<br>";;	
		echo "</div></br>";
	}
