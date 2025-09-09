<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
include ('includes/left-nav.inc.php');

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

 <!-- Main -->
    <main class="main px-lg-6">
        <!-- Content -->
        <div class="container-lg">
            <!-- Page header -->
            <div class="row align-items-center mb-7">
                <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl rounded text-primary">
                        <i class="fs-2" data-duoicon="app"></i>
                    </div>
                </div>
                <div class="col">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Reports</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                    <div class="row gx-2">
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-light w-100" href="<?= PROOT; ?>172.06.84.0/reports?report=1&election=<?= $election_id; ?>"><span class="material-symbols-outlined me-1">add</span> Refresh</a>
                        </div>
                        <div class="col-6 col-sm-auto">
                            <a href="<?= goBack(); ?>" class="btn btn-danger w-100">Go back</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="row">
                <div class="col-12">
                    <!-- Filters -->
                    <div class="card card-line bg-body-tertiary border-transparent mb-7">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="text-body-secondary">No customers selected</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg"><div class="row gx-3  ">
                                    <div class="col col-lg-auto ms-auto">
                                        <div class="input-group bg-body">
                                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" />
                                            <span class="input-group-text" id="search">
                                                <span class="material-symbols-outlined">search</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a class="btn btn-dark px-3" href="reports.voted.php?report=<?= $election_id; ?>">
                                            Voted details
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-dark px-3" href="reports.voter.php?report=<?= $election_id; ?>">
                                            Voter details
                                        </a>
                                    </div>

                                    <div class="col-auto ms-n2">
                                        <a class="btn btn-dark px-3" href="<?= PROOT; ?>172.06.84.0/registrar">
                                            Voters
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <div>


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
    include ('includes/footer.inc.php');
?>
<script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/Chart.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script> -->


<script type="text/javascript">

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