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


//SIGN UP NEW USER
if( isset( $_POST['action'] ) && $_POST['action'] == 'signup') {
    $errorRepeat = false;
    $vaildationError = false;
    $_SESSION['lastPageData'] = $_POST;
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
    if($cpass != $pass)
    {
        array_push($_SESSION['messages'], "Confirm Password does not match password");
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
//query to pull all usernames to check
    $qry = "Select Username FROM `USERS`";
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
            ':em' => $userEmail,':pw' => $pass
        );
        $stmt->execute($params);
        //check new assinged userID
        $qry = "Select * FROM `USERS` WHERE `Username` = '$userEmail'";
        $stmt = $pdo->query($qry);
        while ($row = $stmt->fetch()) {   //vaildate if user already has an account
            $pulledUserID= $row['UserID'];
        }
        ///////////////////////now use userID to link to customerID
        $qry = "INSERT INTO `CUSTOMERS`(`UserId`, `CustFirstN`, `CustLastN`, `CustAddress`, `CustCity`, `CustZip`, `CustPhone`)
              VALUES (:ui,:cfn,:cln,:ca,:cc,:cz,:cp)";
        $stmt = $pdo->prepare($qry);
        $params = array
        (
            ':ui' => $pulledUserID,':cfn' => $fname,':cln' =>$lname,':ca' =>$addr,':cc' =>$city,':cz' => $zip,':cp' => $phone
        );
        $stmt->execute($params);
        ///////////////now linked customer to user, through UserID
        //add session sign in
        $_SESSION["activeUser"] = $userEmail;
///// pull all new data for session.
        $qry = "SELECT * FROM USERS JOIN CUSTOMERS ON USERS.UserID = CUSTOMERS.UserID WHERE Username='$userEmail'";
        $stmt = $pdo->query($qry);
        while ($row = $stmt->fetch()) {
            $emailMatch = true;
            $pulledUser= $row['Username'];
            $pulledPass= $row['Password'];
            $pulledUserID = $row['UserID'];
            $pulledCustID = $row['CustID'];
            $pulledCustFirstN = $row['CustFirstN'];
            $pulledCustLastN = $row['CustLastN'];
        }
            //correct password in found username
            $_SESSION['activeUser'] = $pulledUser;
            $_SESSION['activeUserID'] = $pulledUserID;
            $_SESSION['activeCustID'] = $pulledCustID;
            $_SESSION['activeCustFirstN'] = $pulledCustFirstN;
            $_SESSION['activeCustLastN'] = $pulledCustLastN;
            $CapsName = strtoupper($pulledCustFirstN);
            array_push($_SESSION['messages'], "Created new User.... $userEmail", "HELLO  $CapsName");
            //header('Location: index.php?page=home');
            unset($_SESSION['lastPageData']);
    }
    if ($errorRepeat) {
        //echo "That Username already has an account";
        array_push($_SESSION['messages'], "That Username already has an account");
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=signup');
    }
    if ($vaildationError) {//there was an input error
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=signup');
    }
}
//log in
if( isset( $_POST['action'] ) && $_POST['action'] == 'login') {
    $userEmail = $_POST['myusername'];
    $pass = $_POST['mypassword'];

//encrpt
    $pass = hash("SHA512", $pass, false);

    $emailMatch = false;
    $pulledUser = "false99999999999999";    //set default for error of fetching
    $pulledPass = "false99999999999999";
    $pulledUserID = "false9999999999999";
//    $qry = "SELECT * FROM USERS WHERE Username='$userEmail'";
    $qry = "SELECT * FROM USERS JOIN CUSTOMERS ON USERS.UserID = CUSTOMERS.UserID WHERE Username='$userEmail'";
    $stmt = $pdo->query($qry);
    while ($row = $stmt->fetch()) {
        $emailMatch = true;
        $pulledUser= $row['Username'];
        $pulledPass= $row['Password'];
        $pulledUserID = $row['UserID'];
        $pulledCustID = $row['CustID'];
        $pulledCustFirstN = $row['CustFirstN'];
        $pulledCustLastN = $row['CustLastN'];
    }
    if ($pass == $pulledPass && ($emailMatch)) {
       //correct password in found username
        $_SESSION['activeUser'] = $pulledUser;
        $_SESSION['activeUserID'] = $pulledUserID;
        $_SESSION['activeCustID'] = $pulledCustID;
        $_SESSION['activeCustFirstN'] = $pulledCustFirstN;
        $_SESSION['activeCustLastN'] = $pulledCustLastN;
        $CapsName = strtoupper($pulledCustFirstN);
        array_push($_SESSION['messages'], "<strong>Welcome  $CapsName!</strong>");
    }
    if( $pass != $pulledPass && ($emailMatch))
    {
         //password did not match
        //echo "Incorrect Password Try again";
        array_push($_SESSION['messages'], "Incorrect Password, Try again");
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=login');
     }
     if(!$emailMatch)
     {
         array_push($_SESSION['messages'], "No such Account Exists");
         $_SESSION['announcements'] = -1;
         header('Location: index.php?page=login');
     }
}

