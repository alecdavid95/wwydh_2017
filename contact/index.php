<!DOCTYPE html>
<html>
	<head>
		<title>Contact</title>
		<link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
		<link href="../helpers/splash.css" type="text/css" rel="stylesheet" />
		<link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="https://use.fontawesome.com/42543b711d.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="../helpers/globals.js" type="text/javascript"></script>
		<script src="scripts.js" type="text/javascript"></script>
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
	                            <a href="../contact" class="active"><li>Contact</li></a>
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
		</div>
    <div id="contact">
      <div class="width">
        <div id="contact_name">CONTACT US</div>
            <div id="form">
              <form action="#"> <!--BACKEND: Edit this action to wherever the form will submit to -->
                <input type="text" name="name" class="form-size" placeholder="Name"><br>
                <input type="text" name="email" class="form-size" placeholder="Email"><br>
                <textarea type="text" name="message" class="message" placeholder="Message"></textarea><br>
                <input type="submit" id="submit" class="form-size" value="Submit">
              </form>
            </div>
        </div>
    </div>
    <div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
    </div>
  </body>
</html>
