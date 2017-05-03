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
		<?php
			#This include file sets HOST, USER, PASS, and DB, which will connect you to the database:
			#calls the hidden connect_db file.php that Defines HOST USER PASS DB they are basically variables
			require_once('connect_db.php');

			#makes a string $dsn and inserts the value of HOST for the first %s, and DB for the second %s.
			#So, for example "mysql:host=localhost;dbname=shop_heroes_db_beta" or something like that.
			$dsn = sprintf("mysql:host=%s;dbname=%s;charset=UTF8;", HOST, DB);
			#This just sets up some nice driver options for PDO error reporting:
			$driver_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			#defines a $pdo database object with $dsn (assembled above), USER, and PASS stuffed in as parameters:
			$pdo = new PDO($dsn, USER, PASS, $driver_options);

			#define the sql quary you want to run
			$query = "SELECT * FROM ORDERS";
			#runs the quary
			$orders = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
			#this should pull all the current data from the products table
			#all the data will show up at the top of the site on top of the header below.
			#this can be themed look better


			#Created with the help of Micah W
		?>
	</head>
	<body>
		<header>
			<head>
				<style type="text/css">
					p.logo
					{
						height:85px;
						background: linear-gradient(rgb(42,157,142), rgb(38,70,83));
					}
					
					tr * {
						vertical-align: top;
						height: 40px;
					}
					
					table.reporttable{
						background-color: white;
						text-align: left;
						margin-left: 2%;
						margin-right: 2%;
						font-size:1em;
						height:500px;
						width:96%;
						border:1px solid black;
					}
					
					p.reportdate{
						margin-left: 2%;
						margin-right: 2%;
						font-size: 1em;
						text-align: center;
					}
					
					select.reportchoice{
						font-size:1em;
						margin-left: 2%;
						margin-right: 2%;
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
					<p>
					|&emsp;&emsp;
					<a href="EmpHomeSH.html">Home</a>
					&emsp;&emsp;|&emsp;&emsp;
					<a href="EmpReportsSH.html">Reports</a>
					&emsp;&emsp;|&emsp;&emsp;
					<a href="EmpDriversSH.html">Drivers</a>
					&emsp;&emsp;|&emsp;&emsp;
					<a href="EmpShoppersSH.html">Shoppers</a>
					&emsp;&emsp;|
					</p>
				</nav>
			</head>
		</header>
		<br>
		<select class="reportchoice" id="reporttype" onchange="changereporttype(this);">
			<option>Driver</option>
			<option>Shopper</option>
			<option>Accounts Payable to Store</option>
			<option>Shopping List in Accounts Payable</option>
			<option>Ticket</option>
		</select>
		<p class="reportdate">
			Enter a Start Date
			<input type="date" name="startdate">
			&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
			Enter an End Date
			<input type="date" name="enddate">
		<p>
		<br>
		<!--<table class="reporttable" id="report">
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
		</table>-->
		<table class="reporttable" id="report">
            <?php foreach ($orders as $order) { ?>
                <tr>
					<td>
						<input class="orders" id="orderid" type="submit" onclick="SelectOrderID(this)" value="<?php echo $order['OrderID']; ?>"/>
					</td> 
					<td>
					</td>
					<td>
					</td>
				</tr>
            <?php } ?>
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
				var i = 0;
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
				
				if(selectValue=="Driver")
				{
					for (i=message-2; i>=1; i--)
					{
						table.deleteRow(i);
					}
					output.innerHTML=selectValue
					for (i=1; i <= 5; i++)
					{
						//var table = document.getElementById("report");
						var row = table.insertRow(i);
						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						var cell3 = row.insertCell(2);
						cell1.innerHTML = "test1";
						cell2.innerHTML = "test2";
						cell3.innerHTML = "tester";
					}
					
						
					
				}
				else if (selectValue=="Shopper")
				{
					for (i=message-2; i>=1; i--)
					{
						table.deleteRow(i);
					}
					output.innerHTML=selectValue
					for (i=1; i <= 5; i++)
					{
						//var table = document.getElementById("report");
						var row = table.insertRow(i);
						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						cell1.innerHTML = i;
						cell2.innerHTML = "th Row in Shopper";
					}
				}
				else
				{
					output.innerHTML="";
				}
				
				
				
				
				//output.innerHTML=selectValue;
			}
		</script>
		
	</body>
	
	<footer>
			<br>
			<nav class="sitenavigation">
			|&emsp;
			<a href="index.html">Home</a>
			&emsp;|&emsp;
			<a href="index2.html">New Products</a>
			&emsp;|&emsp;
			<a href="index2.html">Special Offers</a>
			&emsp;|&emsp;
			<a href="contact.html">Contact Us</a>
			&emsp;|&emsp;
			<a href="index2.html" class="terms">Privacy Policy</a>
			&emsp;|&emsp;
			<a href="index2.html" class="terms">Terms of Use</a>
			&emsp;|
			</nav>
			<p>
				Copyright &copy; ShopHeroes 2017. All rights reserved.
			</p>
			
	</footer>
</html>