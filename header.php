
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Responsive design a little bit between these-->
    <meta name="viewport" content="width=device-width">
    <!-- Responsive design a little bit between these -->
    <meta name="keywords" content="shpo heroes">
    <meta name="description" content="Shop Heroes HomePage">
    <!--Styles including the icons-->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="stylesheet" type="text/css" href="styleSH.css">
    <link rel="icon" size="192x192" href="images/android.png">
    <title>Welcome to Shop Heroes</title>
</head>

<header>
<div id="page">
    <header>
        <p class="head"> Welcome to Shop Heroes! Please sign in to get started.</p>
        <h1><img src="images/location.png" width="25" height="10" alt=""> <a href="location#">  Find a store  </a>  |   <a href="location2#">  Select a Store  </a>
            <p>
                <br>
            </p>
            <div id="search">
                <p>
                </p>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" name="terms" size="25" maxlength="25" placeholder="What are you looking for?">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Search" class="centercell">
                    </td>
                </tr></h1>
        <img src="images/shop.gif" width="220" height="85" alt="Welcome to Shop Heroes"> <!--Should NOT be in a div of its own should be in the search div!!!!! -->
</div>
<!--Navigation Bar-->
<ul>
    <li><a href="index.php?page=home">Home</a></li>
    <li><a href="index.php?page=aboutus">About Us</a></li>
    <li><a href="index.php?page=order">Orders</a></li>
    <li><a href="index.php?page=myaccount">My Account</a></li>
    <li><a href="index.php?page=contact">Contact us</a></li>
    <?php
    if(!(isset($_SESSION["activeUser"])))
        echo '<li><a href="index.php?page=signup">Sign Up</a></li>';
    if(!(isset($_SESSION["activeUser"])))
        print '<li><a href="index.php?page=login">Login</a></li>';
	if(isset($_SESSION["activeUser"]) && $_SESSION["activeUser"] == 0)
        print '<li><a href="index.php?page=logout">Logout</a></li>';
	?>
</ul>
</header>
<?php
print_r($_SESSION);
?>