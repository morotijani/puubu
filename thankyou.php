<?php 

    require_once("connection/conn.php");

if (!isset($_SESSION['voter_accessed'])) {
    unset($_SESSION['voter_accessed']);
    header("Location: index");
}

if ($voter_count > 0) {
    foreach ($voter_result as $voter_row) {
        
    }
} else {
    unset($_SESSION['voter_accessed']);
    header("Location: index");
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
    <title>Thank you for voting • Puubu</title>
</head>
<body style="background: #d58107;">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky navbar-dark">
        <div class="container">
            <a href="index" class="navbar-brand"><img src="media/puubu.logo.png" alt="Logo"></a>
  
            <ul class="navbar-nav navbar-nav-secondary order-lg-3">
                <li class="nav-item">
                    <a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="logout">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- hero -->
    <section class="inverted">
        <div class="d-flex flex-column container min-vh-100 py-20">
            <div class="row align-items-center justify-content-center justify-content-lg-between my-auto">
                <div class="col-lg-6 order-lg-2">
                    <img class="img-fluid" src="media/review.svg" alt="Figure">
                </div>
                <div class="col-md-8 col-lg-5 order-lg-1 text-center text-lg-start">
                    <h1 class="display-2">Your vote is settled.</h1>
                    <p class="lead">Gracias for your vote! Your vote is being organized, all votes will be completed within <span id="countDT"></span>.</p>
                    <a href="logout" class="btn btn-rounded btn-outline-white rounded-pill">Scooot!</a>
                </div>
            </div>
        </div>
    </section>

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
                    document.getElementById('countDT').innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s";

                    // IF COUNT DOWN FINISHES
                    if (distance < 0) {
                        clearInterval(x);

                        var timerStoper = 'timer';
                        var election = <?= $voter_row['eid']; ?>;
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