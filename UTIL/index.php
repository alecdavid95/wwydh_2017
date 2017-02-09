<?php

    session_start();

    include "../helpers/conn.php";

    // BACKEND:0 change homepage location query to ORDER BY RAND() LIMIT 3
    $q = $conn->prepare("SELECT l.*, COUNT(DISTINCT p.id) AS plans, GROUP_CONCAT(DISTINCT f.feature SEPARATOR '[-]') AS features FROM locations l LEFT JOIN plans p ON p.location_id = l.id AND p.published = 1 LEFT JOIN location_features f ON f.location_id = l.id
    WHERE l.id < 37 OR l.id = 3 GROUP BY l.id ORDER BY plans DESC, RAND() LIMIT 4");
    $q->execute();

    $data = $q->get_result();
    $locations = [];

    while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
        if (isset($row["features"])) $row["features"] = explode("[-]", $row["features"]);
        array_push($locations, $row);
    }

    $q = $conn->prepare("SELECT i.*, count(up.id) as `upvotes` FROM ideas i LEFT JOIN upvotes_ideas up ON up.idea_id = i.id GROUP BY i.id ORDER BY `upvotes` DESC LIMIT 4");
    $q->execute();

    $data = $q->get_result();
    $ideas = [];

    while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
        array_push($ideas, $row);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WWYDH | <?php echo isset($_GET["contact"]) ? "Contact" : "Home" ?></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,600i,700" rel="stylesheet">
        <link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
        <link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzAMBl8WEWkqExNw16kEk40gCOonhMUmw" async defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://use.fontawesome.com/42543b711d.js"></script>
        <script src="../helpers/globals.js" type="text/javascript"></script>
        <script src="scripts.js" type="text/javascript"></script>

        <script type="text/javascript">
            // convert location data from php to javascript using JSON
            var locations = jQuery.parseJSON('<?php echo str_replace("'", "\'", json_encode($locations)) ?>');
        </script>
    </head>
    <body onload="initMap(); openNav();">
        <div id="nav">
            <div class="nav-inner width clearfix <?php if (isset($_SESSION['user'])) echo 'loggedin' ?> ">
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
                        <a href="../plans"><li>Plans</li></a>
                        <a href="../projects"><li>Projects</li></a>
                    </ul>
                </div>
            </div>
        </div>
        <div id="mapContainer">
            <div id="mySidenav" class="sidenav">
                <div class="sidebar-tools">

                </div>
                <a href="../ideas/new"><div id="sideIdea" class="side-button">I Have an Idea</div></a>
                <a href="../locations/new"><div id="sideLocation" class="side-button">I Have a Location</div></a>
                <div id="sideHelp" class="side-button">I Want to Contribute</div>
                <a href="../contact"><div id="sideContact" class="side-button">Contact Us</div></a>
            </div>
            <div id="map"></div>
            <div id="welcome">
                <div class="width">
                    <!--<span id="see-how"><h1>See How it Works!</h1></span>-->
                    <div id="see-how">See How it Works!</div>
                </div>
            </div>
        </div>
        <div id="explore">
            <div class="width">
                <h1> EXPLORE </h1>
                <ul class="tab">
                    <li class="active tablink" data-target="1">Locations</li>
                    <li class="tablink" data-target="2">Projects</li>
                </ul>
                <div id="locations" class="tabcontent active" data-tab="1">
                    <?php
                    foreach($locations as $l) { ?>
                        <div class="location">
                            <div class="options btn-group">
                                <div class="btn op-1"><a href="../dashboard?newplan&location=<?php echo $l["id"] ?>">Make a Plan Here</a></div>
                                <?php if ($l["plans"] > 0) { ?> <div class="btn op-2"><a href="../plans?location=<?php echo $l["id"] ?>">See other Plans here</a></div> <?php } ?>
                                <div class="btn op-3"><a href="../locations/propertyInfo.php?id=<?php echo $l["id"] ?>">View full location</a></div>
                            </div>
                            <div class="location_image" style="background-image: url(../helpers/location_images/<?php if (isset($l['image'])) echo $l['image']; else echo "no_image.jpg";?>);">
                                <?php if ($l["plans"] > 0) { ?>
                                    <div class="ideas_count"><?php echo $l["plans"] ?></div>
                                <?php } ?>
                            </div>
                            <div class="location_desc">
                                <div class="address"><?php echo $l["mailing_address"] ?></div>
                                <?php if (isset($l["features"])) { ?>
                                    <div class="features">
                                        <span>Features:</span>
                                            <ul>
                                                <?php foreach ($l["features"] as $f) { ?>
                                                    <li><?php echo $f ?></li>
                                                <?php } ?>
                                            </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
                <div id="projects" class="tabcontent" data-tab="2">
                    <!-- <div id="ideas" class="tabcontent active" data-tab="2"> -->
                   <?php
                   foreach($ideas as $i) { ?>
                       <div class="idea">
                           <div class="btn-group">
                               <div class="btn newidea"><a href="../newidea?location=<?php echo $l["id"] ?>">I have a similar idea</a></div>
                               <?php if ($i["ideas"] > 0) { ?> <div class="btn seeideas"><a href="../ideas?location=<?php echo $i["id"] ?>">See other ideas nearby</a></div> <?php } ?>
                               <div class="btn seelocation"><a href="../locations/propertyInfo.php?id=<?php echo $i["id"] ?>">View full location</a></div>
                           </div>
                           <div class="idea_image" style="background-image: url(../helpers/location_images/<?php if (isset($i['image'])) echo $i['image']; else echo "no_image.jpg";?>);">
                               <?php if ($l["ideas"] > 0) { ?>
                                   <div class="ideas_count"><?php echo $i["ideas"] ?></div>
                               <?php } ?>
                           </div>
                           <div class="idea_desc">
                               <div class="idea_leader"><?php echo $i["leader"] ?></div>
                               <div class="address"><?php echo $i["address"] ?></div>
                  <div class="idea_status">Status: <?php echo $i["completed"] == 0 ? "unfinished" : "finished" ?></div>
                               <?php if (isset($i["features"])) { ?>
                                   <div class="features">
                                       <span>Features:</span>
                                           <ul>
                                               <?php foreach ($i["features"] as $f) { ?>
                                                   <li><?php echo $f ?></li>
                                               <?php } ?>
                                           </ul>
                                   </div>
                               <?php } ?>
                           </div>
                       </div>
                   <?php }
                   ?>
                </div>
            </div>
        </div>
        <div id="about">
            <div class="grid-inner width">
                <h1>ABOUT</h1>
                <div class="small-content">
                    WWYDH facilitates economic and social revitalization in Baltimore by combining the existing vacant and underutilized built infrastructure with the creative imagination and skills of people throughout the city. We begin by asking the simplest question:
                </div>

                <p>"What would you do here?"</p>
            </div>
        </div>
        <div id="how">
            <div class="grid-inner width">
                <h1 style="text-align:center"><font color="#3a3a3a">HOW IT WORKS</font></h1>
                <table>
                	<tr>
                		<th><img src="../images/idea.png" /></th>
                		<th><img src="../images/contributors.png" /></th>
                		<th><img src="../images/implementation.png" /></th>
                	</tr>
                	<tr>
                		<td>Submit an idea for a location to WWYDH. You can choose to anonymously submit the idea for someone else to eventually lead, or lead the idea yourself. </td>
                		<td>Ideas can be upvoted and downvoted, so the best rise to the top. A checklist of people and resources the idea will need is generated. Users can contribute to the idea by volunteering to fill one or more of the requirements on the list. When the list is complete, the idea can move to the next stage.</td>
                		<td>The idea becomes a project, and the project is implemented by everyone who pledged to contribute his or her time and skills to turn a vacant location into a useful space for the community.</td>
                	</tr>
                </table>
            </div>
        </div>
        <div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
        </div>
    </body>
</html>
