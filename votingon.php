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
                    <a class="nav-link nav-icon" href="<?= PROOT; ?>logout" title="Logout">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="offcanvas-wrap">
        <section class="overflow-hidden" style="background: var(--bg-dark); position: relative; min-height: 100vh;">
            <!-- Animated Background Gradient -->
            <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 50% 50%, rgba(255, 159, 28, 0.15), transparent 50%); animation: pulseGlow 10s infinite alternate; z-index: 0;"></div>

            <div class="container d-flex flex-column py-20 min-vh-100" style="position: relative; z-index: 1;">
                <div class="row align-items-center justify-content-center my-auto">
                    <div class="col-md-10 col-lg-8 col-xl-7 text-center" data-aos="fade-up">
                        
                        <div class="glass-card p-5 p-md-6">
                            <span class="badge bg-opaque-white text-white rounded-pill px-3 py-2 mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                                👋 Welcome, <?= ucwords($voter_row['std_fname'].' '. $voter_row['std_lname']); ?>
                            </span>
                            
                            <h1 class="display-5 fw-bold lh-sm my-2 mb-4 text-white"><?= $voting_on; ?></h1>
                            
                            <div class="d-inline-block px-4 py-2 rounded-pill mb-4" style="background: rgba(255, 159, 28, 0.1); border: 1px solid rgba(255, 159, 28, 0.3);">
                                <span class="h5 mb-0 text-color fw-bold" id="countDT"><i class="bi bi-clock-history me-2"></i>Loading timer...</span>
                            </div>

                            <p class="lead my-4 text-secondary">"We have the power to make a difference. But we need to VOTE."</p>

                            <div class="mt-5">
                                <?php if ($count_checkVoterhasdone > 0): ?>
                                    <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); color: #28a745;">
                                        <i class="bi bi-check-circle-fill me-2"></i> You have successfully cast your vote!
                                    </div>
                                    <a href="logout" class="btn btn-puubu btn-lg rounded-pill pulse-button mt-3">
                                        Log Out <i class="bi bi-box-arrow-right ms-2"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="startvote" class="btn btn-puubu btn-lg rounded-pill pulse-button px-5">
                                        Proceed to Ballot <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
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
                        document.getElementById('countDT').innerHTML = "<small><?= $voter_row['stop_timer']; ?> Election ended</small>";
                        window.location = 'ended';
                    }
                }, 1000);
            <?php endif; ?>
        });
    </script>

</body>
</html>