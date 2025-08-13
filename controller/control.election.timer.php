<?php 

    require_once("../connection/conn.php");

    if (isset($_POST['timerStoper'])) {
        if ($_POST['timerStoper'] == 'timer') {
            $election_id = $_POST['election'];

            $query = "SELECT * FROM election WHERE eid = ? AND session = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$election_id, 1]);
            $result_count = $statement->rowCount();

            if ($result_count > 0) {
                $queryStop = "UPDATE election SET session = ? WHERE eid = ?";
                $statement = $conn->prepare($queryStop);
                $result = $statement->execute([2, $election_id]);
                if (isset($result)) {
                    header('Location: ../ended');
                } else {
                    //header('Location: ../logout');
                }
            }
        }
    }

?>