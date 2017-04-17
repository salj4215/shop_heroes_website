<?php

include ('LoginCreds.php');

$user = $_POST['Username'];
$pass = $_POST['Password'];

$login = new LoginCreds();

$login->setUsername($user);
$login->setPassword($pass);

$loginString = json_encode($login);

$ch = curl_init('https://cislinux.hfcc.edu/~sjahaf/shop_heroes_server/api/1.0/login.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $loginString);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($loginString)));
$result = curl_exec($ch);
var_dump($result);

header('Location: https://cislinux.hfcc.edu/~sjahaf/shop_heroes_server/api/1.0/login.php');