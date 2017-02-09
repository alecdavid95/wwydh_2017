<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Record Form</title>
<script type="text/JavaScript" src="Assignment4check.js"></script>
<link rel="stylesheet" href="Assignment3Style.css" />
</head>
<body style="background-image: url(scenicdrone.jpg);">
<?php
include 'db.inc.php';
// Connect to MySQL DBMS
if (!($connection = @ mysql_connect($hostName, $username,
  $password)))
  showerror();
// Use the cars database
if (!mysql_select_db($databaseName, $connection))
  showerror();
$result=mysql_query($query);
$row=mysql_fetch_array($result);
$id = $_GET['id'];

$getinfo = "select Name, Manufacturer, Price, Color, Type from assign5table WHERE id='$id'";
$query = mysql_query($getinfo);

while ($row = mysql_fetch_array($query)) {
    $name = $row['Name'];
    $man = $row['Manufacturer'];
	$price = $row['Price'];
    $color = $row['Color'];
	$type = $row['Type'];
}
?>
<form name="survey1" method="post"  action="continueUpdate.php" id="form" onsubmit="return checkFilled();"  style = "width: 300px; text-align: center; vertical-align: middle; margin-left:40%;">
			<fieldset style="background-color: #4CAF50;">
				Vehicle Name: <input type="text" name="vname" id="vname" value="<?=$name?>"><hr>
				Manufacturer: <input type="text" name="vman" id="vman"value="<?=$man?>"><hr>
				Color: <input type="text" name="vcolor" id="vcolor" value="<?=$color?>"><hr>
				Price: <input type="number" name="quantity" step="any" value="<?=$price?>"><hr>
				Type: 
				<select name="type">
					<option value="Land">Land</option>
					<option value="Air">Air</option>
					<option value="Both">Both</option>
				</select><hr>
				ID: <input type="number" name="id" step="any" value="<?=$id?>"><hr>
				<input type="reset" onclick="alert('You have reset the form');"/><br>
				<input type="submit" />
			</fieldset>
		</form>		
</body>
</html>


