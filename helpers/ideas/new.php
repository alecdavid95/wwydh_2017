<?php
    header("Content-type: text/plain");

    session_start();

    // check if data was submitted
    if (isset($_POST["submit"])) {
        require_once "../conn.php";

        // check login, return login error
        if ((!isset($_SESSION["user"])) && $_POST["leader"] == "true") {
            echo "-1";
            exit();
        }

        $q = $conn->prepare("INSERT INTO ideas (title, description, category) VALUES (?, ?, ?)");
        $q->bind_param("sss", $_POST["title"], $_POST["description"], $_POST["category"]);
        $q->execute();

        $id = $conn->insert_id;

        $location_requirements = explode("[-]", $_POST["location_requirements"]);
        $contributions = explode("[-]", $_POST["contributions"]);

        foreach ($location_requirements as $l) {
            $q = $conn->prepare("INSERT INTO location_requirements (idea_id, requirement) VALUES (?, ?)");
            $q->bind_param("ss", $id, $l);
            $q->execute();
        }

        // create new checklist and capture id
        $q = $conn->prepare("INSERT INTO checklists (idea_id) VALUES ($id)");
        $q->execute();

        $checklist_id = $conn->insert_id;

        foreach ($contributions as $c) {
            $q = $conn->prepare("INSERT INTO checklist_items (checklist_id, description) VALUES (?, ?)");
            $q->bind_param("ss", $checklist_id, $c);
            $q->execute();
        }

        echo "1"; // return success
        exit();
    } else {
        echo "Access Denied";
    }
?>
