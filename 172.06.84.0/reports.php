<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
//include ('includes/left-nav.inc.php');

if (isset($_GET['report']) && !empty($_GET['election'])) {
    $election_id = sanitize((int)$_GET['election']);

    $query = "
        SELECT * FROM election 
        WHERE eid = ? 
        AND session = ? 
        OR session = ? 
        LIMIT 1
    ";
    $statement = $conn->prepare($query);
    $statement->execute([$election_id, 1, 2]);
    $report_result = $statement->fetchAll();
    $count_report = $statement->rowCount();

    foreach ($report_result as $report_row) {}

    if ($count_report > 0) {
?>
<main role="main" class="col-md-12 col-lg-12 px-4" style="background-color: rgb(70, 60, 54);">
<style type="text/css">
    .dropdown-menu.show {
        padding: 0!important;
        background-color: #5f554d;
    }
</style>
    <ul class="nav justify-content-end p-3">
        <li class="nav-item">
             <a href="reports.voted.php?report=<?= $election_id; ?>" class="text-secondary nav-link">Voted Details</a>
        </li>
        <li class="nav-item">
            <a href="reports.voter.php?report=<?= $election_id; ?>" class="text-secondary nav-link">Voter Details</a>
        </li>
        <li class="nav-item">
            <a href="registrar" class="text-secondary nav-link">Voters</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">other</a>
            <div class="dropdown-menu">
                <?php if ($report_row['session'] == 1): ?>
                    <a href="javascript:;" class="nav-link end-election text-danger" id="<?= $report_row["eid"]; ?>">End Election</a>
                    <hr>                
                <?php endif; ?>
                <a href="reports?report=1&election=<?= $election_id; ?>" class="text-secondary nav-link">refresh!</a>
                <a href="index" class="nav-link text-secondary">go back!</a>
            </div>
        </li>
    </ul>

    <span id="temporary"></span>    
    <span id="graph-result"></span>
    <canvas id="myChart" width="400" height="160"></canvas>
    <span id="display_candidate_and_result"></span>


<?php
    } else {
        $_SESSION['error_flash'] = 'Election was not found!';
        echo '<script>window.location = "index";</script>';
    }
} else {
    $_SESSION['error_flash'] = 'There was an error, please try again later.';
    echo '<script>window.location = "index";</script>';
}

$electionStarted = '';

// CLEAR STARTED ELECTION
if (isset($_GET['eclear']) && !empty($_GET['eclear'])) {
    $query = "SELECT * FROM election WHERE session = :session LIMIT 1";
    $statement = $conn->prepare($query);
    $statement->execute([':session' => 2]);
    $clear_started_election_result = $statement->fetchAll();
    $count_election_clear_started_election = $statement->rowCount();
    if ($count_election_clear_started_election > 0) {
        foreach ($clear_started_election_result as $clear_started_election_row) {}
        
        $queryStop = "UPDATE election SET session = ?, stop_timer = ? WHERE eid = ?";
        $statement = $conn->prepare($queryStop);
        $result = $statement->execute([0, '', $clear_started_election_row['eid']]);
        if (isset($result)) {
            $_SESSION['flash_success'] = 'Election Has Been Stopped Successfully';
            echo "<script>window.location = 'index';</script>";
        }
    }
}

?>

<style type="text/css">
.start-0 {
    left: 0!important;
}

.top-0 {
    top: 6rem !important;
}

.toast-container {
    position: absolute;
}
</style>

<div class="toast-container p-3 top-0 start-0" id="toastPlacement" data-original-class="toast-container p-3"></div>

<?php
    include ('includes/main-footer.inc.php');
    include ('includes/footer.inc.php');
?>
<script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/Chart.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script> -->


<script type="text/javascript">
    feather.replace();
    $(document).ready(function() {
        $('.end-election').on('click', function() {
            var election_id = $(this).attr('id');
            if (confirm("ELection will End")) {
                $.ajax({
                    url : "controller/control.end.election.php",
                    method : "POST",
                    data : {
                        election_id : election_id
                    },
                    success : function(data) {
                        window.location = 'https://puubu.namibra.io/172.06.84.0/reports?report=1&election='+election_id
                        //$('#temporary').html('<span>'+data+'</span>');
                    }
                });
            } else {
                return false;
            }
        });

        //
        function get_all_results_per_candidate() {
            var election_id = "<?= $election_id; ?>";
            var election_name =  "<?= $report_row['election_name']; ?>"
            var election_session =  "<?= $report_row['session']; ?>"

            $.ajax({
                url : "controller/control.result.of.candidate.php",
                method: "POST",
                data : {
                    election_id : election_id,
                    election_name : election_name,
                    election_session : election_session
                },
                success : function(data) {
                    $('#display_candidate_and_result').html(data);
                }
            });
        }
        get_all_results_per_candidate()

        //
        function get_all_results_per_candidate_ingraph() {
            var election_id = "<?= $election_id; ?>";
            var election_name =  "<?= $report_row['election_name']; ?>"
            var election_session =  "<?= $report_row['session']; ?>"

            $.ajax({
                url : "controller/control.result.of.candidate.ingraph.php",
                method: "POST",
                data : {
                    election_id : election_id,
                    election_name : election_name,
                    election_session : election_session
                },
                success : function(data) {
                    $('#graph-result').html(data);
                }
            });
        }
        get_all_results_per_candidate_ingraph()

        setInterval(function() {
            get_all_results_per_candidate();
            get_all_results_per_candidate_ingraph();
            toast_for_voted_voters();
        }, 3000)

        // Toast for just voted voters
        function toast_for_voted_voters() {
            var toast = "toast"

            $.ajax({
                url : 'controller/control.toast.for.voters.php',
                method : "POST",
                data : {
                    toast : toast
                },
                success : function(data) {
                    $('#toastPlacement').html(data);
                    var voterhasdone_id = $('#vhd_id').val();
                    $("#liveToast").toast({ 
                        delay: 3000
                    }, update_voter_toast(voterhasdone_id));
                    $("#liveToast").toast('show');
                    
                }
            });
        }
        toast_for_voted_voters()

        // UPDATE TOAST STATUS TO SEEN
        function update_voter_toast(voterhasdone_id) {
            $.ajax({
                url : "controller/control.toast.for.voters.update.php",
                method : "POST",
                data: {
                    voterhasdone_id : voterhasdone_id
                },
                success : function(data) {}
            });
        }

    });
</script>