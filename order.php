<link rel="stylesheet" type="text/css" href="styleOrder.css">
<div id="shopPage">
<?php
//stopping varrible crashing on start up
if(!isset($searchWord) || (!isset($searchWHERE))){
    $searchWHERE = "";
    $searchWord = "";}
//if cart is not set, then create it.
//if(isset($_COOKIE['cart'])) {setcookie('cart',array('productID' => 'quantity'));}

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

//if GET category or if not set,
if(isset($_POST['search'])) {
    if(!isset($_SESSION['search']))
        $_SESSION['search']= "";
    $_SESSION['search'] = $_POST['search'];
    $_POST['search'] = "";
}

//print "Store: " . var_dump($_SESSION["store"]);
//print "Category: " . var_dump($_SESSION["category"]);
?>
    <!-- Products Navigation bar -->
    <div id="prod_navigation">
		<div class="dropdown">
			<button class="productbtn">Stores &#9660</button>
			<div class="dropdown-content">
                <a href="index.php?page=order&store=AllStores">All Stores</a>
                <a href="index.php?page=order&store=Kroger">Kroger</a>
				<a href="index.php?page=order&store=Meijer">Meijer</a>
			</div>
		</div>
        <div class="dropdown">
			<button class="productbtn">Groceries &#9660</button>
			<div class="dropdown-content">
            <a href="index.php?page=order&category=all">All</a>
            <a href="index.php?page=order&category=produce">Produce</a>
            <a href="index.php?page=order&category=snacks">Snacks</a>
            <a href="index.php?page=order&category=cereal">Cereal</a>
            <a href="index.php?page=order&category=chips">Chips</a>
            <a href="index.php?page=order&category=dairy">Dairy</a>
            </div>
        </div>
        <!--Adding cart preview -->
        <div class="shoppingCart">
            <form action="index.php" method="get">
            <button name="cart" id="cart" type="submit" class="productbtn""><img src="images/shopping-cart-wheel_318-27957.png"style="width:20px;height:20px;"alt="Shopping Cart">   Shopping Cart</button>
                <input type='hidden' name='page' value='shoppingcart'>
            </form>
        </div>
    </div>
    <form name="signUp" action="index.php?page=order"  method="POST">
        <td>
    <table width="50%" border="1" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
        <td colspan="10">Search for an Item</td>
        <td width="250"><input name="search" type="text" id="search" value="<?php if(isset($_SESSION['search'])){echo $_SESSION['search'];}?>"></td>
        <input type="hidden" name="action" value="search">
        <td><input type="submit" name="Submit" value="Search"></td>
    </tr>
    </table>
    </td>
    </form>
<br>
    <?php
    //to select store choice.
    if(isset($_SESSION["store"]) && $_SESSION["store"] == 'AllStores')
        $storeWHERE=" WHERE STORES.StoreID LIKE '%'";
    elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Kroger')
        $storeWHERE=" WHERE STORES.StoreID = '1'";
    elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Meijer')
        $storeWHERE=" WHERE STORES.StoreID = '2'";
    else    //default to all
        $storeWHERE=" WHERE STORES.StoreID LIKE '%'";

    //Show header for types of items shown based on user selection
    echo "<br>";
    echo "<br>";
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'produce' )
        echo "<h1>Produce</h1>";
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'snacks' )
        echo "<h1>Snacks</h1>";
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'cereal' )
        echo "<h1>Cereal</h1>";
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'chips' )
        echo "<h1>Chips</h1>";
    if(isset($_SESSION["category"]) && $_SESSION["category"] == 'dairy' )
        echo "<h1>Dairy</h1>";
    else
        echo "<h1>All Groceries</h1>";

    //to redirect the qry with category selection
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
    ?>
</br></br></br>

        <?php
    //serach bar where
    if(isset($_SESSION['search']))
    {
        $searchWord = $_SESSION['search'];
        $searchWHERE = " AND PRODUCTS.ProductName LIKE '%$searchWord%'";
    }
    else
        $searchWHERE = "";
        //replace category string to hold the attribute WHERE search, or blank.

    $orderBY = "ORDER BY `ProductName`";
	$qry = 'Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID' . $storeWHERE . $categoryWHERE . $searchWHERE . $orderBY;
////call quuery
	$stmt = $pdo -> query( $qry );
	echo "<table class='products'>";
	$numProductDisplayed = 0;
	while($row = $stmt->fetch())
	{   //new table row per product
        $numProductDisplayed++;
//        var_dump($row['ProductID']);
	    echo "<tr style='outline: thin solid'>";
	    $productID = $row['ProductID'];
	    $productName = $row['ProductName'];
	    $productCat = $row['ProductCategory'];
        //product pictures
        echo "<td><img src='images/products/128/_" . $row['ProductUPC'] . ".png' style='width:128px;height:128px;'></td>";
        //product information
		echo "<td>Product: " . $row['ProductName'] . "<br><br>Category: " . $productCat . "</td>";
        echo "<td>Price: $" . $row['UnitPrice'] . "<br><br>Stock: " . $row['Quantity'] . "<br><br>Seller: "; if($row['StoreID'] == 1){echo "Kroger";}; if($row['StoreID'] == 2){echo 'Meijer';}; echo "</td>";
//        echo "<td><a href='index.php?page=order&pid=$productID'>ADD TO CART</a></td></tr>";
        //form for placing ADD TO CART
        echo "<td><form method='post' >";
        echo"    <input type='hidden' name='productName' value='$productName'>";
        echo"    <input type='hidden' name='productCat' value='$productCat'>";
        echo"    <input type='hidden' name='pid' value='$productID'>";
        echo"    <input type='hidden' name='action' value='addcart' >";
        echo"    <button>Add to Cart</button>";
        echo"</form></td></tr>";

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
//If search brings back nothing
	if($numProductDisplayed ==0) {
        echo "<strong>Sorry no products were found matching the description '$searchWord'"; if($categoryWHERE != ""){ print " under " . $_SESSION['category'];} print " at " . $_SESSION['store'] . "</strong>";
    }
    $numProductDisplayed=0;

//?>
</div>
