<?php

    session_start();

    if (isset($_SESSION["user"]) && isset($_POST)) {
        include "../conn.php";

        $q = $conn->prepare("INSERT INTO plans (title, location_id, idea_id, creator_id) VALUES (?, ?, ?, ?)");
        $q->bind_param("ssss", $_POST["title"], $_POST["location"], $_POST["idea"], $_SESSION["user"]["id"]);
        $q->execute();

        // grab title of plan for message
        $q = $conn->prepare("SELECT title FROM plans WHERE id=$conn->insert_id");
        $q->execute();
        $title = $q->get_result()->fetch_array(MYSQLI_ASSOC)["title"];


        $_SESSION["message"] = ["success", "Idea added to '{$title}' successfully!"];

        echo $conn->insert_id;
    } else {
        echo "-1";
    }

?>
