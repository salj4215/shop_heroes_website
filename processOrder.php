<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Sign up Form">
    <meta name="description" content="Sign up Form">
    <title>ProcessOrder</title>
</head>
<?php
session_start();
if(!(isset($_SESSION['messages']))){
    $_SESSION['messages'] = array('');
    $_SESSION['announcements'] = 0;
}
else if((isset($_SESSION['messages'])) && (count($_SESSION['messages']) > 1 )){
    $_SESSION['announcements'] += 1;
//count amount of times messages were declared
    if ($_SESSION['announcements'] >= 1) {
        $_SESSION['messages'] = array('');
        $_SESSION['announcements'] = 0;
    }
}
//HEADER OF PAGES
require ('header.phtml');

require_once("connect_db.php");
$charset = 'utf8';
$dsn = "mysql:host=".HOST.";dbname=".DB.";charset=$charset";
$opt =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
$pdo = new PDO($dsn, USER, PASS, $opt); //create pdo object
?>

<body>
<!--This is where you proccess the order, take the card information. put it into an array, and then hand it too CCathu-->
<!--That will return an array to be feed back response.-->
<?php
$vaildationError = false;
$user_id = $_SESSION['activeUserID'];

if(isset($_POST['Amount'])){
    $amount = $_POST['Amount'];
}
if(isset($_POST['firstName'])){
    $first_name = $_POST['firstName'];
}
if(isset($_POST['lastName'])){
    $last_name = $_POST['lastName'];
}
if(isset($_POST['CardNum'])){
    $card_num = $_POST['CardNum'];
}
if(isset($_POST['CardExp'])){
    $card_exp = $_POST['CardExp'];
}
if(isset($_POST['CVV'])){
    $cvv = $_POST['CVV'];
}
//what is ship type.... ? 1-hour /2-hour?
if(isset($_POST['shippingTime'])){
    $ship_type=$_POST['shippingTime'];
}
else {
    $ship_type = "1-hour";
}
if(isset($_POST['shipAddress'])) {
    $ship_address = $_POST['shipAddress'];
}
if(isset($_POST['shipCity'])){
    $order_city = $_POST['shipCity'];
}
if(isset($_POST['shipZip'])){
    $order_zip = $_POST['shipZip'];
}

