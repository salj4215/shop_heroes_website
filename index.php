<?php
/**
 * Created by PhpStorm.
 * User: Sal
 * Date: 4/3/2017
 * Time: 9:11 PM
 */
require_once("connect_db.php");

$charset = 'utf8';
$dsn = "mysql:host=".HOST.";dbname=".DB.";charset=$charset";
$opt =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
$pdo = new PDO($dsn, USER, PASS, $opt); //create pdo object

require ('header.html');

if(isset($_GET['page']) && $_GET['page'] == 'contact')
    require ('contact.html'); //contact page
elseif (isset($_GET['page']) && $_GET['page'] == 'home')
    require ('HomeSH.html');
elseif (isset($_GET['page']) && $_GET['page'] == 'order')
    require ('order.php');
elseif (isset($_GET['page']) && $_GET['page'] == 'signup')
    require ('createAccount.html');
elseif (isset($_GET['page']) && $_GET['page'] == 'login')
    require ('main_login.html');
else
    require ('HomeSH.html');

require ('footer.html');