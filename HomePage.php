<?php
include('index.php');
$qry = "SELECT * FROM USERS JOIN EMPLOYEES ON USERS.UserID = EMPLOYEES.UserID";
$stmt = $pdo->query($qry);
while ($row = $stmt->fetch()) {
?>
<pre><?php print_r($row); ?></pre>
<?php
}

?>