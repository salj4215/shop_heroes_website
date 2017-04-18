<div id="prod_navigation">
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">Products</button>
        <div class="dropdown-content">
            <a href="index.php?page=order&category=produce">Produce</a>
            <a href="index.php?page=order&category=snacks"">Snacks</a>
            <a href="index.php?page=order&category=cereal"">Cereal</a>
            <a href="index.php?page=order&category=chips"">Chips</a>
			<a href="index.php?page=order&category=dairy"">Dairy</a>


        </div>
    </div>
    <button class="chkoutbtn">Check-Out</button>
</div>

<?php
//to redirect the qry with category seelction
if(isset($_GET['category']) && $_GET['category'] == 'produce')
    $category="WHERE ProductCategory = 'Produce'";
elseif(isset($_GET['category']) && $_GET['category'] == 'snacks')
    $category="WHERE ProductCategory = 'Snacks'";
elseif(isset($_GET['category']) && $_GET['category'] == 'cereal')
    $category="WHERE ProductCategory = 'Cereal'";
elseif(isset($_GET['category']) && $_GET['category'] == 'chips')
    $category="WHERE ProductCategory = 'Chips'";
elseif(isset($_GET['category']) && $_GET['category'] == 'dairy')
    $category="WHERE ProductCategory = 'Dairy'";
else
    $category="";
//replace category string to hold the attribute WHERE search, or blank.


//ALSO will need to add another attribute to where STOREID matches, so that they wont fill a cart
//from multiple stores. That once they pick one item, or maybe select a store, that it will only
//display those items?


	$qry = 'Select * from `PRODUCTS` ' . $category;
	
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