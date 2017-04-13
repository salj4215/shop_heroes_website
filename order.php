<div id="prod_navigation">
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">Products</button>
        <div class="dropdown-content">
            <a href="#">Produce</a>
            <a href="#">Snacks</a>
            <a href="#">Cereal</a>
            <a href="#">Chips</a>
			<a href="#">Dairy</a>

        </div>
    </div>
    <button class="chkoutbtn">Check-Out</button>
</div>

<?php
	$qry = 'Select * from `PRODUCTS`';
	
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