
<?php
session_start();
if(!session_is_registered(myusername)){
header("location:HomeSH.html");
}
?>

<html>
<body>
Login Successful
</body>
</html>