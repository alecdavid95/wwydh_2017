<?php
    header("Content-type: text/plain");

    session_start();
    require_once "conn.php";

    if (isset($_SESSION["user"])) {
        // already logged in, return 1
        echo 1;
        exit();
    }

    if (strlen($_POST["username"]) == 0 || strlen($_POST["password"]) == 0) {
        echo 0;
        exit();
    }

    // BACKEND: HASH YOUR DAMN PASSWORDS BRIAN xD
    $q = $conn->prepare("SELECT * FROM users WHERE username=? and password=?");
    $q->bind_param("ss", $_POST["username"], $_POST["password"]);
    $q->execute();

    $user = $q->get_result()->fetch_array(MYSQLI_ASSOC);

    if (isset($user["id"])) {
        // login successful, return 1
        $_SESSION["user"] = $user;
        echo 1;
        exit();
    } else {
        echo 0;
        exit();
    }
?>
