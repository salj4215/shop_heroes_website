<div id="prod_navigation">
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">Products</button>
        <div class="dropdown-content">
            <a href="#">Category 1</a>
            <a href="#">Category 2</a>
            <a href="#">Category 3</a>
            <a href="#">Category 4</a>
        </div>
    </div>
    <button class="chkoutbtn">Check-Out</button>
</div>

<?php
	$qry = 'Select * from `Products`';
	
	$stmt = $pdo -> exec( $qry );
	
	while($row = $stmt->fetch())
	{
		echo $row['ProductID'] . "<br>";
		echo $row['StoreID'] . "<br>";;
		echo $row['ProductName'] . "<br>";;
		echo $row['ProductUPC'] . "<br>";;
		echo $row['UnitPrice'] . "<br>";;
		echo $row['ProductCategory'] . "<br>";;
		echo $row['Description'] . "<br>";;
		echo $row['Quantity'] . "<br>";;		
	}	