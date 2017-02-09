<?php
    session_start();

    require_once "../../helpers/vars.php";

    if (isset($_GET["location"])) {
        require_once "../../helpers/conn.php";
        $locationid = $_GET["location"];

        $q = $conn->prepare("SELECT l.*, COUNT(DISTINCT i.id) AS ideas, GROUP_CONCAT(DISTINCT f.feature SEPARATOR '[-]') AS features FROM locations l LEFT JOIN ideas i ON i.location_id = l.id LEFT JOIN location_features f ON f.location_id = l.id WHERE l.id=? GROUP BY l.id");
        $q->bind_param("s", $locationid);
        $q->execute();

        $location = $q->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    if (isset($_GET["idea"])) {
        //BACKEND:40 handle editing an idea here, EG change title, retrieve entry completion from database and set that pane as active, populate
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Idea</title>
		<link href="../../helpers/header_footer.css" type="text/css" rel="stylesheet" />
		<link href="../../helpers/splash.css" type="text/css" rel="stylesheet" />
		<link href="styles.css" type="text/css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="https://use.fontawesome.com/42543b711d.js"></script>
        <script src="../../helpers/globals.js" type="text/javascript"></script>
        <script src="scripts.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="width">
            <div id="nav">
                <div class="nav-inner width clearfix <?php if (isset($_SESSION['user'])) echo 'loggedin' ?>">
                    <a href="../../home">
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
		</div>
        <div class="outside" class="width">
            <div id="wrapper">
                <div class="pane active" data-index="1">
                    <div class="pane-title">
                        <div class="advance" data-target="2"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
                        <div class="title">Basic Information</div>
                    </div>
                    <div class="pane-content">
                        <div class="pane-content-intro">Would you like credit for this idea?</div>
                        <div class="button active" data-leader="1">
                            <div>Give me credit!</div>
                        </div>
                        <div class="button" data-leader="0">
                            <div>No thanks!</div>
                        </div>
                        <div class="login-warning active">This will require an account!</div>
                        <label for="title">Title</label>
                        <input name="title" type="text" placeholder="What is your idea? Be specific!" />
                        <label>Category</label>
                        <select name="category">
                            <option disabled selected>Choose one...</option>
                            <?php foreach ($idea_categories as $key => $lc) { ?>
                                <option value="<?php echo $key ?>"><?php echo $lc["title"] ?></option>
                            <?php } ?>

                        </select>
                        <label for="description">Description</label>
                        <textarea name="description" placeholder="Describe your idea in detail."></textarea>
                    </div>
                </div>
                <div class="pane" data-index="2">
                    <div class="pane-title">
                        <div class="advance" data-target="3"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
                        <div class="retreat" data-target="1"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                        <div class="title">Location Requirements</div>
                    </div>
                    <div class="pane-content">
                        <div class="pane-content-intro">
                            What does your location need to have?
                        </div>
                        <div class="location-checklist">
                            <div class="add-checklist-item"><i class="fa fa-plus" aria-hidden="true"></i> Add item</div>
                            <div class="checklist-item">
                                <input type="text" placeholder="Enter a location requirement here. EG: Electricity" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pane" data-index="3">
                    <div class="pane-title">
                        <div class="advance" data-target="4"><i class="fa fa-arrow-right" aria-hidden="true"></i></div>
                        <div class="retreat" data-target="2"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                        <div class="title">Contributors Needed</div>
                    </div>
                    <div class="pane-content">
                        <div class="pane-content-intro">
                            What contributions do you anticipate needing?
                        </div>
                        <div class="checklist">
                            <div class="add-checklist-item"><i class="fa fa-plus" aria-hidden="true"></i> Add item</div>
                            <div class="checklist-item">
                                <input type="text" placeholder="Enter a requirement here. EG: Truck x 4" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pane" data-index="4">
                    <div class="pane-title">
                        <!-- this advance handles completion, that's why the target is -1 -->
                        <div class="advance" data-target="-1"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                        <div class="retreat" data-target="3"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                        <div class="title">Preview</div>
                    </div>
                </div>
                <div class="pane" data-index="-1">
                    <!-- Login Required -->
                    <div class="pane-title">
                        <div class="title">Login Required!</div>
                    </div>
                    <div class="pane-content">
                        <div class="pane-content-intro error">
                            You must be logged in to receive credit for this idea!
                        </div>
                        <div class="login-marker">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </div>
                        <div class="login">
                            <input name="user" type="text" placeholder="username" spellcheck="false", autocorrect="off" />
                            <input name="pass" type="password" placeholder="password" />
                            <input name="submit" type="submit" value="Submit" />
                        </div>
                    </div>
                </div>
                <div class="pane" data-index="-2">
                    <!-- Successful Submission -->
                    <div class="pane-title">
                        <div class="title">Idea Submitted!</div>
                    </div>
                    <div class="pane-content">
                        <div class="pane-content-intro">
                            Your idea was submitted successfully! Great!
                        </div>
                        <div class="success-marker">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        <div class="next-steps">
                            <div class="sub-intro">
                                What's next?
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
