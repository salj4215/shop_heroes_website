<?php
// Enable debugging.
ini_set('display_errors', 'on');
error_reporting(E_ALL);

// Note: This assumes everything is set correctly
//       in ccauthapi/config.php file.
require_once(dirname(__FILE__) . '/../ccauthapi/CCAuthApi.php');

// Dummy values for sample transaction.
$values = [
    "transaction_type" => "sale",
    "amount" => "54.97",
    "cardholder_firstname" => "John",
    "cardholder_lastname" => "Smith",
    "card_number" => "4111222233334444",
    "card_expiration" => "1225",
    "cvv" => "0369",
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CCAuthApi test</title>
</head>
<body>
<h1>CCAuthApi test</h1>

<h2>Request data</h2>
<pre><?php print_r($values); ?></pre>

<h2>Response data</h2>
<?php $authorization = CCAuthApi::create($values)->response(); ?>
<pre><?php print_r($authorization); ?></pre>
</body>
</html>