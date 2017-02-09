
<?php
echo "<table border=1>
 <tr><th>ID</th><th>Name</th><th>Manufacturer</th><th>Price</th>
 <th>Color</th><th>Type</th></tr>";
include 'db.inc.php';
// Connect to MySQL DBMS
if (!($connection = @ mysql_connect($hostName, $username,
  $password)))
  showerror();
// Use the cars database
if (!mysql_select_db($databaseName, $connection))
  showerror();

// create a variable
$vname=$_POST['vname'];
$vman=$_POST['vman'];
$vcolor=$_POST['vcolor'];
$quantity=$_POST['quantity'];
$type=$_POST['type'];
 
// Create SQL statement
$query = "INSERT INTO assign5table VALUES(null, '$vname', '$vman', '$quantity','$vcolor','$type')";
// Execute SQL statement
if (!($result = @ mysql_query ($query, $connection))){
  showerror();
}
else{
	header("Location: http://triton.towson.edu/~amyers24/selectall.php");
	exit;
}


?>
