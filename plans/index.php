<?php

	session_start();

	include "../helpers/paginate.php";
	include "../helpers/vars.php";
	include "../helpers/conn.php";

	$theQuery = "";
	$result = null;

	// count all records for pagination
	$q = $conn->prepare("SELECT COUNT(i.id) as total FROM plans i");
	$q->execute();

	$total = $q->get_result()->fetch_array(MYSQLI_ASSOC)["total"];
	$offset = $itemCount * ($page - 1);

	// BACKEND:10 change locations search code to prepared statements to prevent SQL injection
	if (isset($_GET["isSearch"])) {
		$theQuery = "SELECT * FROM `locations` WHERE `building_address` LIKE '%{$_GET["sAddress"]}%' AND `building_address` LIKE '%{$_GET["sAddress"]}%' AND `block` LIKE '%{$_GET["sBlock"]}%' AND `lot` LIKE '%{$_GET["sLot"]}%' AND `zip_code` LIKE '%{$_GET["sZip"]}%' AND `city` LIKE '%{$_GET["sCity"]}%' AND `neighborhood` LIKE '%{$_GET["sNeighborhood"]}%' AND `police_district` LIKE '%{$_GET["sPoliceDistrict"]}%' AND `council_district` LIKE '%{$_GET["sCouncilDistrict"]}%' AND `longitude` LIKE '%{$_GET["sLongitude"]}%' AND `latitude` LIKE '%{$_GET["sLatitude"]}%' AND `owner` LIKE '%{$_GET["sOwner"]}%' AND `use` LIKE '%{$_GET["sUse"]}%' AND `mailing_address` LIKE '%{$_GET["sMailingAddr"]}%'";
	} else if (isset($_GET["location"])) {
		$q = $conn->prepare("SELECT u.name AS `name`, i.*, GROUP_CONCAT(cc.description SEPARATOR '[-]') as `checklist`, l.mailing_address, l.image FROM ideas i LEFT JOIN users u ON u.id = i.leader_id
		LEFT JOIN locations l ON i.location_id = l.id
		LEFT JOIN checklists c ON c.idea_id = i.id
		LEFT JOIN checklist_items cc ON cc.checklist_id = c.id
		WHERE cc.contributer_id IS NULL AND i.location_id = {$_GET["location"]} GROUP BY i.id");
	} else {
		$q = $conn->prepare("SELECT pl.*, i.*, l.*, i.image AS `idea image`, GROUP_CONCAT(DISTINCT f.feature SEPARATOR '[-]') AS features FROM plans pl LEFT JOIN ideas i ON i.id = pl.idea_id LEFT JOIN locations l ON l.id = pl.location_id LEFT JOIN location_features f ON f.location_id = l.id WHERE pl.published = 1 GROUP BY l.id, i.id  ORDER BY i.id");
	}

	$q->execute();
	$data = $q->get_result();

	$plans = [];

	$row = $data->fetch_array(MYSQLI_ASSOC);
	$plans[$row["idea_id"]] = [];
	array_push($plans[$row["idea_id"]], $row);

	while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
		if (array_key_exists($row["idea_id"], $plans)) {
			array_push($plans[$row["idea_id"]], $row);
		} else {
			$plans[$row["idea_id"]] = [];
			array_push($plans[$row["idea_id"]], $row);
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>All Plans</title>
		<link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
		<link href="../helpers/splash.css" type="text/css" rel="stylesheet" />
		<link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="https://use.fontawesome.com/42543b711d.js"></script>
		<script src="../helpers/globals.js" type="text/javascript"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$( function() {
	$( "#accordion" ).accordion();
} );
</script>
	</head>
	<body>
		<div class="width">
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
	                            <a href="../login"><li>Log in</li></a>
	                            <a href="#"><li>Sign up</li></a>
	                            <a href="../contact"><li>Contact</li></a>
	                        </ul>
	                    <?php } else { ?>
	                        <div class="loggedin">
	                            <span class="click-space">
	                                <span class="chevron"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
	                                <div class="image" style="background-image: url(../helpers/user_images/<?php echo $_SESSION["user"]["image"] ?>);"></div>
	                                <span class="greet">Hi <?php echo $_SESSION["user"]["first"] ?>!</span>
	                            </span>

	                            <div id="nav_submenu">
	                                <ul>
	                                    <a href="../dashboard"><li>Dashboard</li></a>
	                                    <a href="../profile"><li>My Profile</li></a>
	                                    <a href="../helpers/logout.php?go=home"><li>Log out</li></a>
	                                </ul>
	                            </div>
	                        </div>
	                    <?php } ?>
	                </div>
	                <div id="main_nav" class="nav">
	                    <ul>
	                        <a href="../locations"><li>Locations</li></a>
	                        <a href="../ideas"><li>Ideas</li></a>
	                        <a href="../plans" class="active"><li>Plans</li></a>
	                        <a href="../projects"><li>Projects</li></a>
	                    </ul>
	                </div>
	            </div>
	        </div>
		</div>
		<div id="splash">
			<div class="splash_content">
				<h1>Search Plans</h1>
				<form method="POST">
					<input type="submit" name="simple_search" value="Search"></input>
					<input name="search" type="text" placeholder="Enter an address, category, or search keywords" />
				</form>
			</div>
			<div class="new-of-type">
				Add New Plan
				<i class="fa fa-plus" aria-hidden="true"></i>
			</div>
		</div>

		<div class="grid-inner width">
			<div id="toolbar">
				<div id="item-count">
					Showing <span><?php echo $offset + 1 ?></span> -
					<span><?php echo ($total - $offset > $itemCount) ? $itemCount : $total ?></span> of <?php echo $total ?>
				</div>
				<div id="sort">
					<span>Sort by</span>
					<select>
						<option value="default" selected>Upvotes: High to Low</option>
						<option value="upvotes-asc"
							<?php if (isset($_GET["sort"]) && $_GET["sort"] == "upvotes-asc") echo "selected" ?>
						>Upvotes: Low to High</option>
						<option value="date-desc"
							<?php if (isset($_GET["sort"]) && $_GET["sort"] == "date-desc") echo "selected" ?>
						>Date: Newest to Oldest</option>
						<option value="date-asc"
							<?php if (isset($_GET["sort"]) && $_GET["sort"] == "date-asc") echo "selected" ?>
						>Date: Oldest to Newest</option>
					</select>
				</div>
				<div style="clear: both"></div>
			</div>
			<div class="add-to-plan">
				<ul>
					<li class="create">
						<i class="fa fa-plus" aria-hidden="true"></i>
						<span>Create new plan</span>
						<div class="plan-title">
							<form>
								<input name="plan-title" type="text" placeholder="Plan Title" />
								<input type="submit" value="Go!" />
							</form>
						</div>
					</li>
					<?php if (isset($plans)) {
						 foreach ($plans as $p)  { ?>
							<?php if ($p["has idea"] == "false") { ?>
								<li class="existing" data-plan="<?php echo $p["id"] ?>"><?php echo $p["title"] ?></li>
							<?php } ?>
					<?php }
					} ?>
				</ul>
			</div>

		</div>
		<div class="grid-inner width">
			<?php
			foreach ($plans as $plan) {
				$row = $plan[0]; // selects the first element to use as the idea row since all rows have the same idea information xD ?>
				<div class="idea">
					<div class="grid-item width">
						<div class="vote">
							<div class="upvote">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
							</div>
							<div class="downvote">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
							</div>
						</div>
						<div class="idea_image_wrapper">
							<i class="fa <?php echo $idea_categories[$row['category']]['fa-icon'] ?>"></i>
							<div class="overlay"></div>
							<div class="idea_image" style="background-image: url(../helpers/idea_images/<?php echo $row["idea image"]?>);"></div>
						</div>
						<div class="idea_desc">
							<div class="title"><?php echo $row["title"] ?></div>
							<div class="category"><?php echo $idea_categories[$row['category']]["title"] ?></div>
							<div class="description"><?php echo $row["description"] ?></div>
							<?php /* ?>
							<?php if (count($row["checklist"]) > 0) { ?>
								<div class="checklist">
									<span>Contributors Needed: </span>
									<ul>
										<?php for ($i = 0; $i < count($row["checklist"]) && $i < 4; $i++) { ?>
											<li><?php echo $row["checklist"][$i] ?></li>
										<?php } ?>
										<?php if (count($row["checklist"]) > 4) { ?>
											<span><?php echo count($row["checklist"]) - 4 ?>+ more</span>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<?php */ ?>
						</div>
					</div>
					<div class="locations">
						<?php foreach($plan as $location) {
							if (isset($location["features"])) $location["features"] = implode(" | ", explode("[-]", $location["features"])); ?>
							<div class="location">
								<div class="vote">
									<div class="upvote">
										<i class="fa fa-thumbs-up" aria-hidden="true"></i>
									</div>
									<div class="downvote">
										<i class="fa fa-thumbs-down" aria-hidden="true"></i>
									</div>
								</div>

								<div class="location_image" style="background-image: url(../helpers/location_images/<?php echo $location["image"] ?>)"></div>
								<div class="location_address"><?php echo $location["building_address"]." ".$location["city"].", Maryland ".$location["zip_code"] ?></div>
								<div class="location_features"><?php echo $location["features"] ?></div>
								<div style="clear: both"></div>
							</div>
						<?php } ?>
					</div>
		 	<?php }
			?>
		</div>
	</div>
		<div id="pagination">
			<div class="grid-inner">
				<ul>
				<?php
					$starting_page = ($page - 5 > 0) ? $page - 5 : 1;
					$ending_page = ($page + 5 < ceil($total / $itemCount)) ? $page + 5 : ceil($total / $itemCount);

					for ($i = 0; $i <= 10 && $starting_page + $i <= $ending_page; $i++) { ?>
						<li><a <?php echo ($page == $starting_page + $i) ? 'class="active"' : "" ?>
							href="?page=<?php echo $starting_page + $i ?>"><?php echo $starting_page + $i ?></a>
						</li>
				<?php } ?>
				</ul>
			</div>
		</div>
		<div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
        </div>
	</body>
</html>
