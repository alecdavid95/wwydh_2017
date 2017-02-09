<?php
    session_start();
    include "../helpers/conn.php";
	$userInfoRow = null;
  $usrRankVal = null;
	if (isset($_GET["usrID"]))
	{
		$usrQuery = "SELECT * from user_profiles where id=" . $_GET["usrID"];
		$queryResult = $conn->query($usrQuery);
		$userInfoRow = @mysqli_fetch_array($queryResult);
	}
  if(isset($_GET["usrID"]))
  {
  $usrRankQry = "SELECT rank from user_profiles where id=" . $_GET["usrID"];
  $rqueryResult = $conn->query($usrRankQry);
  $rankRow = @mysqli_fetch_array($rqueryResult);
  }
  if(isset($_GET["usrID"]))
  {
  $comPlete = "SELECT completed from projects where id=" . $_GET["usrID"];
  $rComP = $conn->query($comPlete);
  $rCompRow = @mysqli_fetch_array($rComP);
  }
  if(isset($_GET["usrID"]))
  {
  $name = "SELECT first,last from users where id=" . $_GET["usrID"];
  $name1 = $conn->query($name);
  $nameRow = @mysqli_fetch_array($name1);
  }
	function writeSkillSection($image_path, $skillname, $description)
	{
		// echo "<div class=\"skillSection\">\n\t<img class=\"skillImg\" src=\"" . $image_path . "\"></img>\n\t<div class=\"skillLabel\">" . $skillname . "</div>\n\t<span class=\"tooltiptext\">" . $description . "</span>\n</div>";
    echo "<div class=\"skillSection\">\n\t<div class=\"skillLabel\">" . $skillname . "</div>\n\t<span class=\"tooltiptext\">" . $description . "</span>\n</div>";
	}
  function printRank($usrRankVal)
  {
   echo $usrRankVal;
  }
	//if (isset($_SESSION[)
?>
<!DOCTYPE html>
<html>
  <head>
    <title>WWYDH | <?php echo $nameRow["first"]." ".$nameRow["last"] ?></title>
		<link href="styles.css" type="text/css" rel="stylesheet" />
    <link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
	<body>
    <div class="bg"></div>
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

    <div style="text-align:center;
    background: #418040;
    background: -webkit-linear-gradient(left top, #50A050, #114010);
    background: -o-linear-gradient(bottom right, #70A050, #114010);
    background: -moz-linear-gradient(bottom right, #50A050,#114010);
    background: linear-gradient(to bottom right, #50A050, #114010);
    margin-top: 65px;
    width: 100%;
    height: 50%;
    min-height: 400px;">
      <div>&nbsp;</div>
      <div style="margin-left: 82%;
      text-alight:center;
      margin-top: 40px;
      width: 210px;
      height: 40px;
      background: #FFAE00;
      border-radius:4px;">
        <div>&nbsp;</div>
        <div style="margin-top: -34px">
          <a href="..\projects" style="color:#FFFFFF;text-decoration:none;"><h3>SUGGEST A PROJECT</h3></a>
        </div>
      </div>
      <div>&nbsp;</div>
      <div>&nbsp;</div>
      <center><div style="background-color: #FFFFFF;
      border-radius: 50%;
      width: 156px;
      height:156px;
      background-position: center;
      margin-top: -50px;">
        <div style="background-color: #FFFFFF;
        width: 1px;
        height: 3px;">
        </div>
        <div style="background-image: url(../helpers/user_images/<?php echo $_SESSION["user"]["image"] ?>);
          border-radius: 50%;
          width: 150px;
          height: 150px;
          background-position: center;
          background-size: cover;">
        </div>
      </div></center>
      <div>&nbsp;</div>
      <div>&nbsp;</div>
      <div style="color: #ffffff; margin-top: -50px;">
        <h1><?php echo $_SESSION["user"]["first"] ?> <?php echo $_SESSION["user"]["last"] ?></h1>
      </div>
    </div>
<div id="profile">
  <div class="BoxContainer">
  <div id="AboutMe" class="BioContent">
		<div class="BoxLabel">
			Bio
		</div>
		<div class="BoxContent">
			<?php
				echo $userInfoRow['about_me'];
			?>
		</div>
	</div>
	<div id="MySkills" class="skillContainer">
		<div class="BoxLabel">
			Contributions
		</div>
		<?php
			//TODO: Use SESSION instead of GET
			if (isset($_GET["loggedIn"]))
			{
			}
			$userSkillQuery = "SELECT skill_arr from user_profiles where id=" . $_GET["usrID"];
			$queryResult = $conn->query($userSkillQuery);
			$skillsArrRow = @mysqli_fetch_array($queryResult);
			$skills = explode(";", $skillsArrRow['skill_arr']);
			foreach ($skills as $currentSkill)
			{
				$skillsQuery = "SELECT * from user_skills where id=" . $currentSkill;
				$skillResult = $conn->query($skillsQuery);
				$skillsRow = @mysqli_fetch_array($skillResult);
				writeSkillSection($skillsRow['img'], $skillsRow['skill_name'], $skillsRow['skill_description']);
			}
		?>
  </div>
</div>

    <div id="AboutMe" class="BoxContainer">
  		<div class="BoxLabel">
  			Rank
  		</div>
      <div class="BoxContent">
        <?php
        echo $rankRow['rank'];
        ?>
      </div>
    </div>

        <div id="AboutMe" class="BoxContainer">
    	  	<div class="BoxLabel">
    	  		Completed Projects
    	  	</div>
          <div class="BoxContent">
            <?php
            echo $rCompRow['completed'];
            ?>
          </div>
        </div>
  <div id="footer">
          <div class="grid-inner">
              &copy; Copyright WWYDH <?php echo date("Y") ?>
          </div>
  </div>
</div>
	</body>
</html>