// VALIDATE DATA
//if cvv isnt 3
if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['CardNum']) || empty($_POST['CardExp']) || empty($_POST['CVV']) || empty($_POST['billingAddress']) || empty($_POST['billingCity']) || empty($_POST['billingZip'] ) || !isset($_POST['shippingTime']) ) {
    array_push($_SESSION['messages'], "All form input lines must be filled in.");
    $vaildationError = true;
}   //if all fields are not empty. then vaildate inputs
else {
    if (strlen($order_zip) < 5) {
        array_push($_SESSION['messages'], "Please type in your full 5 digit zip code.");
        $vaildationError = true;
    }
    if (strlen($card_num) > 16 || strlen($card_num) < 13) {
        array_push($_SESSION['messages'], "Credit Card number must be between 13-16 digits.");
        $vaildationError = true;
    }
    if (strlen($card_exp) != 4) {
        array_push($_SESSION['messages'], "Your Credit Card Expiration must be 4 digits.(monthYEAR) EX: '0218'  OR '1119' ");
        $vaildationError = true;
    }
    $yearExp = substr($card_exp, -2);
    $yearExp = (int)$yearExp;
    $monthExp = mb_substr($card_exp, 0, 2);
    $monthExp = (int)$monthExp;
    echo "<br>$monthExp / $yearExp ";
    if (($yearExp < 17) && ($monthExp < 6)) {
        array_push($_SESSION['messages'], "Credit card is expired");
        $vaildationError = true;
    }
    if ($monthExp > 12 || $monthExp < 1) {
        array_push($_SESSION['messages'], "Credit card expiration FIRST TWO didgits must be a vaild month. EX: '1'->'12'");
        $vaildationError = true;
    }
    if (strlen($cvv) != 3) {
        array_push($_SESSION['messages'], "Your CVV number must be 3 digits located on the back of your credit card.");
        $vaildationError = true;
    }
}
//revert to old page if INPUT error.
if ($vaildationError) {//there was an input error
    $_SESSION['announcements'] = -1;
    header('Location: index.php?page=checkout');
}
else {
    echo " values => {" . print_r($amount) . print_r($first_name) . print_r($last_name) . print_r($card_num) . print_r($card_exp) . " }<br>";
    require_once(dirname(__FILE__) . '/ccauthapi/CCAuthApi.php');
    $values = [
        "transaction_type" => "sale",
        "amount" => $amount,
        "cardholder_firstname" => $first_name,
        "cardholder_lastname" => $last_name,
        "card_number" => $card_num,
        "card_expiration" => $card_exp,
        "cvv" => $cvv,
    ];

    $authorization = CCAuthApi::create($values)->response();
    if (($authorization->response_code) == 1) {
        echo $authorization->auth_code;
    } else {
        echo "Fail";
    }
    ?>
    <pre><?php print_r($authorization); ?></pre>


    <?php
    if (!isset($_SESSION['store']))
        $_SESSION['store'] = '';
    if (isset($_GET['store'])) {
        $_SESSION['store'] = $_GET['store'];
    }
//to select store choice.
    if (isset($_SESSION["store"]) && $_SESSION["store"] == 'Membership') {
        $storeWHERE = " WHERE STORES.StoreID LIKE '0'";
        $store_id = 0;
    } elseif (isset($_SESSION["store"]) && $_SESSION["store"] == 'Kroger') {
        $storeWHERE = " WHERE STORES.StoreID = '1'";
        $store_id = 1;
    } elseif (isset($_SESSION["store"]) && $_SESSION["store"] == 'Meijer') {
        $storeWHERE = " WHERE STORES.StoreID = '2'";
        $store_id = 2;
    } else    //default to all
        $storeWHERE = " WHERE STORES.StoreID LIKE '0'";


    echo "<br><br>STARTING  TO RETREIVE USER INFORMATION AND CUSTOMR ID<br><br>";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//if it was approved
    echo $authorization->response_code;
    if (($authorization->response_code) == 1) {
        $sql = "SELECT * FROM CUSTOMERS WHERE UserID = '$user_id'";

        require_once('connect_db.php');
        $con = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect');
        $pay_amt = $amount;
        $pay_confirm_code = $authorization->auth_code;
        $result = mysqli_fetch_array(mysqli_query($con, $sql));
        if (isset($result)) {
            $cust_id = $result['CustID'];
            $pmt_name = $result['CustFirstN'] . ' ' . $result['CustLastN'];
            $pmt_type = "Credit";
            $pmt_address = $result['CustAddress'];
            $pmt_date = date("Y-m-d H:i:s");
            $pmt_zip = $result['CustZip'];
            $pmt_state = "MI";
            $pmt_city = $result['CustCity'];
        }
//TO CREATE ORDER AND GET ORDER NUMBER
        $order_date = date("Y-m-d H:i:s");
//set all of the order status codes to 0
        $order_processed = 0;
        $shopping_started = 0;
        $boxing_items = 0;
        $waiting_delivery = 0;
        $in_route = 0;
        $delivered = 0;
        $signed_for = 0;
        $paid = 0;
        $order_total = $pay_amt;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo "<br><br>FOUND CUST ID, NOW INSERT ORDER THAT HAS BEEN APPROVED TO RECIEVE ORDER ID<br><br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if (isset($cust_id)) {//if we found a CustID from the UserID

            $sql = "INSERT INTO ORDERS(CustID, StoreID, ShipType, ShipAddress, OrderDate, OrderCity, OrderZip,
                                OrderProcessed, ShoppingStarted, BoxingItems, WaitingForDelivery, InRoute, Delivered, SignedFor, Paid,
                                SignedTime, OrderTotal, PaymentConfirmationCode)
                        VALUES('$cust_id', '$store_id', '$ship_type', '$ship_address', '$order_date', '$order_city', '$order_zip',
                                '$order_processed', '$shopping_started', '$boxing_items', '$waiting_delivery', '$in_route', '$delivered', '$signed_for', '$paid',
                                null, '$order_total', null)";

            if (mysqli_query($con, $sql)) {
                $sql = "SELECT OrderID FROM ORDERS WHERE CustID='$cust_id' AND OrderDate='$order_date'";
                $result = mysqli_fetch_array(mysqli_query($con, $sql));
                if (isset($result)) {
                    echo $result[0];
                    $order_id = $result[0];
                } else {
                    echo "Order creation failed";
                }
            } else {
                echo "Order creation failed";
            }
        } else {
            echo "Failed to find customer";
        }
