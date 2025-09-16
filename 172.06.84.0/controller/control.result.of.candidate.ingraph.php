<?php

require_once("../../connection/conn.php");

if (isset($_POST['election_id'])) {
    $election_id = sanitize($_POST['election_id']);
    $election_name = sanitize($_POST['election_name']);
    $election_session = sanitize($_POST['election_session']);
        
    $candidate_name = '';
    $candidate_votes = '';
    $candidate_no_votes = '';
    $graph_bgcolor = '';
    $graph_bdcolor = '';

    $postion_query = "
        SELECT * FROM positions 
        WHERE election_id = ?
    ";
    $statement = $conn->prepare($postion_query);
    $statement->execute([$election_id]);
    $position_results = $statement->fetchAll();
    $position_count = $statement->rowCount();

    if ($position_count < 0) {
        
    } else {
        foreach ($position_results as $position_row) {
            $positionName = $position_row['position_name'];
            $positionId = $position_row['position_id'];
            
            $contestant_query = "
                SELECT * FROM vote_counts 
                INNER JOIN cont_details 
                ON cont_details.contestant_id = vote_counts.contestant_id 
                WHERE contestant_election = ? 
                AND cont_position = ? 
                AND cont_details.del_cont = ?
            ";
            $statement = $conn->prepare($contestant_query);
            $statement->execute([$election_id, $positionId, 'no']);
            $contestant_results = $statement->fetchAll();
            $contestant_count = $statement->rowCount();

            if ($contestant_count > 0) {
                $count_candidates = 0;
                foreach ($contestant_results as $crow) {
                    
                    $contestantName = ucwords($crow['cont_fname'] . ' ' . $crow['cont_lname']);

                    $candidate_name .= '"'.$contestantName.'", ';
                    $candidate_votes .= $crow['results'].', ';
                    $graph_bgcolor .= '"'.'rgba(' . rand(128,255) . ' ' . rand(128,255) . ' ' . rand(128,255) . ' / 15%'.')'.'", ';
                    $graph_bdcolor .= '"'.'rgba(' . rand(128,255) . ' ' . rand(128,255) . ' ' . rand(128,255) . ' / 15%'.')'.'", ';
                    $candidate_no_votes = $candidate_no_votes . '"'.$crow['results_no'].'", ';
              
                    $count_candidates++;
                }
            }
        }
    }
}

$candidate_name = trim($candidate_name, ", ");
$candidate_votes = trim($candidate_votes, ", ");
$graph_bgcolor = trim($graph_bgcolor, ", ");
$graph_bdcolor = trim($graph_bdcolor, ", ");
$candidate_no_votes = trim($candidate_no_votes, ", ");

?>

<script type="text/javascript">
    for (let i = 0; i < 5; i++) {

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= $candidate_name; ?>],
                datasets: [{
                    label: '# of Votes',
                    data: [<?= $candidate_votes; ?>],
                    backgroundColor: [<?= $graph_bdcolor; ?>],
                    borderColor: [<?= $graph_bdcolor; ?>],
                    borderWidth: 2,
                    borderRadius: 5,
                    borderSkipped: false
                },
                {
                    label: '# of No votes',
                    data : [<?= $candidate_no_votes; ?>],
                    backgroundColor: '#ff000005',
                    borderColor: '#ff00007a',
                    pointBackgroundColor: '#ffc107',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    }

</script>