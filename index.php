<?php
/**
 * Created by PhpStorm.
 * User: Sal
 * Date: 4/3/2017
 * Time: 9:11 PM
 */
require ('header.html');

if(isset($_GET['page']) && $_GET['page'] == 'contact')
    require ('contact.html'); //contact page
else
    require ('HomeSH.html');

require ('footer.html');