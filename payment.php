<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Sign up Form">
    <meta name="description" content="Sign up Form">
    <title>Payment</title>
</head>
<body>
<style media="screen" type="text/css">
    fieldset {

        margin-left: 0px;
        margin-right: 0px;
        padding-top: 5px;
        padding-bottom: 120px;
        padding-left: 0.75em;
        padding-right: 0.75em;
        border: 2px groove;
        height: 325px;
        width: 350px;
        display: table-cell;
        white-space:nowrap;

    }
</style>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_SESSION["store"]) && $_SESSION["store"] == 'Membership')
    $storeWHERE=" WHERE STORES.StoreID LIKE '0'";
elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Kroger')
    $storeWHERE=" WHERE STORES.StoreID = '1'";
elseif(isset($_SESSION["store"]) && $_SESSION["store"] == 'Meijer')
    $storeWHERE=" WHERE STORES.StoreID = '2'";
else    //default to all
    $storeWHERE=" WHERE STORES.StoreID LIKE '0'";

if(isset($_COOKIE['cart'])) {
    $cookieCart = $_COOKIE['cart'];
    $cookieCart = stripslashes($cookieCart);
    $cart = json_decode($cookieCart, true);
}
//this recalculates the subtotal again, incase of cookies changing. as well as remembering session store
$subTotal=0.0;
$UnitsPrice=0.0;
$shipping =0.0;
$grandTotal=0.0;
$qry = "Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID" . $storeWHERE;
////call quuery
$stmt = $pdo -> query( $qry );
$numProductDisplayed = 0;
while($row = $stmt->fetch()) {   //if its in the cart
    foreach ($cart as $pid => $qty) {
        if ($row['ProductID'] == $pid) {
            $UnitsPrice=0.0;
            $productID = $row['ProductID'];
            $price = $row['UnitPrice'];
            $UnitsPrice = ($qty * $price);
            $subTotal = $subTotal + $UnitsPrice;
            }
        }
    }
$shipping = 5.99;
$grandTotal = $subTotal + $shipping;
?>
<div id="payment">

    <table class="no-spacing" cellspacing="0" align="center">
        <form name="confirmCheckout" action="processOrder.php"  method="POST">
            <td>
                <fieldset><legend align="center"> Shipping Address :</legend>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                        <tr>
                            <td width="78">First Name</td>
                            <td width="294"><input name="firstName" type="text" id="firstName" value="<?php echo $_SESSION['activeCustFirstN']; ?>"></td>
                        </tr>
                        <tr>
                            <td>Last Name</td><td><input name="lastName" type="text" id="lastName" value="<?php echo $_SESSION['activeCustLastN']; ?>"></td>
                        </tr>
                        <tr>
                            <td>Shipping Address</td><td><input name="shipAddress" type="text" id="shipAddress" value="<?php echo $_SESSION['activeCustAdd']; ?>"></td>
                        </tr>
                        <tr>
                            <td>Shipping City</td><td><input name="shipCity" type="text" id="shipCity" value="<?php echo $_SESSION['activeCustCity'] ?>"></td>
                        </tr>
                        <tr>
                            <td>Shipping Zipcode</td><td><input name="shipZip" type="text" id="shipZip" value="<?php echo $_SESSION['activeCustZip']; ?>"></td>
                        </tr>
                        <tr>
                            <td>Phone Number</td><td><input name="phonenumber" maxlength="10" type="text" id="phonenumber" value="<?php echo $_SESSION['activeCustPhone']; ?>"></td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td>
                <fieldset><legend align="center"> Payment Information</legend>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
                        <tr>
                            <td width="78">Billing Name</td>
                            <td width="294"><input name="billingName" type="text" id="billingName" value=""></td>
                        </tr>
                        <tr>
                            <td>Billing Address</td><td><input name="billingAddress" type="text" id="billingAddress" value=""></td>
                        </tr>
                        <tr>
                            <td>Billing City</td><td><input name="billingCity" type="text" id="billingCity" value=""></td>
                        </tr>
                        <tr>
                            <td>Billing Zipcode</td><td><input name="billingZip" type="text" id="billingZip" value=""></td>
                        </tr>
                        <tr><br><br></tr>

                        </tr>
                    </table>
                </fieldset>
            </td>
            <td>
                <fieldset><legend align="center"> Credit Card</legend>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
                        <tr>
                            <td width="78">Credit Card Number</td>
                            <td width="294"><input name="CardNum" type="text" id="CardNum" value=""></td>
                        </tr>
                        <tr>
                            <td>Expiration</td><td><input name="CardExp" type="text" id="CardExp" value=""></td>
                        </tr>
                        <tr>
                            <td>CCV code:</td><td><input name="CVV" type="text" id="CVV"></td>
                        </tr>
						<tr>
						<td width="100"><input type="radio" name="shippingTime" value="1-hour">1 Hour ($7.99)</td>
						<td><input type="radio" name="shippingTime" value="2-hour" checked="checked">2 Hour ($5.99)<td>
						</tr>
                        <tr>
                        <td>
                            <input type="hidden" name="action" value="confirmCheckout">
                            <input type="hidden" name="Amount" value="<?php echo $subTotal?>">
                        <td><input type="submit" name="Submit" value="Confirm Payment"></td>
                        </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
    </table>
</div>
<?php
$shipping = 5.99;
$grandTotal = $subTotal + $shipping;
echo '<br>Order Subtotal: $' . number_format($subTotal, 2, '.', ',');
echo '<br>Order Shipping: $' . number_format($shipping, 2, '.', ',');
echo "<br>==================================";
echo '<br>Order Total: $' . number_format($grandTotal, 2, '.', ',');
?>
</body>
</html>

</form>