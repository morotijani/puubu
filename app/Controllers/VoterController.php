<?php
namespace App\Controllers;

class VoterController {
    protected $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function login() {
        global $conn, $details, $location;
        
        if (isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'votingon');
        }

        $login_issue_text = "Problem loggin in to cast my vote. Assist me ASAP!";
        $login_issue = urlencode($login_issue_text);
        $displayErrors = '';

        if (isset($_POST['submitVoter'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $displayErrors = "Invalid request token. Please refresh and try again.";
            } elseif (empty($_POST['voter_id']) || empty($_POST['voter_password'])) {
                $displayErrors = "Invalid Details";
            } else {
                if (isset($_SESSION['crAdmin'])) {
                    $displayErrors = "Oops... admin is working on some background checks please come back later ...";
                } else {
                    $query = "
                        SELECT * FROM registrars 
                        INNER JOIN election
                        ON election.election_id = registrars.registrar_election
                        WHERE std_id = ?
                    ";
                    $statement = $conn->prepare($query);
                    $statement->execute([$_POST['voter_id']]);
                    $result_voterLogin = $statement->fetchAll();

                    if ($statement->rowCount() > 0) {
                        foreach ($result_voterLogin as $row) {
                            if ($row['session'] == 1) {
                                if (!password_verify($_POST['voter_password'], $row['std_password'])) {
                                    $displayErrors = "Invalid Voter Details";
                                } else {
                                    $login_issue_text = "Someone logged in with my account, on this IP: " . $details->ip;
                                    $login_issue = urlencode($login_issue_text);

                                    $to = $row["std_email"];
                                    $subject = 'New login on Puubu 🦝.';
                                    $body = '<center><p>We\'ve noticed a new login, ' . ucwords($row["std_fname"]) . ',</p><p>We\'ve noticed a login from a device that you don\'t usually use from this location; ' . $details->country . '.</p><p>If this was you, you can safely disregard this email. If this wasn\'t you, you can secure your account <a href="https://wa.me/+233240445410/?text=' . $login_issue . '" target="_blank" class="text-color">here..</a></p><p>From,<br> Puubu Group.</p></center>';

                                    try {
                                        send_email($to, $subject, $body);

                                        $unique_vld_id = guidv4();
                                        $election_logs_query = "
                                            INSERT INTO voter_login_details (voter_login_details_id, voter_id, details_location) 
                                            VALUES (?, ?, ?)
                                        ";
                                        $statement = $conn->prepare($election_logs_query);
                                        $election_logs_result = $statement->execute([$unique_vld_id, $row['voter_id'], $location]);

                                        if ($election_logs_result) {
                                            $_SESSION['voter_accessed'] = $row['voter_id'];
                                            $_SESSION['voter_login_details_id'] = $unique_vld_id;

                                            $log_message = "voter ['" . ucwords($row["std_fname"] . ' ' . $row["std_lname"]) . "'], loggedin, location ('" . $location . "')!";
                                            add_to_log($log_message, $row["voter_id"], 'user');

                                            redirect(PROOT . 'votingon');
                                        }

                                    } catch (\Exception $e) {
                                        $displayErrors = "Please check you internet connection or contact Puubu Administrator.";
                                    }
                                }
                            } else {
                                $displayErrors = "Sorry, the election is either not started or it has ended.";
                            }
                        }
                    } else {
                        $displayErrors = "Invalid Voter Details";
                    }
                }
            }
        }

        echo $this->twig->render('voter/login.twig', [
            'displayErrors' => $displayErrors,
            'login_issue' => $login_issue
        ]);
    }

    public function dashboard() {
        global $conn, $voter_result, $started_election;
        
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];

        echo $this->twig->render('voter/dashboard.twig', [
            'voter' => $voter_row,
            'started_election' => $started_election
        ]);
    }

    public function ballot() {
        global $conn, $voter_result, $started_election;
        
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];
        if ($voter_row['session'] == 2) {
            redirect(PROOT . 'ended');
        }

        $electionId = $voter_row['election_id'];
        
        // Fetch Positions
        $posQuery = "SELECT * FROM positions WHERE election_id = ?";
        $stmt = $conn->prepare($posQuery);
        $stmt->execute([$electionId]);
        $positions = $stmt->fetchAll();

        $ballotData = [];
        foreach ($positions as $pos) {
            // Fetch Contestants for this position
            $contQuery = "
                SELECT * FROM cont_details 
                WHERE cont_position = ? 
                AND contestant_election = ? 
                AND del_cont = 'no' 
                ORDER BY contestant_ballot_number ASC
            ";
            $stmt = $conn->prepare($contQuery);
            $stmt->execute([$pos['position_id'], $electionId]);
            $contestants = $stmt->fetchAll();

            $ballotData[] = [
                'position' => $pos,
                'contestants' => $contestants,
                'count' => count($contestants)
            ];
        }

        echo $this->twig->render('voter/ballot.twig', [
            'voter' => $voter_row,
            'ballot' => $ballotData,
            'started_election' => $started_election,
            'electionId' => $electionId
        ]);
    }
}
