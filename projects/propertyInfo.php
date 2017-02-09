<html>
	<?php
		include "../helpers/conn.php";
		//$servername = "wwydh-mysql.cqqq2sesxkkq.us-east-1.rds.amazonaws.com";
		//$username = "wwydh_a_team";
		//$password = "nzqbzNU3drDhVsgHsP4f";

		//$conn = new mysqli($servername, $username, $password, "wwydh");
		$theQuery = "SELECT * FROM locations where `id`='{$_GET["id"]}'";
		$result = $conn->query($theQuery);
		$rowcount=mysqli_num_rows($result);
		$row = @mysqli_fetch_array($result);
		
		echo "<head>";
		echo "<title>{$row["building_address"]}</title>";
		echo "<style>";
			echo ".imgViewer {width: 100%; height: 25em; background-color: #fbffdf; background-repeat:repeat; background-position: center;}";
			echo "td {padding: 1.5em;}";
			echo "h1, h3 {text-align: center;}";
		echo "</style>";
		echo "</head>";
		echo "<body>";
		echo "<div class=\"imgViewer\" style=\"background-image:url(../helpers/location_images/{$row["image"]})\">";
		echo "</div>";
		echo "<h1>{$row["building_address"]}</h1>";
		echo "<h3>Property Information</h3>";
		echo "<p align=\"center\">";
		echo "<table>";
			echo "<tr><td><b>Block: </b>{$row["block"]}</td><td><b>Lot: </b>{$row["lot"]}</td><td><b>Zip Code: </b>{$row["zip_code"]}</td></tr>";
			echo "<tr><td><b>City: </b>{$row["city"]}</td><td><b>Neighborhood: </b>{$row["neighborhood"]}</td><td><b>Police District: </b>{$row["police_district"]}</td></tr>";
			echo "<tr><td><b>Council District: </b>{$row["council_district"]}</td><td><b>Longitude: </b>{$row["longitude"]}</td><td><b>Latitude: </b>{$row["latitude"]}</td></tr>";
			echo "<tr><td><b>Owner: </b>{$row["owner"]}</td><td><b>Use: </b>{$row["use"]}</td><td><b>Mailing Address: </b>{$row["mailing_address"]}</td></tr>";
		echo "</table>";
		echo "</p>";
		echo "</body>";
	?>
</html>
