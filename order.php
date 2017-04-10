<?php
    /**
     * Created by Salleh Jahaf
     * Description: Shop Heroes Order page where customer places items into shopping cart.
     * Date: 4/6/2017
     * Time: 9:46 PM
     */

    require_once ('../connect_db.php'); //waiting for access to file

$charset = 'utf8';
$dsn = "mysql:host=".HOST.";dbname=".DB.";charset=$charset";
$opt =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
$pdo = new PDO($dsn, USER, PASS, $opt); //create pdo object

?>

<div id="prod_navigation">
    <div class="dropdown"> <!-- drop-down button -->
        <button class="productbtn">Products</button>
        <div class="dropdown-content">
            <a href="#">Category 1</a>
            <a href="#">Category 2</a>
            <a href="#">Category 3</a>
            <a href="#">Category 4</a>
        </div>
    </div>
    <button class="chkoutbtn">Check-Out</button>
</div>
