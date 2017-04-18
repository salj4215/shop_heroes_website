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

session_status();
$error = false;
//SIGN UP NEW USER
if( isset( $_POST['action'] ) && $_POST['action'] == 'signup')
{
    $user = $_POST['myusername'];
    $pass = $_POST['mypassword'];
    // VALIDATE DATA

//encrpt
    $pass = hash("SHA512", $pass, false);

    $qry = "Select Username from `USERS`";
    $stmt = $pdo -> query( $qry );
    while($row = $stmt->fetch())
    {   //vaildate if user already has an account
        if($user == $row['Username']) {
            $error = true;
            break;
        }
    }
    if($error == false) {
        $qry = "INSERT INTO USERS (Username, Password)VALUES('$user','$pass')";
        $stmt = $pdo->prepare($qry);
        echo "CREATED USER: " . $user ;
    }
    if($error == true) {
        echo "That username already has an account";
    }
}
    
if (isset($_GET['page']) && $_GET['page'] == 'aboutus')
    require ('aboutus.html'); //about us page
else if(isset($_GET['page']) && $_GET['page'] == 'contact')
    require ('contact.html'); //contact page
else if(isset($_GET['page']) && $_GET['page'] == 'myaccount')
    require ('myaccount.html');
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