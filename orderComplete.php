
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Sign up Form">
    <meta name="description" content="Sign up Form">
    <title>Payment</title>
</head>
<body>
    <?php

    $order_id = $_SESSION['orderNum'];
    $store_id = $_SESSION['orderStore'];
    $shipping = $_SESSION['shippingType'];
    if($store_id == 0) {
        $store = "Shop Heroes";
    }
    if($store_id == 1) {
        $store = "Kroger";
    }
    if($store_id == 2) {
        $store = "Meijer";
    }
    echo "<p> Your Order has been completed";
    echo "<br><br>Order Number: $order_id";
    echo "<br>Store: $store";
    echo "<br>Shipment time: $shipping";
    echo "<br><br>Your order will now start to be put together and shipped!</p>";

    ?>
</body>
</html>
