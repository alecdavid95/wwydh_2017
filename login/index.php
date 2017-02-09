<?php
    session_start();
    include("../helpers/conn.php");

    if (isset($_SESSION["user"])) {
        // already logged in
        header("Location: ../dashboard");
    } else if (!empty($_POST['username']) && !empty($_POST['password'])) {
        include "../helpers/makeUser.php";

        $q = $conn->prepare("SELECT * FROM users WHERE login=?");
        $q->bind_param("s", $pass);
        $q->execute();
        $result = $q->get_result();

        if ($result->num_rows == 1) {
            // user successfully authenticated
            $_SESSION["user"] = $result->fetch_array(MYSQLI_ASSOC);
            header("Location: ../dashboard");
        } else {
            $error = true;
        }

    } // else do nothing
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WWYDH | Login</title>
        <link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
        <link href="style.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="../helpers/globals.js" type="text/javascript"></script>
		<script type="text/JavaScript" src="check.js"></script>

        <script type="text/javascript">
            // focus the username text field on page load
            $(document).ready(function() {
                $("[name=username]").focus();
            })
        </script>

        <?php if (isset($error)) { ?>
            <script type="text/javascript">
                alert("Incorrect Username/Password!");
            </script>
        <?php } ?>
    </head>
    <body>
        <div class="bg"></div>
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
                            <a href="../login" class="active"><li>Log in</li></a>
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
        <div id="signin">
            <div class="title">Sign in to WWYDH</div>
               <form method="post" action="#" name="loginform" onsubmit="return checkFilled();">
                    <input type="text" placeholder="username"  name="username" id="vname" class="form-size" />
                    <input type="password" placeholder="password"  name="password" class="form-size" id="password"/>
                    <input name="login-submit" type="submit" id="enter" class="form-size" value="Sign In">
                </form>
        </div>
        <div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
        </div>
    </body>
</html>
