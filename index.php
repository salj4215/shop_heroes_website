<?php
session_start();
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

require ('header.php');


$errorRepeat = false;
//SIGN UP NEW USER
if( isset( $_POST['action'] ) && $_POST['action'] == 'signup')
{
    $user = $_POST['userEmail'];
    $pass = $_POST['mypassword'];
    // VALIDATE DATA

//encrpt
    $pass = hash("SHA512", $pass, false);

    $qry = "Select Username from `USERS`";
    $stmt = $pdo -> query( $qry );
    while($row = $stmt->fetch())
    {   //vaildate if user already has an account
//        echo $row['Username'];
//        echo "<br>";
        if($user == $row['Username'])
        {
            $errorRepeat = true;
            break;
        }
    }
    if($errorRepeat == false) {
        $qry = "INSERT INTO USERS (Username, Password)VALUES('$user','$pass')";
        $stmt = $pdo->prepare($qry);
        echo "CREATED USER... HELLO " . $user ;
        //add session sign in
        $_SESSION["activeUser"]= $user;
        header('Location: https://beta.cis220.hfcc.edu/index.php?page=home');
    }
    if($errorRepeat == true) {
        echo "That Username already has an account";
    }
}
//log in
if( isset( $_POST['action'] ) && $_POST['action'] == 'login')
{
    $user = $_POST['userEmail'];
    $pass = $_POST['mypassword'];
    // VALIDATE DATA

//encrpt
    $pass = hash("SHA512", $pass, false);

    $qry =  "SELECT * FROM USERS WHERE Username='$user'";
    $stmt = $pdo -> query( $qry );
    while($row = $stmt->fetch())
    {
        if($pass == $row['Password'])
        {
            //correct password in found username
            echo "Welcome " . $row['Password'];
            $_SESSION["activeUser"]= $row['Username'];
            header('Location: https://beta.cis220.hfcc.edu/index.php?page=home');
        }
        else
        {
            //password did not match
            echo "Incorrect Password Try again";
            header('Location: https://beta.cis220.hfcc.edu/index.php?page=login');
        }
    }
}

if (isset($_GET['page']) && $_GET['page'] == 'aboutus')
    require ('aboutus.html'); //about us page
else if(isset($_GET['page']) && $_GET['page'] == 'contact')
    require ('contact.html'); //contact page
else if(isset($_GET['page']) && $_GET['page'] == 'myaccount')
    require ('myaccount.html'); //my account page
elseif (isset($_GET['page']) && $_GET['page'] == 'home')
    require ('HomeSH.html'); //home page
elseif (isset($_GET['page']) && $_GET['page'] == 'order')
    require ('order.php'); //order page
elseif (isset($_GET['page']) && $_GET['page'] == 'signup')
    require ('createAccount.html');
elseif (isset($_GET['page']) && $_GET['page'] == 'login')
    require ('main_login.html');
elseif (isset($_GET['page']) && $_GET['page'] == 'logout') {
    session_unset();
    header('Location: https://beta.cis220.hfcc.edu/index.php?page=home');
}
else
    require ('HomeSH.html');

require ('footer.html');