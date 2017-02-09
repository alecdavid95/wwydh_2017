<?php

	session_start();

	include "../helpers/paginate.php";
	include "../helpers/vars.php";
	include "../helpers/conn.php";

	$theQuery = "";
	$result = null;

	// count all records for pagination
	$q = $conn->prepare("SELECT COUNT(i.id) as total FROM ideas i");
	$q->execute();

	$total = $q->get_result()->fetch_array(MYSQLI_ASSOC)["total"];
	$offset = $itemCount * ($page - 1);

	$id = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : 0;

	// set the $_GET variables to appended to links that reload this page
	$urlExtras = "?";
	if (isset($_GET["page"])) $urlExtras += "page=".$_GET["page"]."&";
	if (isset($_GET["sort"])) $urlExtras += "sort=".$_GET["sort"]."&";

	// BACKEND:10 change locations search code to prepared statements to prevent SQL injection
	if (isset($_GET["isSearch"])) {
		$theQuery = "SELECT * FROM `locations` WHERE `building_address` LIKE '%{$_GET["sAddress"]}%' AND `building_address` LIKE '%{$_GET["sAddress"]}%' AND `block` LIKE '%{$_GET["sBlock"]}%' AND `lot` LIKE '%{$_GET["sLot"]}%' AND `zip_code` LIKE '%{$_GET["sZip"]}%' AND `city` LIKE '%{$_GET["sCity"]}%' AND `neighborhood` LIKE '%{$_GET["sNeighborhood"]}%' AND `police_district` LIKE '%{$_GET["sPoliceDistrict"]}%' AND `council_district` LIKE '%{$_GET["sCouncilDistrict"]}%' AND `longitude` LIKE '%{$_GET["sLongitude"]}%' AND `latitude` LIKE '%{$_GET["sLatitude"]}%' AND `owner` LIKE '%{$_GET["sOwner"]}%' AND `use` LIKE '%{$_GET["sUse"]}%' AND `mailing_address` LIKE '%{$_GET["sMailingAddr"]}%'";
	} else {
		if (isset($_GET["sort"]) && $_GET["sort"] == "upvotes-asc") $sort = "`upvotes` ASC";
		elseif (isset($_GET["sort"]) && $_GET["sort"] == "date-desc") $sort = "`timestamp` DESC";
		elseif (isset($_GET["sort"]) && $_GET["sort"] == "date-asc") $sort = "`timestamp` ASC";
		// dafault case
		else $sort = "`upvotes` DESC";

		$q = $conn->prepare("SELECT i.*,
			(SELECT COUNT(up_i.id) FROM upvotes_ideas up_i WHERE up_i.idea_id = i.id) AS `upvotes`,
			(SELECT COUNT(up_i_u.id) FROM upvotes_ideas up_i_u WHERE up_i_u.user_id = $id AND up_i_u.idea_id = i.id) AS `upvoted`,
			COUNT(pl.id) AS `plans` FROM ideas i LEFT JOIN plans pl ON pl.idea_id = i.id GROUP BY i.id ORDER BY $sort LIMIT $itemCount OFFSET $offset");
	}

	$q->execute();
	$data = $q->get_result();

	// if user is logged in, get users plans and identify whether or not they have an idea attached to them
	if (isset($_SESSION["user"])) {
		$q = $conn->prepare("SELECT pl.*, IF(COUNT(i.id) > 0, 'true', 'false') AS `has idea` FROM plans pl LEFT JOIN ideas i ON pl.idea_id = i.id WHERE pl.creator_id = {$_SESSION["user"]["id"]} AND pl.published = 0 GROUP BY pl.id");
		$q->execute();

		$users_plans = $q->get_result();
		$plans = [];

		while ($row = $users_plans->fetch_array(MYSQLI_ASSOC)) array_push($plans, $row);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>All Ideas</title>
		<link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
		<link href="../helpers/splash.css" type="text/css" rel="stylesheet" />
		<link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="https://use.fontawesome.com/42543b711d.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="../helpers/globals.js" type="text/javascript"></script>
		<script src="scripts.js" type="text/javascript"></script>

		<?php if (isset($_SESSION["message"])) { ?>
			<script type="text/javascript">
				setTimeout(function() {
					$(".notification").addClass("visible");

					setTimeout(function() {
						$(".notification").removeClass("visible");
					}, 4000);
				}, 600);
			</script>
		<?php } ?>
	</head>
	<body>
		<div class="width">
			<?php if (isset($_SESSION["message"])) { ?>
				<div class="notification <?php echo $_SESSION["message"][0] ?>">
					<span><?php echo $_SESSION["message"][1] ?></span>
				</div>
				<?php
				unset($_SESSION["message"]);
			} ?>
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
	                        <a href="../ideas" class="active"><li>Ideas</li></a>
	                        <a href="../plans"><li>Plans</li></a>
	                        <a href="../projects"><li>Projects</li></a>
	                    </ul>
	                </div>
	            </div>
	        </div>
		</div>
		<div id="splash">
			<div class="splash_content">
				<h1>Search Ideas</h1>
				<form method="POST">
					<input type="submit" name="simple_search" value="Search"></input>
					<input name="search" type="text" placeholder="Enter a category or search keywords" />
				</form>
			</div>
			<div class="new-of-type">
				New Idea
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
			<?php
			while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
				if (isset($row["checklist"])) $row["checklist"] = explode("[-]", $row["checklist"]); ?>

				<div class="idea
				<?php if (isset($row["owner"]) && $row["owner"] == $_SESSION["user"]["id"]) echo "mine" ?>"
				data-idea="<?php echo $row["id"] ?>">
					<div class="grid-item width">
						<div class="plan-buttons options btn-group">
							<div class="btn op-1"><a>Add to plan <i class="fa fa-sort" aria-hidden="true"></i></a></div>
							<?php if ($row["plans"] > 0) { ?> <div class="btn op-2"><a href="../plans?location=<?php echo $row["id"] ?>">See other plans with this idea</a></div> <?php } ?>
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
						<div class="vote">
							<div class="upvote <?php if ($row["upvoted"] == 1) echo "me"; ?>">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
								<div class="vote_count"><?php echo $row["upvotes"] ?></div>
							</div>
							<div class="downvote <?php if ($row["downvoted"] == 1) echo "me"; ?>">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
								<div class="vote_count"><?php echo $row["downvotes"] ?></div>
							</div>
							<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
						</div>
						<div class="idea_image_wrapper">
							<?php if (isset($row["owner"]) && isset($_SESSION["user"]) && $row["owner"] == $_SESSION["user"]["id"]) { ?>
								<div class="corner-ribbon idea-mine">mine</div>
							<?php } ?>
							<i class="fa <?php echo $idea_categories[$row['category']]['fa-icon'] ?>"></i>
							<div class="overlay"></div>
							<div class="idea_image" style="background-image: url(../helpers/idea_images/<?php if (isset($row['image'])) echo $row['image']; else echo "no_image.jpg";?>);"></div>
						</div>
						<div class="idea_desc">
							<div class="title"><?php echo $row["title"] ?></div>
							<div class="post_date">Posted on:  <span><?php echo date("F j, Y", strtotime($row["timestamp"])) ?></span></div>
							<div class="category">Category: <span><?php echo $idea_categories[$row['category']]["title"] ?></span></div>
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
				</div>
		 	<?php }
			?>
		</div>
		<div id="pagination">
			<div class="grid-inner">
				<ul>
				<?php
					// appends other $_GET variables to the url before following it
					$urlExtras = "?";
					if (isset($_GET["sort"])) $urlExtras .= "sort=".$_GET["sort"]."&";

					$starting_page = ($page - 5 > 0) ? $page - 5 : 1;
					$ending_page = ($page + 5 < ceil($total / $itemCount)) ? $page + 5 : ceil($total / $itemCount);

					for ($i = 0; $i <= 10 && $starting_page + $i <= $ending_page; $i++) { ?>
						<li><a <?php echo ($page == $starting_page + $i) ? 'class="active"' : "" ?>
							href="<?php echo $urlExtras ?>page=<?php echo $starting_page + $i ?>"><?php echo $starting_page + $i ?></a>
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
