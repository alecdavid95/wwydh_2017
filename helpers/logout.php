<?php
    if (!isset($_SESSION)) session_start();

    session_unset();
    session_destroy();
    unset($_SESSION['loggedin']);

    if (isset($_GET["go"])) {
        header("Location: ../".$_GET["go"]);
    } else {
        return true;
    }
?>
