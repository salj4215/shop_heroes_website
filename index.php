<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Sal
 * Date: 4/3/2017
 * Time: 9:11 PM
 */
require_once("connect_db.php");
//check if there are messages if, not declare
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
$charset = 'utf8';
$dsn = "mysql:host=".HOST.";dbname=".DB.";charset=$charset";
$opt =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
$pdo = new PDO($dsn, USER, PASS, $opt); //create pdo object

$errorRepeat = false;
$vaildationError = false;
//SIGN UP NEW USER
if( isset( $_POST['action'] ) && $_POST['action'] == 'signup') {
    $userEmail = $_POST['userEmail'];
    $pass = $_POST['mypassword'];
    $cpass = $_POST['checkpassword'];
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $addr = $_POST['myaddress'];
    $city = $_POST['mycity'];
    $zip = $_POST['myzip'];
    $phone = $_POST['phonenumber'];
    // VALIDATE DATA
    //if password less than 8
    if (strlen($pass) < 8) {
        array_push($_SESSION['messages'], "Password must have at least 8 characters!");
        $vaildationError = true;
    }
    if (strlen($zip) < 5) {
        array_push($_SESSION['messages'], "Please type in your full 5 digit zip code.");
        $vaildationError = true;
    }
    if (strlen($phone) < 9) {
        array_push($_SESSION['messages'], "Please enter a full 9 or 10 digit phone number with no extra characters.");
        $vaildationError = true;
    }
    if (empty($fname) || empty($lname) || empty($pass) || empty($cpass) || empty($userEmail) || empty($zip) || empty($city) || empty($phone)) {
        echo "Please make sure no fields are left blank.";
    }
    //if username is NOT vaild email
    if (!(filter_var($userEmail, FILTER_VALIDATE_EMAIL))) {
        //Email is bad
        array_push($_SESSION['messages'], "Username must be a vaild Email!");
        $vaildationError = true;
    }
//encrpt
    $pass = hash("SHA512", $pass, false);

    $qry = "Select Username from `USERS`";
    $stmt = $pdo->query($qry);
    while ($row = $stmt->fetch()) {   //vaildate if user already has an account
        if ($userEmail == $row['Username']) {
            $errorRepeat = true;
            break;
        }
    }
    if ($errorRepeat == false && $vaildationError == false) {
        $qry = "INSERT INTO `USERS`(`Username`, `Password`)VALUES (:em,:pw)";
        $stmt = $pdo->prepare($qry);
        $params = array
        (
            ':em' => $userEmail, ':pw' => $pass,
        );
        $stmt->execute($params);
        //$uid = $pdo -> lastInsertId(  );
        /////////////////////////////////////////////////////////////////////////
        //echo "CREATED USER... HELLO " . $userEmail ;
        array_push($_SESSION['messages'], "Created new User....", "Hello $userEmail");
        //add session sign in
        $_SESSION["activeUser"] = $userEmail;
        //header('Location: index.php?page=home');
    }
    if ($errorRepeat) {
        //echo "That Username already has an account";
        array_push($_SESSION['messages'], "That Username already has an account");
        $_SESSION['announcements'] = 0;
        header('Location: index.php?page=signup');
    }
    if ($vaildationError) {//there was an input error
        $_SESSION['announcements'] = 0;
        header('Location: index.php?page=signup');
    }
}
//log in
if( isset( $_POST['action'] ) && $_POST['action'] == 'login') {
    $userEmail = $_POST['myusername'];
    $pass = $_POST['mypassword'];
    // VALIDATE DATA

//encrpt
    $pass = hash("SHA512", $pass, false);
    $emailMatch = false;
    $pulledUser = "false99999999999999";    //set default for error of fetching
    $pulledPass = "false99999999999999";
    $qry = "SELECT * FROM USERS WHERE Username='$userEmail'";
    $stmt = $pdo->query($qry);
    while ($row = $stmt->fetch()) {
        $emailMatch = true;
        $pulledUser= $row['Username'];
        $pulledPass= $row['Password'];
    }
    if ($pass == $pulledPass && ($emailMatch)) {
       //correct password in found username
        array_push($_SESSION['messages'], "Welcome $userEmail");
        $_SESSION["activeUser"] = $pulledUser;
    }
    if( $pass != $pulledPass && ($emailMatch))
    {
         //password did not match
        //echo "Incorrect Password Try again";
        array_push($_SESSION['messages'], "Incorrect Password, Try again");
        $_SESSION['announcements'] = 0;
        header('Location: index.php?page=login');
     }
     if(!$emailMatch)
     {
         array_push($_SESSION['messages'], "No such Account Exists");
         $_SESSION['announcements'] = 0;
         header('Location: index.php?page=login');
     }
}
//HEADER OF PAGES
require ('header.phtml');

print "|||||||||||||||";
print_r($_SESSION);
print "|||||||||||||||";
//print_r($_SESSION['messages']);
//var_dump($_SESSION['messages']);
//if statement1 global for all pages and all messages to be printed
if( isset( $_SESSION['messages']) && (count($_SESSION['messages']) > 1 )) {
    foreach ($_SESSION['messages'] as $message) {
        echo $message . "<br>";
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
    header('Location: index.php?page=home');
}   //logout link
else
    require ('HomeSH.html');

require ('footer.html');