//UPDATE USER
if( isset( $_POST['action'] ) && $_POST['action'] == 'updateUser') {
    $errorRepeat = false;
    $vaildationError = false;
    $activeUserID = $_SESSION['activeUserID'];
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
    if($cpass != $pass)
    {
        array_push($_SESSION['messages'], "Confirm Password does not match password");
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
//query to pull all usernames to check
    $qry = "Select Username FROM `USERS` WHERE UserID NOT LIKE '$activeUserID'";
    $stmt = $pdo->query($qry);
    while ($row = $stmt->fetch()) {   //vaildate if user already has an account
        if ($userEmail == $row['Username']) {
            $errorRepeat = true;
            break;
        }
    }
    if ($errorRepeat == false && $vaildationError == false) {
        //////////////////now update customer portion
        $qry = "UPDATE CUSTOMERS SET `CustFirstN`= :cfn, `CustLastN`=:cln, `CustAddress`=:ca, `CustCity`=:cc, `CustZip`=:cz, `CustPhone`=:cp WHERE UserID='$activeUserID' ";
        $stmt = $pdo->prepare($qry);
        $params = array
        (
            ':cfn' => $fname,':cln' =>$lname,':ca' =>$addr,':cc' =>$city,':cz' => $zip,':cp' => $phone
        );
        $stmt->execute($params);
        //////now update the User portion
        $qry = "UPDATE USERS SET `Username`= :us, `Password`=:pss WHERE UserID='$activeUserID' ";
        $stmt = $pdo->prepare($qry);
        $params = array
        (
            ':us' => $userEmail, ':pss' => $pass
        );
        $stmt->execute($params);
        ///////////////now linked customer to user, through UserID
        //add session sign in
        $_SESSION["activeUser"] = $userEmail;
///// pull all new data for session.
        $qry = "SELECT * FROM USERS JOIN CUSTOMERS ON USERS.UserID = CUSTOMERS.UserID WHERE Username='$userEmail'";
        $stmt = $pdo->query($qry);
        while ($row = $stmt->fetch()) {
            $emailMatch = true;
            $pulledUser= $row['Username'];
            $pulledPass= $row['Password'];
            $pulledUserID = $row['UserID'];
            $pulledCustID = $row['CustID'];
            $pulledCustFirstN = $row['CustFirstN'];
            $pulledCustLastN = $row['CustLastN'];
        }
        //correct password in found username
        $_SESSION['activeUser'] = $pulledUser;
        $_SESSION['activeUserID'] = $pulledUserID;
        $_SESSION['activeCustID'] = $pulledCustID;
        $_SESSION['activeCustFirstN'] = $pulledCustFirstN;
        $_SESSION['activeCustLastN'] = $pulledCustLastN;
        $CapsName = strtoupper($pulledCustFirstN);
        array_push($_SESSION['messages'], "Updated User.... $pulledUserID", "<strong>Hello  $CapsName!</strong>");
        //header('Location: index.php?page=home');
    }
    if ($errorRepeat) {
        //echo "That Username already has an account";
        array_push($_SESSION['messages'], "That UserEmail is already in use.");
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=updateUserInfo');
    }
    if ($vaildationError) {//there was an input error
        $_SESSION['announcements'] = -1;
        header('Location: index.php?page=updateUserInfo');
    }
}

//HEADER OF PAGES
require ('header.phtml');

//print "|||||||||||||||";
//print_r($_SESSION);
//print "|||||||||||||||";

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
    require ('myaccount.phtml'); //my account page
elseif (isset($_GET['page']) && $_GET['page'] == 'home')
    require ('HomeSH.html'); //home page
elseif (isset($_GET['page']) && $_GET['page'] == 'order')
    require ('order.php'); //order page
elseif (isset($_GET['page']) && $_GET['page'] == 'signup')
    require ('createAccount.phtml');
elseif (isset($_GET['page']) && $_GET['page'] == 'login')
    require ('main_login.html');
elseif (isset($_GET['page']) && $_GET['page'] == 'updateUserInfo')
    require ('updateUserInfo.phtml');
elseif (isset($_GET['page']) && $_GET['page'] == 'logout') {
    session_unset();
    header('Location: index.php?page=home');
}   //logout link
else
    require ('HomeSH.html');

require ('footer.html');