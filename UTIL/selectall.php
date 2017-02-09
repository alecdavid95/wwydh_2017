 <html><head><title>Autonomous Vehicles Table</title>
  <link rel="stylesheet" href="tablepage.css" />
 </head> 
<body style="background-image: url(drone.jpg);">
<div id="button">
<form action="test2.php" method="post">
<input type="submit" value="Show Full Joined Table"/>
</form>
<form action="test3.php" method="post">
<input type="submit" value="Table Join"/>
</form>
</div>
<form action="selectbytype.php" method="post">
<fieldset>
Select Drone Type: <select name="type">
<option value="Land">Land</option>
<option value="air">Air</option>
<option value="both">both</option>
</select>
<input type="submit" />
</fieldset>
</form>
<?php
echo "<table border=1>
 <tr><th class='styled-th'>ID</th><th class='styled-th'>Name</th><th class='styled-th'>Manufacturer</th><th class='styled-th'>Price</th>
 <th class='styled-th'>Color</th><th class='styled-th'>Type</th></tr>";
include 'db.inc.php';
// Connect to MySQL DBMS
if (!($connection = @ mysql_connect($hostName, $username,
  $password)))
  showerror();
// Use the cars database
if (!mysql_select_db($databaseName, $connection))
  showerror();
 
// Create SQL statement
$query = "SELECT * FROM assign5table";
// Execute SQL statement
if (!($result = @ mysql_query ($query, $connection)))
  showerror();
// Display results
while ($row = @ mysql_fetch_array($result))
  echo "<tr><td class='styled-td'>{$row["id"]}</td>
<td class='styled-td'>{$row["name"]}</td>
<td class='styled-td'>{$row["manufacturer"]}</td>
<td class='styled-td'>{$row["price"]}</td>
<td class='styled-td'>{$row["color"]}</td>
<td class='styled-td'>{$row["type"]}</td>
<td class='styled-td'><a href=\"delete.php?id=".$row['id']."\"><img src ='ex.png' width='30px' height='30px'></a></td>
<td class='styled-td'><a href=\"Update.php?id=".$row['id']."\"><img src ='arrow.png' width='30px' height='30px'></a></td>
</tr>";
?>
</body> 
<a href="Add.html" style ="margin 30px;"><img src="add.png" alt="add" style="width:84px;height:42px;border:0;"></a>
</form>
</html>