//    mysqli_close($con);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo "<br><br>RETREIVED ODER ID, NOW INSERT INTO PAYMENTS<br><br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $sql = "INSERT INTO PAYMENTS SET CustID='$cust_id', OrderID='$order_id', PmtName='$pmt_name', PmtType='$pmt_type',
    PmtAmount='$pay_amt', PmtAddress='$pmt_address', PmtDate='$pmt_date',
    PmtZip='$pmt_zip', PmtState='$pmt_state', PmtCity='$pmt_city'";
        if (mysqli_query($con, $sql)) {
            $sql = "UPDATE ORDERS SET OrderProcessed=1, Paid=1 ,PaymentConfirmationCode='$pay_confirm_code' WHERE OrderID = '$order_id'";
        }
        if (mysqli_query($con, $sql)) {
            echo "Success";
        } else {
            echo "Fail";
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo "<br><br>NOW PUT ITEMS FROM CART ONTO ORDER LINE <br><br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Then for orderlineItems

        if (isset($_COOKIE['cart'])) {
            $cookieCart = $_COOKIE['cart'];
            $cookieCart = stripslashes($cookieCart);
            $cart = json_decode($cookieCart, true);
        }
        $qry = 'Select * from `PRODUCTS` JOIN `STORES` ON PRODUCTS.StoreID = STORES.StoreID' . $storeWHERE;
////call quuery
        $stmt = $pdo->query($qry);
        while ($row = $stmt->fetch()) {   //if its in the cart
            foreach ($cart as $pid => $qty) {
                if ($row['ProductID'] == $pid) {    //if this is in the cart, and from the correct store.
                    $product_id = $row['ProductID'];
                    $product_price = $row['UnitPrice'];
                    $product_quantity = $qty;
                    $line_price = $product_price * $product_quantity;
                    //Now for inserting that item into the ORDERLINE
                    $sql = "INSERT INTO ORDER_LINE_ITEMS SET OrderID='$order_id', StoreID='$store_id', ProductID='$product_id',ProductPrice = '$product_price', ProductQuantity='$product_quantity', LinePrice='$line_price'";
                    if (mysqli_query($con, $sql)) {
                        echo "Success";
                        //then remove items from cart
                        $cart[$product_id] = 0;
                    } else {
                        echo "Fail<br>";
                        echo $sql;
                        echo "<br>";
                    }
//                mysqli_close($con);

                }   //displaying membership, dispite whats in cart
            }
        }
        mysqli_close($con);
        //now clean out products that have zero qty
        foreach ($cart as $pid => $qty) {
            if ($qty == 0)
                unset($cart[$pid]);
        }
//now renew data in cookie cart.
        $json = json_encode($cart);
        setcookie('cart', $json);
    }
//if not approved, send back with messeage
    if (($authorization->response_code) != 1) {
        array_push($_SESSION['messages'], "Credit Card was declined, please try again. ERROR: #" . ($authorization->response_code));
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=checkout');
    }
//if Approved then contiune to display results.
    if (($authorization->response_code) == 1) {
        array_push($_SESSION['messages'], "Order Completed");
        $_SESSION['announcements'] = -1;
        $_SESSION['orderNum'] = $order_id;
        $_SESSION['orderStore'] = $store_id;
        $_SESSION['shippingType'] = $ship_type;
        header('Location: index.php?page=orderComplete');
    }
}
?>

</body>
<?php
require ('footer.html');
?>