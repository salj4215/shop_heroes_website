<?php

//rebienenstein
//script for the app to access the CCAuthAPI


if(isset($_POST['Amount'])){
    $amount = $_POST['Amount'];
}else{
    exit;
}if(isset($_POST['CustFirst'])){
    $first_name = $_POST['CustFirst'];
}else{
    exit;
}if(isset($_POST['CustLast'])){
    $last_name = $_POST['CustLast'];
}else{
    exit;
}if(isset($_POST['CardNum'])){
    $card_num = $_POST['CardNum'];
}else{
    exit;
}if(isset($_POST['CardExp'])){
    $card_exp = $_POST['CardExp'];
}else{
    exit;
}if(isset($_POST['CVV'])){
    $cvv = $_POST['CVV'];
}else{
    exit;
}

require_once(dirname(__FILE__) . '/../ccauthapi/CCAuthApi.php');


$values = [
    "transaction_type" => "sale",
    "amount" => $amount,
    "cardholder_firstname" => $first_name,
    "cardholder_lastname" => $last_name,
    "card_number" => $card_num,
    "card_expiration" => $card_exp,
    "cvv" => $cvv,
];

//I have to make sure the payment will go through before
//I insert the order, so I just get the auth_code back to insert
//into Payments after the order is created.
$authorization = CCAuthApi::create($values)->response();
if(($authorization->response_code)==1){
   echo $authorization->auth_code;
}else{
    echo "Fail";
}