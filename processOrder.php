<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Sign up Form">
    <meta name="description" content="Sign up Form">
    <title>ProcessOrder</title>
</head>
<?php
//HEADER OF PAGES
require ('header.phtml');
//I have to make sure the payment will go through before
//I insert the order, so I just get the auth_code back to insert
//into Payments after the order is created.
?>

<body>
<!--This is where you proccess the order, take the card information. put it into an array, and then hand it too CCathu-->
<!--That will return an array to be feed back response.-->
<?php
if(isset($_POST['Amount'])){
    $amount = $_POST['Amount'];
}
else{
//    exit;
}
if(isset($_POST['firstName'])){
    $first_name = $_POST['firstName'];
}
else{
//    exit;
}
if(isset($_POST['lastName'])){
    $last_name = $_POST['lastName'];
}
else{
//    exit;
}
if(isset($_POST['CardNum'])){
    $card_num = $_POST['CardNum'];
}
else{
//    exit;
}
if(isset($_POST['CardExp'])){
    $card_exp = $_POST['CardExp'];
}
else{
//    exit;
}
if(isset($_POST['CVV'])){
    $cvv = $_POST['CVV'];
}
else{
//    exit;
}
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
if(($authorization->response_code)==1){
    echo $authorization->auth_code;
}
else{
    echo "Fail";
}
?>

</body>
<?php
require ('footer.html');
?>