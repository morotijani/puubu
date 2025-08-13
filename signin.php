<?php

    require_once("connection/conn.php");

    if (isset($_SESSION['voter_accessed'])) {
        header("Location: startvote");
    }
    require (BASEURL . "172.06.84.0/PHPMailer/PHPMailerAutoload.php");
    $login_issue_text = "Problem loggin in to cast my vote. Assist me ASAP!";
    $login_issue = urlencode($login_issue_text);

    $displayErrors = '';

    if (isset($_POST['submitVoter'])) {
        if (empty($_POST['voter_id']) || empty($_POST['voter_password'])) {
            $displayErrors =  "Invalid Details";
        } else {
            if (isset($_SESSION['crAdmin'])) {
                $displayErrors =  "Oops... admin is working on some background checks please come back later ...";
            } else {
                $query = "
                    SELECT * FROM registrars 
                    INNER JOIN election
                    ON election.eid = registrars.election_type
                    WHERE std_id = ?
                ";
                $statement = $conn->prepare($query);
                $statement->execute([$_POST['voter_id']]);
                $result_voterLogin = $statement->fetchAll();

                if ($statement->rowCount() > 0) {
                    foreach ($result_voterLogin as $row) {
                        if ($row['session'] == 1) {
                            if ($row['std_password'] != $_POST['voter_password']) {
                                $displayErrors =  "Invalid Voter Details";
                            } else {
                                $login_issue_text = "Someone logged in with my account, on this IP: " . $details->ip;
                                $login_issue = urlencode($login_issue_text);

                                $to   = $row["std_email"];
                                $from = 'info@puubu.namibra.io';
                                $from_name = 'Puubu, Inc';
                                $subject = 'New login on Puubu.';
                                $body = '
                                <center>
                                    <p>We\'ve noticed a new login, '.ucwords($row["std_fname"]).',</p>
                                    <p>We\'ve noticed a login from a device that you don\'t usually use from this location; '.$details->country.'.</p>
                                    <p>If this was you, you can safely disregard this email. If this wasn\'t you, you can secure your account <a href="https://wa.me/+233240445410/?text=' . $login_issue . '" target="_blank" class="text-color">here..</a></p>
                                    <p>From <br>, Puubu Inc.</p>
                                </center>
                                ';

                                $mail = new PHPMailer();
                                try {
                                    $mail->IsSMTP();
                                    $mail->SMTPAuth = true;

                                    $mail->SMTPSecure = 'ssl'; 
                                    $mail->Host = 'smtp.namibra.io';
                                    $mail->Port = 465;  
                                    $mail->Username = 'info@puubu.namibra.io';
                                    $mail->Password = 'Ys7eeeeb9'; 

                                    $mail->IsHTML(true);
                                    $mail->WordWrap = 50;
                                    $mail->From = "info@puubu.namibra.io";
                                    $mail->FromName = $from_name;
                                    $mail->Sender = $from;
                                    $mail->AddReplyTo($from, $from_name);
                                    $mail->Subject = $subject;
                                    $mail->Body = $body;
                                    $mail->AddAddress($to);
                                    $mail->Send();
                                

                                    $election_logs_query = "
                                        INSERT INTO voter_login_details (voter_id) 
                                        VALUES (?)
                                    ";
                                    $statement = $conn->prepare($election_logs_query);
                                    $election_logs_result = $statement->execute([$row['id']]);
                                    $just_inserted_election_log_id = $conn->lastinsertId();

                                    if (isset($election_logs_result)) {
                                        $_SESSION['voter_accessed'] = $row['id'];
                                        $_SESSION['voter_login_details_id'] = $just_inserted_election_log_id;
                                        header("Location: votingon");
                                    }


                                } catch (Exception $e) {
                                    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    $displayErrors = "Please check you internet connection or contact Puubu Administrator.";
                                }


                            }
                        } else {
                            $displayErrors = "Sorry, the election you are choosen to vote under is either not started or it has ended.";
                        }
                    }
                } else {
                    $displayErrors =  "Invalid Voter Details";
                }
            }
        }
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
    <title>Sign In • Puubu</title></head>
<body>


    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky navbar-dark">
        <div class="container">
            <a href="index" class="navbar-brand"><img src="media/puubu.favicon.png" alt="Logo"></a>
      
            <ul class="navbar-nav navbar-nav-secondary order-lg-3">
                <li class="nav-item">
                    <a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="javascript:;">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
  

    <section class="overflow-hidden">
        <div class="container d-flex flex-column py-20.5 min-vh-100 level-3">
            <div class="row align-items-center justify-content-center justify-content-lg-between my-auto">
                <div class="col-md-8 col-lg-5 order-lg-2 mb-5 mb-lg-0" data-aos="fade-up">
                    <img src="media/44100.jpg" class="img-fluid" alt="Illustration">
                </div>
                <div class="col-md-10 col-lg-6 col-xl-5 text-center text-lg-start order-lg-1">
                    <small style="color: #FF9800;">Sign in to, </small><h1>PuuBu</h1>
                    <p class="lead text-secondary mb-5">We have the power to make a difference. But we need to VOTE.</p>
                    <div class="row align-items-center g-3">
                        <form action="signin.php" method="POST">
                            <code id="displayErrors"><?= $displayErrors; ?></code>
                            <div class="form-group mb-1">
                                <label for="voter_id" class="form-label">Your ID</label>
                                <input type="text" autofocus autocomplete="off" class="form-control form-control-sm" id="voter_id" name="voter_id" placeholder="Your ID" required>
                            </div>

                            <div class="form-group">
                                <label for="voter_password" class="form-label">Your Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="voter_password" name="voter_password" placeholder="**********" required>
                                    <div class="input-group-append" onclick="controlPassword();" style="cursor: pointer;">
                                        <span class="input-group-text">
                                            <i id="show" class="bi bi-eye"></i>
                                            <i id="hide" class="bi bi-eye-slash" style="display: none;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-auto mt-1">
                                <button type="submit" name="submitVoter" id="submitVoter" class="btn btn-light btn-with-icon rounded-pill btn-sm text-color">Let's Go! <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </form>
                        <div class="col text-md-start mb-1">
                            <p class="text-primary">Can't login contact <span class="fw-bold"><a href="https://wa.me/+233209425285/?text=<?= $login_issue; ?>" target="_blank" class="text-color">Puubu administration</a></span> now.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <figure class="background">
            <svg width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle data-aos="fade-up" data-aos-delay="200" cx="120%" cy="-40%" r="50%" stroke="#dddddd" stroke-opacity="1" data-center-top="@r: 50%;" data-top-bottom="@r: 70%;" />
                <circle data-aos="fade-up" data-aos-delay="400" cx="85%" cy="125%" r="75%" stroke="#dddddd" stroke-opacity="1" data-center-top="@r: 75%;" data-top-bottom="@r: 95%;" />
                <circle data-aos="fade-up" data-aos-delay="600" cx="-25%" cy="125%" r="50%" stroke="#dddddd" stroke-opacity="1" data-center-top="@cx: -25%;" data-top-bottom="@cx: 45%;" />
            </svg>
        </figure>
    </section>

  <!-- javascript -->

    <script type="text/javascript" src="172.06.84.0/media/files/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="dist/js/vendor.bundle.js"></script>
    <script type="text/javascript" src="dist/js/index.bundle.js"></script>

    <script type="text/javascript">

        $("#displayErrors").fadeOut(8000);

        const inputs = document.querySelectorAll(".input");

        function addcl() {
            let parent = this.parentNode.parentNode;
            parent.classList.add("focus");
        }

        function remcl() {
            let parent = this.parentNode.parentNode;
            if (this.value == ""){
                parent.classList.remove("focus");
            }
        }

        inputs.forEach(input => {
            input.addEventListener("focus", addcl);
            input.addEventListener("blur", remcl);
        });

        function controlPassword() {
            var m = document.getElementById('voter_password');
            var n = document.getElementById('hide');
            var o = document.getElementById('show');

            if (m.type === 'password') {
                m.type = "text";
                n.style.display = "block";
                o.style.display = "none";
            } else {
                m.type = "password";
                n.style.display = "none";
                o.style.display = "block";
            }
        }
    </script>

</body>
</html>