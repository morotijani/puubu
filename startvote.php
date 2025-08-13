<?php

require_once("connection/conn.php");

if (!isset($_SESSION['voter_accessed'])) {
    unset($_SESSION['voter_accessed']);
    header("Location: index");
}

$out = '';
$sendVotes = '';

if ($voter_count > 0) {
    foreach ($voter_result as $voter_row) {
        
        if ($voter_row['session'] == 0) {
            $voting_on = "ELection Organizer(s) has not yet started the election... Please come back later!";
        } elseif ($voter_row['session'] == 1) {
            $voting_on = ucwords($voter_row["election_name"]) . ' ~ <span class="text-muted">' . ucwords($voter_row['election_by']) . '</span>';
        } elseif ($voter_row['session'] == 2) {
            header('Location: ended');
        }

        $electionId = $voter_row['eid'];
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
                for ($num = 0; $num < $position_count; $num++) {

                    $contestantQuery = "
                        SELECT * FROM cont_details 
                        WHERE cont_position = ?
                        AND election_name = ? 
                        AND del_cont = ?
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
                                    <div class="col-md-3"> 
                                        <div class="product">
                                            <figure class="product-image">
                                                <a href="javascript:;" class="avatar">
                                                    <img src="'.PROOT.'media/uploadedprofile/'.$row["cont_profile"].'" alt="Image" class="rounded" style="max-height: 100%; height: 56px; object-fit: cover;">
                                                </a>
                                            </figure>
                                            <a class="product-title fs-sm" href="javascript:;">
                                                '.strtoupper($row["cont_indentification"]).'
                                            </a>
                                            <span class="product-price text-muted">
                                                '.ucwords($row['cont_fname'] .' '. $row['cont_lname']).'
                                            </span>
            
                            ';
                            foreach ($sql8_result as $row8) {
                                if ($row8['count_pc'] > 1) {
                                    $out .= '
                                        <input type="radio" id="'.$row["cont_id"].'" name="contestant'.$num.'" value="'.$row["cont_id"].'" class="form-check-input">
                                        <label for="'.$row["cont_id"].'"></label>
                                    ';
                                } else {
                                    $out .= '<input type="radio" name="onecont'.$num.'" id="'.$row["cont_id"].'" value="yes,'.$row["cont_id"].'" class="form-check-input"> <label for="'.$row["cont_id"].'" class="text-success fs-sm">Yes &nbsp;</label>';
                                    $out .= '<input type="radio" name="onecont'.$num.'" id="'.$row["cont_id"].'" value="no,'.$row["cont_id"].'" class="form-check-input"> <label for="'.$row["cont_id"].'" class="text-danger fs-sm">No</label>';
                                }
                            }
                            $out .= '
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
                            <div class='row'>
                                <div class='col-12'>
                                    <button type='submit' name='submitVotes' id='submitVotes' class='btn btn-warning btn-sm shadow'>Send all votes.</button>
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
    header("Location: signin");
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
    <title><?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?>, Start Vote • Puubu</title></head>

<style type="text/css">
    body {
        background-color: #f1f1f1;
    }
</style>
<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky p-1 navbar-dark">
        <div class="container">
            <a href="votingon" class="navbar-brand">
                <img src="media/puubu.logo.png" alt="Logo">
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
        <section class="cover overflow-hidden inverted" style="background-color: #3A2302;">
            <div class="d-flex flex-column min-vh-50 py-5 container foreground mt-5">
                <div class="row justify-content-center my-auto">
                    <div class="col-lg-8 col-xl-6">
                        <span class="eyebrow text-secondary text-shadow"><?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?></span>
                        <h1 class="display-5 mb-1 text-shadow"><u><?= $voting_on; ?></u></h1>
                        <h1 class="display-4 mb-1 text-shadow" id="countDT"></h1>
                        <div class="text-secondary fs-3">
                            <span data-typed='{"strings": ["your vote start now.", "Every vote counts."]}'></span>
                        </div>
                        <a href="logout" class="btn btn-outline-white btn-sm">.vote later.</a>
                    </div>
                </div>
            </div>

            <figure class="background background-dimm" style="background-image: url('media/elections-990.jpg')" data-top-top="transform: translateY(0%);" data-top-bottom="transform: translateY(20%);"></figure>
            <span class="scroll-down"></span>
        </section>

        <!-- product carousel -->
        <section class="overflow-hidden pt-3 pt-xl-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <?= $out; ?>
                        <?= $sendVotes; ?>
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
                    <h4>Puubu Inc. <br>Ghana</h4>
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

                        var timerStoper = 'timer';
                        var election = <?= $voter_row['eid']; ?>;
                        document.getElementById('countDT').innerHTML = "<small><?= $voter_row['stop_timer']; ?> election ended</small>";
                        $.ajax ({
                            url : 'controller/control.election.timer.php',
                            method : 'POST',
                            data : {
                                timerStoper : timerStoper,
                                election : election
                            },
                            success : function(data) {
                                window.location = 'ended';
                            }
                        });
                    }
                }, 1000);
            <?php endif; ?>
        });
    </script>
</body>
</html>