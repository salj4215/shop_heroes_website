<link rel="stylesheet" type="text/css" href="styleOrder.css">
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

//add to cart           as string
if( isset( $_POST['action'] ) && $_POST['action'] == 'addToCart') {
//if(isset($_POST['addToCart']) && $_POST['addToCart'] != "0") {
    $productIDtoBEaddedTOcart = $_POST['cart'];
    echo "<br> . $productIDtoBEaddedTOcart" . "*************************************";
}
function AddToCart($pid)
{    // add new item on array
    if(!isset($cart)){ $cart = array('productID' => 'quantity');}
    //Search cart array for product id, if not there, than add new product, and quantiy
    foreach ( $cart as $id => $quantity) {
        if ($pid == $id) {
            ($quantity + 1);
        } else {
            array_push($cart,$id,1);
        }
}
    $json = json_encode($cart);
    setcookie('cart',$json);
    echo "<br>Added $pid to cart" . " and the cookie is= " . var_dump($cart) . "<br>";
}


//print "Store: " . var_dump($_SESSION["store"]);
//print "Category: " . var_dump($_SESSION["category"]);
?>
    <!-- Products Navigation bar -->
    <div id="prod_navigation">
		<div class="dropdown">
			<button class="productbtn">Stores &#9660</button>
			<div class="dropdown-content">
                <a href="index.php?page=order&store=All">All Stores</a>
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
=======================================================================================================
<br>
    <?php
    //to select store choice.
    if(isset($_SESSION["store"]) && $_SESSION["store"] == 'All')
        $storeWHERE=" WHERE STORES.StoreID LIKE '%'";
    elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Kroger')
        $storeWHERE=" WHERE STORES.StoreID = '1'";
    elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Meijer')
        $storeWHERE=" WHERE STORES.StoreID = '2'";
    else    //default to all
        $storeWHERE=" WHERE STORES.StoreID LIKE '%'";

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

    //serach bar where
    if(isset($_SESSION['search']))
    {
        $searchWord = $_SESSION['search'];
        $searchWHERE = " AND PRODUCTS.ProductName LIKE '%$searchWord%'";
    }
    else
        $serachWHERE = "";
        //replace category string to hold the attribute WHERE search, or blank.

	$qry = 'Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID' . $storeWHERE . $categoryWHERE . $searchWHERE;

//	//display variable for output information
//	echo "==============================================================================================";
//    echo "</br>storeWHERE: == '" . $storeWHERE . "'";
//	echo "</br>categoryWHERE: == '" . $categoryWHERE . "'";
//    echo "</br>searchWHERE: == '" . $searchWHERE . "'";
//    echo "</br>qry: == '" . $qry . "'";
//    echo "</br>store: == '" . $_SESSION['store'] . "'";
//    print "</br>category: == '" . $_SESSION['category'] . "'";
//    print "</br>searchWord = '" . $searchWord . "'    and searchWHERE == ". "'" . $searchWHERE . "'<br>";
//    print "</br></br>============================================================================================<br>";
////call quuery
	echo "<br />";
	echo "<br />";
	echo "<br />";
	$stmt = $pdo -> query( $qry );
	echo "<table>";
	$numProductDisplayed = 0;
	while($row = $stmt->fetch())
	{   //new table row per product
        $numProductDisplayed++;
//        var_dump($row['ProductID']);
	    echo "<tr>";
	    $productID = $row['ProductID'];
        //product pictures
        echo "<td><img src='images/products/128/_" . $row['ProductUPC'] . ".png' style='width:128px;height:128px;'></td>";
        //product informations
		echo "<td>ProductName: " . $row['ProductName'] . "<br><br>ProductCategory: " . $row['ProductCategory'] . "<br><br>ProductUPC: " . $row['ProductUPC'] . "</td>";
        echo "<td>UnitPrice: $" . $row['UnitPrice'] . "<br><br>Quantity: " . $row['Quantity'] . "<br><br>Store= "; if($row['StoreID'] == 1){echo "Kroger";}; if($row['StoreID'] == 2){echo 'Meijer';}; echo "</td>";
//        echo "<td><a href='index.php?page=order&pid=$productID'>ADD TO CART</a></td></tr>";
        echo "<td><form name='addToCart' action='index.php?page=order' method='POST'><input type='hidden' name='productID' value='$productID'>";
        echo "<input type='submit' name='cartBtn' value='ADD TO CART'></td></tr>";
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
//clear search
//if(isset($_SESSION['search'])){
//    $_SESSION['search'] = "";
//    $searchWHERE = "";
//    $searchWord = "";}

echo "<br><br><br> this is the data of orderlineitems.";

$qry ="Select * from `OrderLineItems`";

$stmt = $pdo -> query( $qry );
while($row = $stmt->fetch())
{   //new table row per product
    var_dump($row);
    echo "<br>";
}
?>

