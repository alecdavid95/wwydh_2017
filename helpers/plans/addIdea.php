<?php

    session_start();

    if (isset($_SESSION["user"]) && isset($_POST)) {
        include "../conn.php";

        $q = $conn->prepare("UPDATE plans SET idea_id = ? WHERE id = ?");
        $q->bind_param("ss", $_POST["idea"], $_POST["plan"]);
        $q->execute();

        // grab title of plan for message
        $q = $conn->prepare("SELECT title FROM plans WHERE id=?");
        $q->bind_param("s", $_POST["plan"]);
        $q->execute();
        $title = $q->get_result()->fetch_array(MYSQLI_ASSOC)["title"];


        $_SESSION["message"] = ["success", "Idea added to '{$title}' successfully!"];

        echo $_POST["plan"];
    } else {
        echo "-1";
    }

?>
