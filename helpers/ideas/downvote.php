<?php
    session_start();

    if (isset($_SESSION["user"]) && isset($_POST)) {
        include "../conn.php";

        $q = $conn->prepare("INSERT INTO downvotes_ideas (idea_id, user_id) VALUES (?, ?)");
        $q->bind_param("ss", $_POST["idea"], $_SESSION["user"]["id"]);
        $q->execute();

        $q = $conn->prepare("DELETE FROM upvotes_ideas WHERE user_id = ? AND idea_id = ?");
        $q->bind_param("ss", $_SESSION["user"]["id"], $_POST["idea"]);
        $q->execute();

        echo $_POST["idea"];
    }
?>
