<?php

    session_start();

    include "../../helpers/conn.php";

    // BACKEND:0 change homepage location query to ORDER BY RAND() LIMIT 3
    $q = $conn->prepare("SELECT l.* FROM locations l ORDER BY RAND() LIMIT 4");
    $q->execute();

    $data = $q->get_result();
    $locations = [];

    while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
        if (isset($row["features"])) $row["features"] = explode("[-]", $row["features"]);
        array_push($locations, $row);
    }

	//$currentState = "na";

	//if ($_GET["frmAction"] == "AddLocation")
		//$currentState = "std";
	if (isset($_GET["frmAction"]))
	{
		$currentState = $_GET["frmAction"];
	}
	else
	{
		$currentState = "std";
		unset($_SESSION["AddrData"]);
	}

	$ar_Row = null;
	$ar_Rowcount = 0;
	$addResult = "";

	if (isset($_GET["frmAction"]) && $_GET["frmAction"]=="AddLocation")
	{
		if (!isset($_SESSION["AddrData"]))
		{
			$_SESSION["AddrData"] = $_POST;
		}
		$addDB = mysqli_select_db($conn, "wwydh");
		$selQuery = "SELECT * from locations where building_address='{$_POST["sAddress"]}' AND city='{$_POST["sCity"]}'";

		$addResult = $conn->query($selQuery);
		$ar_Rowcount=mysqli_num_rows($addResult);
		$ar_Row = @mysqli_fetch_array($addResult);

		if ($ar_Rowcount > 0 && !(isset($_GET["override"]) && $_GET["override"]=="true"))
		{
			$currentState = "confirmReplace";
			header('Location: ?frmAction=confirmReplace&id=' . $ar_Row['id']);
			$addResult = "Already in DB";
		}
		else
		{
			$addQuery = "";
			if (!isset($_POST["sAddress"]) && isset($_SESSION["AddrData"]))
			{
				$addrInfoArr = $_SESSION["AddrData"];
				$addQuery = "INSERT INTO locations (building_address, block, lot, zip_code, city, neighborhood, police_district, council_district, longitude, latitude, owner, `use`, mailing_address) VALUES ('{$addrInfoArr["sAddress"]}', '{$addrInfoArr["sBlock"]}', '{$addrInfoArr["sLot"]}', '{$addrInfoArr["sZip"]}', '{$addrInfoArr["sCity"]}', '{$addrInfoArr["sNeighborhood"]}', '{$addrInfoArr["sPoliceDistrict"]}', '{$addrInfoArr["sCouncilDistrict"]}', '{$addrInfoArr["sLongitude"]}', '{$addrInfoArr["sLatitude"]}', '{$addrInfoArr["sOwner"]}', '{$addrInfoArr["sUse"]}', '{$addrInfoArr["sMailingAddr"]}')";
				unset($_SESSION["AddrData"]);
			}
			else
			{
				$addQuery = "INSERT INTO locations (building_address, block, lot, zip_code, city, neighborhood, police_district, council_district, longitude, latitude, owner, `use`, mailing_address) VALUES ('{$_POST["sAddress"]}', '{$_POST["sBlock"]}', '{$_POST["sLot"]}', '{$_POST["sZip"]}', '{$_POST["sCity"]}', '{$_POST["sNeighborhood"]}', '{$_POST["sPoliceDistrict"]}', '{$_POST["sCouncilDistrict"]}', '{$_POST["sLongitude"]}', '{$_POST["sLatitude"]}', '{$_POST["sOwner"]}', '{$_POST["sUse"]}', '{$_POST["sMailingAddr"]}')";
			}
			//$addResult = $conn->query($addQuery);
			if (!mysqli_query($conn,$addQuery))
			{
				$addResult = "" . mysqli_error($conn);
				$currentState = "err";
				//echo("Error description: " . mysqli_error($con));
			}
			else
			{
				mysqli_commit($conn);
				$addResult = "" . mysqli_error($conn);
				header('Location: ?frmAction=AddSuccess');
			}
		}
		mysqli_close($conn);

	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WWYDH | <?php echo isset($_GET["contact"]) ? "Contact" : "Home" ?></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,600i,700" rel="stylesheet">
        <link href="../../helpers/header_footer.css" type="text/css" rel="stylesheet" />
        <link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzAMBl8WEWkqExNw16kEk40gCOonhMUmw" async defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="../helpers/globals.js" type="text/javascript"></script>
        <script type="text/javascript">
            // convert location data from php to javascript using JSON
            var locations = jQuery.parseJSON('<?php echo str_replace("'", "\'", json_encode($locations)) ?>');

            function initMap() {
                // Create a map object and specify the DOM element for display.
                var map = new google.maps.Map(document.getElementById('map'), {
                    animation: google.maps.Animation.DROP,
                    center: {lat: parseFloat(locations[0].latitude), lng: parseFloat(locations[0].longitude)},
                    scrollwheel: false,
                    zoom: 14
                });

				google.maps.event.addListener(map, 'click', function (e) {
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({
						'latLng': e.latLng
					}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							document.getElementsByName('sAddress')[0].value=results[0].address_components[0].short_name +
								" " + results[0].address_components[1].short_name;
							document.getElementsByName('sMailingAddr')[0].value=results[0].address_components[0].short_name +
								" " + results[0].address_components[1].short_name;
							document.getElementsByName('sCity')[0].value=results[0].address_components[2].short_name;
							document.getElementsByName('sZip')[0].value=results[0].address_components[6].short_name;
							document.getElementsByName('sLatitude')[0].value=e.latLng.lat();
							document.getElementsByName('sLongitude')[0].value=e.latLng.lng();
							//alert(results[0].formatted_address);
						}
					}
					});
					//alert("Latitude: " + e.latLng.lat() + "\r\nLongitude: " + e.latLng.lng());
				});
        }

        </script>

        <!-- scroll on click to how it works -->
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $("#see-how").click(function() {
                    $("html, body").animate({scrollTop: $("#how").offset().top}, 650);
                })
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("li.tablink").click(function() {
                    if (!$(this).hasClass("active")) {
                        // handle nav change
                        $("li.tablink").removeClass("active");
                        $(this).addClass("active");

                        // handle content change
                        $(".tabcontent").removeClass("active");
                        $(".tabcontent[data-tab=" + $(this).data("target") + "]").addClass("active");
                    }
                })
            })
       </script>



    </head>
    <body onload="initMap()">
        <div id="nav">
            <div class="nav-inner width clearfix <?php if (isset($_SESSION['user'])) echo 'loggedin' ?>">
                <a href="../home">
                    <div id="logo"></div>
                    <div id="logo_name">What Would You Do Here?</div>
                    <div class="spacer"></div>
                </a>
                <div id="user_nav" class="nav">
                    <?php if (!isset($_SESSION["user"])) { ?>
                        <ul>
                            <a href="../../login"><li>Log in</li></a>
                            <a href="#"><li>Sign up</li></a>
                            <a href="../../contact"><li>Contact</li></a>
                        </ul>
                    <?php } else { ?>
                        <div class="loggedin">
                            <span class="click-space">
                                <span class="chevron"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                                <div class="image" style="background-image: url(../../helpers/user_images/<?php echo $_SESSION["user"]["image"] ?>);"></div>
                                <span class="greet">Hi <?php echo $_SESSION["user"]["first"] ?>!</span>
                            </span>

                            <div id="nav_submenu">
                                <ul>
                                    <a href="../../dashboard"><li>Dashboard</li></a>
                                    <a href="../../profile"><li>My Profile</li></a>
                                    <a href="../../helpers/logout.php?go=home"><li>Log out</li></a>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div id="main_nav" class="nav">
                    <ul>
                        <a href="../../locations"><li>Locations</li></a>
                        <a href="../../ideas"><li>Ideas</li></a>
                        <a href="../../plans"><li>Plans</li></a>
                        <a href="../../projects"><li>Projects</li></a>
                    </ul>
                </div>
            </div>
        </div>
        <div id="mapContainer">
            <div id="map"></div>
            <div id="welcome">
			<div class="width">
			Click on the map or enter info manually.
                </div>
            </div>
        </div>
		<div id="addAction">
			<div id="addLocForm" <?php if ($currentState!="std") echo " style=\"display: none;\"";?>>
			<?php
			if ($currentState=="AddLocation")
			{
				echo "<b>AddLoc</b>";
				echo $ar_Rowcount;
				echo "<br/>";
				echo $addResult;
				echo $_POST["sAddress"];
			}
			?>
				<form action="?frmAction=AddLocation" method="post">
					Address: <input type="text" class="addLocFormInput" name="sAddress"><br>
					Block: <input type="text" class="addLocFormInput" name="sBlock"><br>
					Lot: <input type="text" class="addLocFormInput" name="sLot"><br>
					Zip Code: <input type="text" class="addLocFormInput" name="sZip"><br>
					City: <input type="text" class="addLocFormInput" name="sCity"><br>
					Neighborhood: <input type="text" class="addLocFormInput" name="sNeighborhood"><br>
					Police District: <input type="text" class="addLocFormInput" name="sPoliceDistrict"><br>
					Council District: <input type="text" class="addLocFormInput" name="sCouncilDistrict"><br>
					Longitude: <input type="text" class="addLocFormInput" name="sLongitude"><br>
					Latitude: <input type="text" class="addLocFormInput" name="sLatitude"><br>
					Owner: <input type="text" class="addLocFormInput" name="sOwner"><br>
					Use: <input type="text" class="addLocFormInput" name="sUse"><br>
					Mailing Address: <input type="text" class="addLocFormInput" name="sMailingAddr"><br>
					<input type="submit" class="addLocFormInput">
				</form>
			</div>
			<div id="duplicateLoc" <?php if ($currentState!="confirmReplace")	echo " style=\"display: none;\"";?>>
				<?php
					$matchQuery = "SELECT * from locations where id='{$_GET["id"]}'";

					$matchResult = $conn->query($matchQuery);
					$match_Row = @mysqli_fetch_array($matchResult);
				?>
				This property appears to be in our database already. Is this the property? <br/><br/>
				<div class="duplicatePreview" style="background-image: url('../helpers/location_images/<?php echo $match_Row["image"];?>');">
				</div>
				<br/>
				Address: <?php echo $match_Row["building_address"]; ?> <br/>
				City: <?php echo $match_Row["city"]; ?> <br/>
				Zip Code: <?php echo $match_Row["zip_code"]; ?> <br/>
				<div style="margin: auto; width: 50%;">
				<a href="add.php"><div id="locationButton">Yes, this is the property</div></a>
				<a href="add.php?frmAction=AddLocation&override=true"><div id="locationButton">No, add my property</div></a><br/>
				</div>
			</div>
			<div id="addSucc" <?php if ($currentState!="AddSuccess")	echo " style=\"display: none;\"";?>>
				Property added successfully! <br/>
			</div>
		</div>
        <div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
        </div>
    </body>
</html>
