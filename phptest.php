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
		<title>Shop Heroes Reports</title>
	</head>
	<body>
		<header>
			<head>
				<style type="text/css">
					p.logo
					{
						margin-left: 1%;
						margin-right: 1%;
						height:85px;
						background: linear-gradient(rgb(42,157,142), rgb(38,70,83));
					}
					
					tr * {
						vertical-align: top;
						height: 40px;
					}
					
					table.reporttable{
						text-align: left;
						margin-left: 1%;
						margin-right: 1%;
						margin-bottom: 1%;
						font-size:1em;
						height:400px;
						width:98%;
						border:1px solid black;
					}
					
					select.reportchoice{
						font-size:1em;
						margin-left: 1%;
						margin-right: 1%;
						margin-bottom: 1%;
						height:40px;"
					}
					
				</style>
				<p class="head">
					Welcome to Shop Heroes Reports!
				</p>
				<p class="logo">
					<img src="images/SHIconWithTransparacy.png" width="220" height="85" alt="Welcome to Shop Heroes">
				</p>
				
				<nav class="sitenavigation">
					<!--<p><a href="index.html">Home</a></p>
					<p><a href="aboutus.html">About Us</a></p>
					<p><a href="orders.html">Orders</a></p>
					<p><a href="giftCard.html">Gift Cards & more</a></p>
					<p><a href="contact.html">Contact us</a></p>-->
					<p>Home</p>
					<p>Reports</p>
					<p>Drivers</p>
					<p>Shoppers</p>
				</nav>
			</head>
		</header>
		<select class="reportchoice" id="reporttype" onchange="changereporttype(this);">
			
			<option>Shopper</option>
			<option>Driver</option>
			<option>Accounts Payable to Store</option>
			<option>Shopping List in Accounts Payable</option>
			<option>Ticket</option>
		</select>
		<table class="reporttable" id="report">
				<tr>
					<td colspan="2" width="20%">
						Your looking at the <span id="output">Driver</span>'s table
					</td>
				</tr>
				<tr height="90%">
					<td width="20%">
					</td>
					<td width="30%">
					</td>
					<td width="90%">
					</td>
				</tr>
		</table>
		<span id="output"></span>
		<span id="output"></span>
		
		<script type="text/javascript">
			function changereporttype(selectObj) {
				//how to insert rows
				//https://www.w3schools.com/jsref/met_table_insertrow.asp
				//how to get the row count
				//https://www.aspsnippets.com/Articles/Get-Count-Number-of-Rows-in-HTML-Table-using-JavaScript-and-jQuery.aspx
				var selectIndex=selectObj.selectedIndex;
				var selectValue=selectObj.options[selectIndex].text;
				var output=document.getElementById("output");
				var totalRowCount = 0;
				var rowCount = 0;
				var table = document.getElementById("report");
				var rows = table.getElementsByTagName("tr")
				for (var i = 0; i < rows.length; i++) {
					totalRowCount++;
					if (rows[i].getElementsByTagName("td").length > 0) {
						rowCount++;
					}
				}
				var message = totalRowCount;
				
				
				
				var table = document.getElementById("report");
				var row = table.insertRow(message-1);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				cell1.innerHTML = message;
				cell2.innerHTML = "th Row";
				
				
				
				
				
				output.innerHTML=selectValue;
			}
		</script>

		<?php
			$host="cis220.hfcc.edu";
			$username="desktopbeta";
			$password="atkj1fZS7lRZC9DP";
			$db_name="shop_heroes_db_beta";
			$tbl_name="PRODUCTS"
			mysql_connect("$host", "$username", "$password")or die("cannot connect");
			mysql_select_db("$db_name")or die("cannot select DB");
			/*$sql="INSERT INTO PRODUCTS (StoreID, ProductName, ProductUPC, UnitPrice, ProductCategory, Description, Quantity)
			VALUES (0, One Hour Delivery, 9999910001, 7.99, Shipping, DEFAULT, DEFAULT)";
			
			try{
			$conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$conn->exec($sql);
			echo "New record created successfully";
			}
			catch()
			{
				echo $sql . "<br>" . $e->getMessage();
			}
			$conn = null;*/
			$sql="SELECT * FROM $tbl_name";
			$result = mysql_query($sql);
			if (!$result) {
				die('Invalid query: ' . mysql_error());
			}
		?>
		
		<?php
			while($rows=mysql_fetch_array($result)){
		?>
		<tr>
		<td width="10%"><? echo $rows['ProductName']; ?></td>
		</tr>
		<?php
		}
		?>
		<?php
			mysql_close();
		?>
		
		
	</body>
	<footer>
			<a href="index.html">Home</a>
			|
			<a href="index2.html">New Products</a>
			|
			<a href="index2.html">Special Offers</a>
			|
			<a href="index2.html">My Account</a>
			|
			<a href="index2.html">Shopping Cart</a>
			|
			<a href="index2.html">Locations</a>
			|
			<a href="index2.html">FAQ</a>
			|
			<a href="contact.html">Contact Us</a>
			|
			<a href="index2.html" class="terms">Privacy Policy</a>
			|
			<a href="index2.html" class="terms">Terms of Use</a>
			<p>
				Copyright &copy; ShopHeroes 2017. All rights reserved.
			</p>
	</footer>
</html>