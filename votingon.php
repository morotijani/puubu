<?php

require_once("connection/conn.php");

if (!isset($_SESSION['voter_accessed'])) {
    unset($_SESSION['voter_accessed']);
    header("Location: index");
}
    
if ($voter_count > 0) {
    foreach ($voter_result as $voter_row) {
        
        if ($voter_row['session'] == 0) {
            $voting_on = "Election Organizer(s) has not yet started the election... Please come back later!";
        } elseif ($voter_row['session'] == 1) {
            $voting_on = 'voting under <span class="text-shadow"><u>' . ucwords($voter_row["election_name"]) . '</u></span>';
        } elseif ($voter_row['session'] == 2) {
            header('Location: ended');
        }
    }
} else {
    unset($_SESSION['voter_accessed']);
    header("Location: index");
}
  
// CHeck if voter has voted
$count_checkVoterhasdone = $conn->query("SELECT * FROM voterhasdone WHERE voter_id = '".$voterId."' AND election_id = '".$voter_row['election_id']."'")->rowCount();
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
    <title><?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?>, Voting On <?= ucwords($voter_row["election_name"]); ?> • Puubu</title>
</head>
<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky navbar-dark">
        <div class="container">
            <a href="index" class="navbar-brand">
                <img src="media/puubu.logo.png" alt="Logo" style="box-shadow: 0.4px 1px 9px currentColor; border-radius: 0.4rem;">
            </a>
          <ul class="navbar-nav navbar-nav-secondary order-lg-3">
                <li class="nav-item">
                    <a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="logout" title="Logout">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="offcanvas-wrap">
        <div data-center-top="@class:inverted bg-color-active;" data-bottom-bottom="@class:inverted bg-color-active;" data-edge-strategy="reset">
            <span class="bg-color bg-black"></span>

            <!-- hero -->
            <section class="overflow-hidden bg-black inverted">
                <div class="d-flex flex-column container py-20 min-vh-100 level-1">
                    <div class="row align-items-center justify-content-center justify-content-lg-end my-auto">
                        <div class="col-md-8 col-lg-5 text-center text-lg-start">
                            <span class="badge bg-opaque-yellow text-yellow rounded-pill">Ciao, <?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?></span>
                            <h1 class="display-5 fw-bold lh-sm my-2 my-xl-4"><?= $voting_on; ?></h1>
                            <span class="lead text-danger" id="countDT"></span>

                            <p class="lead my-4">"We have the power to make a difference. But we need to VOTE.”,</p>
                            <?php if ($count_checkVoterhasdone > 0): ?>
                                <p class="text-danger text-shadow">Ooops... it seems you have already voted... 😂😂😂</p>
                                <a href="logout" class="btn btn-with-icon btn-yellow rounded-pill">say your goodbyes 😋😋😋 !!! <i class="bi bi-arrow-right"></i></a>
                            <?php else: ?>
                                <a href="startvote" class="btn btn-with-icon btn-yellow rounded-pill">Get Started <i class="bi bi-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="container-fluid back back-background">
                    <div class="row h-100">
                        <div class="col-lg-6" data-aos="fade-in">
                            <figure class="background" style="background-image: url('media/profile-cover.jpg')" data-top-top="transform: translateY(0%);" data-top-bottom="transform: translateY(10%);"></figure>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

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
                         document.getElementById('countDT').innerHTML = "<small><?= $voter_row['stop_timer']; ?> Election ended</small>";
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