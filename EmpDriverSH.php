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
					
					input.orders{
						text-align: left;
						width: 100%;
						background-color: white;
						border: none;
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
		
		<!--Bottom button-->
		<p align="center">
		<input type="submit" onclick="ShopStepChange(this)" id="Button" value="Begin Shopping" style="margin: 0.4em 0.7em; height:50px; width:180px" align="center">
		</p>
		<span id="output"></span>
		<span id="output"></span>
		
		<script type="text/javascript">
			/*function changereporttype(selectObj) {
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
			}*/
			
			var orderid = document.getElementById("orderid");
			function SelectOrderID(objButton)
			{
				var selectValue = objButton.value
				//$selectedReport = objButton.text;
				
				//Set order ID = button value
				orderid.innerHTML = objButton.value;
				//orderid.innerHTML = "Order ID#: " + orderid.innerHTML; 
			}
			
			function ShopStepChange(objButton)
			{
				//objButton.innerText="Changed";
				//document.getElementById("Button").value= "Hide Filter";
				//objButton.value = "Changed";
				var btnval = objButton.value;
					
				//how to insert rows
				//https://www.w3schools.com/jsref/met_table_insertrow.asp
				//how to get the row count
				//https://www.aspsnippets.com/Articles/Get-Count-Number-of-Rows-in-HTML-Table-using-JavaScript-and-jQuery.aspx
				
				<!----------------------Var declarations---------------------->
				var i = 0;
				var selectIndex=objButton.value;
				var selectValue=objButton.value;
				var output=document.getElementById("output");
				var totalRowCount = 0;
				var rowCount = 0;
				var table = document.getElementById("report");
				var rows = table.getElementsByTagName("tr")
				<!----------------------End declarations---------------------->
				
				/*for (i=message-2; i>=1; i--)
				{
					table.deleteRow(i);
				}*/
				
				for (var i = 0; i < rows.length; i++) 
				{
					totalRowCount++;
					
					if (rows[i].getElementsByTagName("td").length > 0) 
					{
						rowCount++;
					}
				}
				
				var message = totalRowCount;
				
				
				/*This if block is for the first page of the driver section.
				After the button on the bottom of the page is clicked the following
				blocks will take care of it.*/
				if (objButton.value == "Begin Shopping")
				{
					//Change button text
					objButton.value = "Begin Boxing Items";
					//showorderid.innerHTML = "test";
					//document.getElementById("Button").style.background='#b27a3a';
					
					//Delete everything in the table after the bottom button is clicked.
					//Then fill the table with the relevant information about the order ID selected.
					for (i=message-2; i>=0; i--)
					{
						table.deleteRow(i);
					}
					
					//output.innerHTML=ReportID;
					
					//Fill with relevant order data
					for (i=1; i <= 5; i++)
					{
						//var table = document.getElementById("report");
						var row = table.insertRow(i-1);
						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						var cell3 = row.insertCell(2);
						cell1.innerHTML = i + ")";
						cell2.innerHTML = "Example row for Driver";
						cell3.innerHTML = "Another row for Order #" + orderID;
					}
				
					
					
					
					//Doesn't even appear to be necessary
					//output.innerHTML = objButton.value;
				}
				else if(objButton.value == "Begin Boxing Items")
				{
					//Change bottom button text
					objButton.value = "Items Shelved";
					//output.innerHTML = objButton.value;
					
					//Delete the table rows
					for (i=message-2; i>=0; i--)
					{
						table.deleteRow(i);
					}
					
					//Fill with relevant order data
						var row = table.insertRow(0);
						var cell1 = row.insertCell(0);
						cell1.innerHTML = "Shelve items at the back of store.";
					//output.innerHTML="End of loop";
				}
				else if (objButton.value == "Items Shelved")
				{
					//Change bottom button text
					objButton.value = "Return To Order Selection";
					//output.innerHTML = objButton.value;
					
					//Clear the table
					for (i=message-2; i>=0; i--)
					{
						table.deleteRow(i);
					}
					
					//Update status
					var row = table.insertRow(0);
					var cell1 = row.insertCell(0);
					cell1.innerHTML = "The items are shelved and awaiting pickup.";
				}
				else if(objButton.value == "Return To Order Selection")
				{
					//Reload the table
					window.location.reload(true); 
				}
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