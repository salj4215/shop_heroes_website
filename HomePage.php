<?php
include('index.php');
$qry = "SELECT * FROM USERS JOIN EMPLOYEES ON USERS.UserID = EMPLOYEES.UserID WHERE Username='$userEmail'";
$stmt = $pdo->query($qry);
while ($row = $stmt->fetch()) {
?>
<pre><?php print_r($row); ?></pre>
<?php
}

$qry = "SELECT * FROM EMPLOYEES";
$stmt = $pdo->query($qry);
while ($row = $stmt->fetch()) {
    ?>
    <pre><?php print_r($row); ?></pre>
    <?php
}


$qry = "SELECT * FROM USERS";
$stmt = $pdo->query($qry);
while ($row = $stmt->fetch()) {
    ?>
    <pre><?php print_r($row); ?></pre>
    <?php
}

?>