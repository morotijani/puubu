<?php 

require_once("../../connection/conn.php");

if (isset($_POST['election_id'])) {
    $election_id = sanitize($_POST['election_id']);
    $election_name = sanitize($_POST['election_name']);
    $election_session = sanitize($_POST['election_session']);
        
    // GET THE REGISTRARS
    $registrars_query = "
        SELECT * FROM registrars 
        INNER JOIN election 
        ON election.election_id = registrars.registrar_election 
        WHERE election.election_id = ?
    ";
    $statement = $conn->prepare($registrars_query);
    $statement->execute([$election_id]);
    $registrars_count = $statement->rowCount();
    $registrars_result = $statement->fetchAll();

    $output = "
        <div class='card mt-2'>
            <div class='card-body'>
                <h4 class='mb-3 text-center'>Votes for each positions</h4>
    ";

    
    $postion_query = "
        SELECT * FROM positions 
        WHERE election_id = ?
    ";
    $statement = $conn->prepare($postion_query);
    $statement->execute([$election_id]);
    $position_results = $statement->fetchAll();
    $position_count = $statement->rowCount();

    if ($position_count < 0) {
        $output .= "
            <div class='alert alert-info'>
                There are no positions under <u>" . ucwords($election_name) . "</u>
            </div>
        ";
    } else {
        foreach ($position_results as $position_row) {
            $positionName = $position_row['position_name'];
            $positionId = $position_row['position_id'];

            $output .= "
                <div class='row align-items-center'>
                    <div class='col'>
                        <h4 class=''>" . strtoupper($positionName) . "</h4>
                    </div>
                    <div class='col-auto'>
                        <small class='text-danger' style='font-size: 15px'>Skipped Votes:  ". $position_row['position_skipped_votes'] . "</small>
                    </div>
                </div>
            ";
            
            $contestant_query = "
                SELECT * FROM vote_counts 
                INNER JOIN cont_details 
                ON cont_details.contestant_id = vote_counts.contestant_id 
                WHERE contestant_election = ? 
                AND cont_position = ? 
                AND cont_details.del_cont = ?
                ORDER BY contestant_ballot_number ASC
            ";
            $statement = $conn->prepare($contestant_query);
            $statement->execute([$election_id, $positionId, 'no']);
            $contestant_results = $statement->fetchAll();
            $contestant_count = $statement->rowCount();
            
            $sql8 = "
                SELECT COUNT(*) count_pc 
                FROM cont_details 
                WHERE contestant_election = :contestant_election 
                AND cont_position = :cont_position 
                AND del_cont = :del_cont
            ";
            $statement = $conn->prepare($sql8);
            $statement->execute(
                [
                    ':contestant_election' => $election_id,
                    ':cont_position' => $positionId,
                    ':del_cont' => 'no'
                ]
            );
            $sql8_count = $statement->rowCount();
            $sql8_result = $statement->fetchAll();

            if ($contestant_count > 0) {
                $output .= "<div class='row'>";
                foreach ($contestant_results as $crow) {
                    $contestantBallotNo = $crow['contestant_ballot_number'];
                    $contestantName = $crow['cont_fname'] . ' ' . $crow['cont_lname'];
                    $contestantPicture = $crow['cont_profile'];

                    $countVotes = $crow['results'];
                    $countVotesNO = $crow['results_no'];
                  
                    foreach ($sql8_result as $row8) {
                        if ($row8['count_pc'] > 1) {
                            $output .= "
                                <div class='col-md-3'>
                                    <div class='card'>
                                        <div class='card-body p-3'>
                                            <div class='row align-items-center'>
                                                <div class='col'>
                                                    <div class='d-flex align-items-center'>
                                                        <div class='avatar avatar-xl'>
                                                            <img class='avatar-img rounded' src='../media/uploadedprofile/".$contestantPicture."' alt='...' />
                                                        </div>
                                                        <div class='ms-4'>
                                                            <div class='fw-bold'> ". ucwords($contestantName) . "</div>
                                                            <div class=''>Ballot no: <span class=''>".$contestantBallotNo . "</span></div>
                                                            <div>Votes: <span class='badge bg-warning'>" . $countVotes . "</span> out of <span class='badge bg-primary'>" . $registrars_count . "</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class='col-auto'></div> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class='card'>
                                        <img src='../media/uploadedprofile/".$contestantPicture."' class='img-fluid' style='height: 150px; object-fit: contain; object-position: center;'>
                                        <div class='card-body'>
                                            <p class='text-center text-secondary lead'><u>".ucwords($contestantName)."</u>: <span class='badge badge-danger'>".$countVotes."</span> out of <spa class='badge badge-warning'>". $registrars_count ."</span></p>
                                        </div>
                                    </div> -->
                                </div>
                            ";
                        } else {
                            $output .= "
                                <div class='col-md-3'>
                                    <div class='card'>
                                        <div class='card-body p-3'>
                                            <div class='row align-items-center'>
                                                <div class='col'>
                                                    <div class='d-flex align-items-center'>
                                                        <div class='avatar avatar-xl'>
                                                            <img class='avatar-img rounded' src='../media/uploadedprofile/".$contestantPicture."' alt='...' />
                                                        </div>
                                                        <div class='ms-4'>
                                                            <div class='fw-bold'> ". ucwords($contestantName) . "</div>
                                                            <div class=''>Ballot no: <span class=''>".$contestantBallotNo . "</span></div>
                                                            <div>Yes votes: <span class='badge bg-warning'>" . $countVotes . "</span> out of <span class='badge bg-primary'>" . $registrars_count . "</span></div>
                                                            <div>No votes: <span class='badge bg-warning'>" . $countVotesNO . "</span> out of <span class='badge bg-primary'>" . $registrars_count . "</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class='col-auto'></div> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class='card'>
                                        <img src='../media/uploadedprofile/".$contestantPicture."' class='img-fluid' style='height: 150px; object-fit: contain; object-position: center;'>
                                        <div class='card-body'>
                                            <p class='lead text-center text-secondary'>
                                                <u>".ucwords($contestantName)."</u>
                                                <br>
                                                <span class='text-danger'>Yes votes</span> = <span class='badge badge-danger'>".$countVotes."</span> out of <spa class='badge badge-warning'>". $registrars_count ."</span>
                                            </p>
                                            <p class='lead text-center text-secondary'>
                                                <span class='text-info'>No votes</span> = <span class='badge badge-info'>".$countVotesNO."</span> out of <spa class='badge badge-warning'>". $registrars_count ."</span>
                                            </p>
                                        </div>
                                    </div> -->
                                </div>
                            ";
                        }
                    }

                }
                $output .= "
                    </div>
                    <hr>
                ";
            } else {
                $output .= "
                    <div class='alert alert-info'>There are no contestants under this position.</div>
                ";
            }
        }

        $sql = "
            SELECT * FROM voterhasdone 
            WHERE election_id = ?
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$election_id]);
        $countNumberVotes = $statement->rowCount();
        if ($statement->rowCount() > 0) {
            $output .= "
                <hr>
                <h4 class='text-center mb-3'>Overall Votes for the election</h4>
                <hr>
            ";
            $output .= "
                <p class='text-center'>Overall number of votes: <b>".$countNumberVotes."</b></p>
            ";
        } else {
            $output .= "";
        }
    }
}

if ($election_session == 2) {
    $output .= '
        <div class="text-center">
            <a target="_tab" href="report/full_election_report?election='.$election_id.'" class="btn btn-lg" style="background-color: #2f4f4f; color: #fff;">Generate report</a>
        </div>
    ';
}

  
$output .= "

        </div>
    </div>
";
echo $output;
?>
