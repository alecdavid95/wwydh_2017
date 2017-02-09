
	<?php
		include "../helpers/conn.php";
		//$servername = "wwydh-mysql.cqqq2sesxkkq.us-east-1.rds.amazonaws.com";
		//$username = "wwydh_a_team";
		//$password = "nzqbzNU3drDhVsgHsP4f";

		//$conn = new mysqli($servername, $username, $password, "wwydh");
		$theQuery = "SELECT * FROM locations where `id`='{$_GET["id"]}'";
		$result = $conn->query($theQuery);
		$rowcount = mysqli_num_rows($result);
		$row = @mysqli_fetch_array($result);
  ?>

<!DOCTYPE html>
<html>

    <link href="styles.css" type="text/css" rel="stylesheet" />

    <head>
		    <title><?php echo $row["building_address"] ?></title>
    </head>

		<body>
	     <div class="imgViewer" style="background-image: url(../helpers/location_images/<?php echo $row["image"] ?>)";></div>
       <div class="name"><?php echo $row["building_address"] ?></div>
       <div class="info">
          <div class="generalInfo">
						<br>
		           	<h1>General City Information</h1>
          	<ul>
		          	<li><b>City: </b><?php echo $row["city"] ?></li>
              	<li><b>Neighborhood: </b><?php echo $row["neighborhood"] ?></li>
              	<li><b>Police District: </b><?php echo $row["police_district"] ?></li>
          	</ul>
					</br>
					<br>
						<h1>Current Use Information</h1>
						<ul>
							<li><b>Owner: </b><?php echo $row["owner"] ?></li>
							<li><b>What It Is Being Used As: </b><?php echo $row["use"] ?></li>
							<li><b>Mailing Address: </b> <?php echo $row["mailing_address"] ?></li>
						</ul>
					</br>
					</div>
					<div class="specInfo">
						<br>
						<h1>Specific Property Information</h1>
								<ul>
              	<li> <b>Block: </b><?php echo $row["block"] ?></li>
              	<li> <b>Lot: </b><?php echo $row["lot"] ?></li>
              	<li> <b>Zip Code: </b><?php echo $row["zip_code"] ?></li>
          		</ul>
						</br>
            <br>
            	<h1>Other</h1>
            	<ul>
              	<li><b>Council District: </b><?php echo $row["council_district"] ?></li>
              	<li><b>Longitude: </b><?php echo $row["longitude"] ?></li>
              	<li><b>Latitude: </b><?php echo $row["latitude"] ?></li>
            	</ul>
						</br>
						</div>
            <div class= "description">
              <br>
              <h1>Description </h1>
              	<p>This section includes a general description about this specific lot and </p>
              	<p>will include details provided by the creator of this location's page. </p>
              </br>
            </div>
						<div style="clear: both;"></div>
          </div>
    </body>
</html>
