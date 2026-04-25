<?php

require_once("connection/conn.php");

if (!isset($_SESSION['voter_accessed'])) {
    unset($_SESSION['voter_accessed']);
    redirect(PROOT);
}

$out = '';
$sendVotes = '';

if ($voter_count > 0) {
    foreach ($voter_result as $voter_row) {
        
        if ($voter_row['session'] == 0) {
            $voting_on = "Election Organizer(s) has not yet started the election... Please come back later!";
        } elseif ($voter_row['session'] == 1) {
            $voting_on = ucwords($voter_row["election_name"]) . ' ~ <span class="text-muted">' . ucwords($voter_row['election_by']) . '</span>';
        } elseif ($voter_row['session'] == 2) {
            redirect(PROOT . 'ended');
        }

        $electionId = $voter_row['election_id'];
        $positionQuery = "
            SELECT * FROM positions 
            WHERE election_id = ?
        ";
        $statement = $conn->prepare($positionQuery);
        $position_execute = $statement->execute([$electionId]);
        $position_result = $statement->fetchAll();
        $position_count = $statement->rowCount();

        if (!isset($position_execute)) {
            $out .= "<p>There was an error. Please try again</p>";
            $out .= "<a class='btn btn-info' href='startvote'>Okay ...</a>";
        } else {

            if ($position_count > 0) {
                foreach ($position_result as $eachPosition) {
                    $positions[] = $eachPosition['position_id']; 
                }

                $out .= "<form action='controller/control.add.vote.count.php' method='POST'>";
                $out .= csrf_field();
                for ($num = 0; $num < $position_count; $num++) {

                    $contestantQuery = "
                        SELECT * FROM cont_details 
                        WHERE cont_position = ?
                        AND contestant_election = ? 
                        AND del_cont = ? 
                        ORDER BY contestant_ballot_number ASC
                    ";
                    $statement = $conn->prepare($contestantQuery);
                    $statement->execute([$positions[$num], $electionId, 'no']);
                    $contestant_result = $statement->fetchAll();
                    $contestant_row_count = $statement->rowCount();
                    if ($contestant_row_count < 1) {

                        foreach ($position_result as $position_row) {
                            $out .= '
                                <div class="row align-items-end mb-2">
                                    <div class="col-lg-8 mb-2 mb-lg-0">
                                        <h2 class="fw-bold">'.ucwords($position_row['position_name']).'</h2>';
                                        $out .= "<input type='hidden' name='name-of-positions".$num."'>";
                                        $out .= '<input type="hidden" name="contestant'.$num.'">';
                                        $out .= '<input type="hidden" name="onecont'.$num.'">';
                                        $out .= '
                                        </div>
                                        <div class="col-lg-4 text-lg-end"></div>
                                    </div>

                                    <div class="row justify-content-between">
                                        <div class="col mb-2">
                                            <ul class="list-unstyled">
                                                <li class="mt-1">
                                                    <a href="javascript:;" class="card bg-light card-hover-border">
                                                        <div class="card-body py-4">
                                                            <div class="row align-items-center g-2 g-md-4 text-center text-md-start">
                                                                <div class="col-md-9">
                                                                    <p class="fs-lg mb-0">There are no contestant(s) for the position</p>
                                                                    <ul class="list-inline list-inline-separated text-muted">
                                                                        <li class="list-inline-item">'.$electionStarted.'</li>
                                                                        <li class="list-inline-item">'.$electionBy.'</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-3 text-lg-end">
                                                                    <span>'.ucwords($position_row['position_name']).'</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                            ';
                        }

                    } else {

                        $sql1 = "
                            SELECT * FROM positions 
                            WHERE position_id = ?
                        ";
                        $statement = $conn->prepare($sql1);
                        $statement->execute([$positions[$num]]);
                        $resu = $statement->fetchAll();
                        $out .= '
                            <div class="accordion accordion-classic" id="accordion-1">
                                <div class="accordion-item">
                        ';
                        foreach ($resu as $key) {
                            $out .= '
                                    <div class="accordion-header" id="heading-1-'.$key["position_id"].'">
                                        <div class="accordion-button collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-1-'.$key["position_id"].'" aria-expanded="false" aria-controls="collapse-1-'.$key["position_id"].'">
                                            <div class="d-flex flex-wrap align-items-center w-100">
                                                <div class="col-3 col-md-2 text-secondary fs-lg">00</div>
                                                <div class="col-9 col-md-7 fs-lg">'.ucwords($key['position_name']).'</div>
                                                <div class="d-none d-md-block col-md-3 text-md-end pt-1 pt-md-0">
                                                    <ul class="avatar-list">
                                                        <li>
                                                            <span class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="'.ucwords($key['position_name']).'"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapse-1-'.$key["position_id"].'" class="accordion-collapse collapse" aria-labelledby="heading-1-'.$key["position_id"].'" data-bs-parent="#accordion-1" style="">
                                        <div class="accordion-body">
                                            <div class="d-flex justify-content-end">
                                                <div class="col-md-10">                
                                                    <div class="row">          
                            ';
                            $out .= "<input type='hidden' name='name-of-positions".$num."' value='".$positions[$num]."'>";
                        }


                        foreach ($contestant_result as $row) {
                            $sql8 = "
                                SELECT COUNT(*) count_pc 
                                FROM cont_details 
                                WHERE cont_position = :cont_position 
                                AND del_cont = :del_cont
                            ";
                            $statement = $conn->prepare($sql8);
                            $statement->execute(
                                [
                                    ':cont_position' => $row['cont_position'],
                                    ':del_cont' => 'no'
                                ]
                            );
                            $sql8_count = $statement->rowCount();
                            $sql8_result = $statement->fetchAll();

                            $out .= '               
                                    <div class="col-md-4 col-lg-3 mb-4"> 
                                        <div class="candidate-card glass-card p-3 text-center h-100 position-relative">
                                            <div class="check-badge"><i class="bi bi-check"></i></div>
                                            <figure class="product-image mb-3">
                                                <div class="avatar avatar-xl mx-auto overflow-hidden rounded-circle" style="width: 80px; height: 80px; border: 2px solid var(--glass-border);">
                                                    <img src="'.PROOT.'media/uploadedprofile/'.$row["cont_profile"].'" alt="Image" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            </figure>
                                            <h5 class="product-title text-white mb-1">
                                                '.ucwords($row['cont_fname'] .' '. $row['cont_lname']).'
                                            </h5>
                                            <span class="badge bg-light text-dark mb-3">
                                                Ballot #'.strtoupper($row["contestant_ballot_number"]).'
                                            </span>
                                            <div>
            
                            ';
                            foreach ($sql8_result as $row8) {
                                if ($row8['count_pc'] > 1) {
                                    $out .= '
                                        <input type="radio" id="'.$row["contestant_id"].'" name="contestant'.$num.'" value="'.$row["contestant_id"].'" class="form-check-input visually-hidden candidate-radio">
                                        <label for="'.$row["contestant_id"].'" class="btn btn-outline-light btn-sm rounded-pill w-100 stretched-link">Select</label>
                                    ';
                                } else {
                                    $out .= '<div class="d-flex justify-content-center gap-2">';
                                    $out .= '<input type="radio" name="onecont'.$num.'" id="'.$row["contestant_id"].'_yes" value="yes,'.$row["contestant_id"].'" class="form-check-input visually-hidden candidate-radio"> <label for="'.$row["contestant_id"].'_yes" class="btn btn-outline-success btn-sm rounded-pill">Yes</label>';
                                    $out .= '<input type="radio" name="onecont'.$num.'" id="'.$row["contestant_id"].'_no" value="no,'.$row["contestant_id"].'" class="form-check-input visually-hidden"> <label for="'.$row["contestant_id"].'_no" class="btn btn-outline-danger btn-sm rounded-pill">No</label>';
                                    $out .= '</div>';
                                }
                            }
                            $out .= '
                                            </div>
                                        </div>
                                    </div>
                            ';

                        }
                        $out .= '                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
                $sendVotes = "
                            <input type='hidden' name='number-of-positions' value='".$position_count."'>
                            <input type='hidden' name='name-of-election' value='".$electionId."'>
                                <div class='col-12 text-center mt-3'>
                                    <button type='submit' name='submitVotes' id='submitVotes' class='btn btn-puubu btn-lg rounded-pill pulse-button px-5 shadow-lg'><i class='bi bi-check2-circle me-2'></i> Submit Ballot</button>
                                </div>
                            </div>
                        </div>
                    </form>
                ";

            } else {
                $out .= "
                    <div class='card'>
                        <h4 class='card-header text-center font-weight-lighter'>Oops... No Position(s) Under This Election</h4>
                        <p class='lead text-center'> Alright ... <a href='logout' class='text-warning'>am out</a> for the mean time</p>
                    </div>
                ";
            }
        }
    }
} else {
    unset($_SESSION['voter_accessed']);
    redirect(PROOT . 'signin');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="media/puubu.favicon.png" type="image/x-icon" />
  
    <!-- Libs CSS -->
    <link rel="stylesheet" href="dist/css/libs.bundle.css" />
  
    <!-- Main CSS -->
    <link rel="stylesheet" href="dist/css/index.bundle.css" />
    <link rel="stylesheet" href="dist/css/puubu.css" />
  
    <!-- Title -->
    <title><?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?>, Start Vote • Puubu</title>
</head>
<body style="background: var(--bg-dark); color: var(--text-primary);">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky p-2 navbar-dark" style="background: var(--glass-bg); backdrop-filter: blur(16px); border-bottom: 1px solid var(--glass-border);">
        <div class="container">
            <a href="votingon" class="navbar-brand">
                <h3 class="mb-0 text-white brand-text">Puu<span class="text-color">Bu</span></h3>
            </a>
  
            <ul class="navbar-nav navbar-nav-secondary order-lg-3">
                <li class="nav-item">
                    <a class="nav-link nav-icon text-light" data-bs-toggle="offcanvas" href="logout">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="offcanvas-wrap">

        <!-- header -->
        <section class="cover overflow-hidden" style="background: var(--bg-darker); position: relative;">
            <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 50% 50%, rgba(255, 159, 28, 0.1), transparent 50%); animation: pulseGlow 15s infinite alternate; z-index: 0;"></div>
            <div class="d-flex flex-column min-vh-50 py-5 container mt-5" style="position: relative; z-index: 1;">
                <div class="row justify-content-center my-auto">
                    <div class="col-lg-8 col-xl-7 text-center">
                        <span class="badge bg-opaque-white text-white rounded-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                            Voter: <?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?>
                        </span>
                        <h1 class="display-4 fw-bold mb-3 text-white"><?= $voting_on; ?></h1>
                        <h2 class="display-6 mb-4 text-color" id="countDT"><i class="bi bi-clock-history"></i></h2>
                        <div class="text-secondary fs-4 mb-4">
                            <span data-typed='{"strings": ["Your vote starts now.", "Every vote counts."]}'></span>
                        </div>
                        <a href="logout" class="btn btn-outline-light rounded-pill px-4">Save & Vote Later</a>
                    </div>
                </div>
            </div>
            <span class="scroll-down"></span>
        </section>

        <!-- product carousel -->
        <section class="overflow-hidden pt-3 pt-xl-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="glass-card p-4 p-md-5 mb-5">
                            <?= $out; ?>
                            
                            <div class="mt-4 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                                <?= $sendVotes; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br>
    </div>



    <!-- footer -->
    <footer class="py-5 py-xl-20 border-top">
        <div class="container">
            <div class="row g-2 g-lg-6 mb-1">
                <div class="col-lg-6">
                    <h4>Puubu Group. <br>Ghana</h4>
                    <p class="small">Copyrights &copy; 2021</p>
                </div>    
                <div class="col-lg-6 text-lg-end">
                    <span class="h5">+233 240 445 410</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- javascript -->
    <script type="text/javascript" src="172.06.84.0/media/files/jquery-3.3.1.min.js"></script>
    <script src="dist/js/vendor.bundle.js"></script>
    <script src="dist/js/index.bundle.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // TIMER STOPER
            <?php if ($started_election > 0): ?>
                var countDownDate = new Date("<?= $voter_row['stop_timer']; ?>").getTime();
                // UPDATE THE COUNT DOWN EVERY 1 SECOND
                var x = setInterval(function() {
                    // GET TODAY'S DATE AND TIME
                    var now = new Date().getTime();
                    // FIND THE DISTANCE BETWEEN NOW AND THE COUNT DOWN DATE
                    var distance = countDownDate - now;
                    // TIME CALCULATIONS FOR DAYS, HOURS, MINUTES, AND SECONDS
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // DISPLAY THE RESULT IN THE ELEMENT WITH ID = "TIMER"
                    document.getElementById('countDT').innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s <small>to end the election</small>";

                    // IF COUNT DOWN FINISHES
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById('countDT').innerHTML = "<small><?= $voter_row['stop_timer']; ?> election ended</small>";
                        window.location = 'ended';
                    }
                }, 1000);
            <?php endif; ?>

            // Handle Candidate Selection Highlighting
            $('.candidate-radio').on('change', function() {
                var nameGroup = $(this).attr('name');
                // Remove selected class from all cards in this position group
                $('input[name="' + nameGroup + '"]').each(function() {
                    $(this).closest('.candidate-card').removeClass('selected');
                });
                // Add selected class to the chosen card
                if($(this).is(':checked')) {
                    $(this).closest('.candidate-card').addClass('selected');
                }
            });
        });
    </script>
</body>
</html>