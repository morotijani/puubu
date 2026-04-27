<?php
namespace App\Controllers;

class VoterController {
    protected $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function home() {
        echo $this->twig->render('voter/home.twig');
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
                        ON election.uuid = registrars.election_uuid
                        WHERE std_id = ?
                    ";
                    $statement = $conn->prepare($query);
                    $statement->execute([$_POST['voter_id']]);
                    $result_voterLogin = $statement->fetchAll();

                    if ($statement->rowCount() > 0) {
                        foreach ($result_voterLogin as $row) {
                            if ($row['status'] == 1) {
                                $now = date('Y-m-d H:i:s');
                                if ($now < $row['starts_at']) {
                                    $displayErrors = "This election has not started yet. Access will be granted on " . date('F j, Y, g:i a', strtotime($row['starts_at'])) . ".";
                                } elseif ($now > $row['ends_at']) {
                                    $displayErrors = "Access Denied: This election ended on " . date('F j, Y, g:i a', strtotime($row['ends_at'])) . ".";
                                } else {
                                    // Check if already voted
                                    $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?");
                                    $checkVoted->execute([$row['voter_id'], $row['uuid']]);
                                    if ($checkVoted->fetchColumn() > 0) {
                                        $displayErrors = "Access Denied: You have already cast your vote in this election.";
                                    } elseif (!password_verify($_POST['voter_password'], $row['std_password'])) {
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
        global $conn, $voter_result, $started_election, $has_voted;
        
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];

        echo $this->twig->render('voter/dashboard.twig', [
            'voter' => $voter_row,
            'started_election' => $started_election,
            'has_voted' => $has_voted
        ]);
    }

    public function ballot() {
        global $conn, $voter_result, $started_election;
        
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];
        $now = date('Y-m-d H:i:s');
        
        // Block if already voted
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter_row['voter_id'], $voter_row['uuid']]);
        if ($checkVoted->fetchColumn() > 0) {
            redirect(PROOT . 'votingon');
        }

        if ($voter_row['status'] == 2 || $now > $voter_row['ends_at']) {
            redirect(PROOT . 'votingon');
        }

        $electionUuid = $voter_row['uuid'];
        
        // Fetch Positions
        $posQuery = "SELECT * FROM positions WHERE election_uuid = ?";
        $stmt = $conn->prepare($posQuery);
        $stmt->execute([$electionUuid]);
        $positions = $stmt->fetchAll();

        $ballotData = [];
        foreach ($positions as $pos) {
            // Fetch Contestants for this position
            $contQuery = "
                SELECT * FROM cont_details 
                WHERE cont_position = ? 
                AND election_uuid = ? 
                AND del_cont = 'no' 
                ORDER BY contestant_ballot_number ASC
            ";
            $stmt = $conn->prepare($contQuery);
            $stmt->execute([$pos['position_id'], $electionUuid]);
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
            'electionUuid' => $electionUuid
        ]);
    }

    public function submitVote() {
        global $conn, $voter_result;
        
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            die("Invalid CSRF token.");
        }

        $voter_row = $voter_result[0];
        $voter_id = $voter_row['voter_id'];
        $election_uuid = sanitize($_POST['name-of-election'] ?? '');
        
        $now = date('Y-m-d H:i:s');
        if ($voter_row['status'] != 1 || $election_uuid != $voter_row['uuid']) {
            die("Election is not active or invalid.");
        }

        if ($now < $voter_row['starts_at']) {
            die("Unauthorized: The voting session has not started yet.");
        }
        if ($now > $voter_row['ends_at']) {
            die("Unauthorized: The voting session has ended.");
        }

        // CRITICAL: Final double-check before recording votes
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter_id, $election_uuid]);
        if ($checkVoted->fetchColumn() > 0) {
            die("Critical Error: Your vote has already been recorded for this election.");
        }

        $num_positions = intval($_POST['number-of-positions'] ?? 0);

        try {
            $conn->beginTransaction();

            for ($i = 0; $i < $num_positions; $i++) {
                $position_id = sanitize($_POST["name-of-positions{$i}"] ?? '');
                
                if (isset($_POST["contestant{$i}"]) && !empty($_POST["contestant{$i}"])) {
                    $contestant_id = sanitize($_POST["contestant{$i}"]);
                    // Increment results
                    $stmt = $conn->prepare("UPDATE vote_counts SET results = results + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                    $stmt->execute([$contestant_id, $position_id, $election_uuid]);
                } elseif (isset($_POST["onecont{$i}"]) && !empty($_POST["onecont{$i}"])) {
                    $val = explode(',', $_POST["onecont{$i}"]);
                    if (count($val) == 2) {
                        $choice = sanitize($val[0]);
                        $contestant_id = sanitize($val[1]);
                        if ($choice === 'yes') {
                            $stmt = $conn->prepare("UPDATE vote_counts SET results = results + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                            $stmt->execute([$contestant_id, $position_id, $election_uuid]);
                        } else {
                            $stmt = $conn->prepare("UPDATE vote_counts SET results_no = results_no + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                            $stmt->execute([$contestant_id, $position_id, $election_uuid]);
                        }
                    }
                } else {
                    // Skipped
                    $stmt = $conn->prepare("UPDATE positions SET position_skipped_votes = position_skipped_votes + 1 WHERE position_id = ? AND election_uuid = ?");
                    $stmt->execute([$position_id, $election_uuid]);
                }
            }

            // Mark voter as done
            $vhd_id = guidv4();
            $stmt = $conn->prepare("INSERT INTO voterhasdone (vhd_id, voter_id, election_uuid, voterhasdone_status) VALUES (?, ?, ?, 1)");
            $stmt->execute([$vhd_id, $voter_id, $election_uuid]);

            // BUILD EMAIL RECEIPT
            $receipt_html = "<h3>Voting Receipt: {$voter_row['election_name']}</h3>";
            $receipt_html .= "<p>Hello " . ucwords($voter_row['std_fname']) . ", your ballot has been successfully cast in the <strong>{$voter_row['election_name']}</strong> organized by <strong>{$voter_row['election_by']}</strong>.</p>";
            $receipt_html .= "<p>Here is a summary of your selections for your reference:</p>";
            $receipt_html .= "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; font-family: sans-serif;'>";
            $receipt_html .= "<tr style='background: #f1f5f9;'><th>Position</th><th>Selection</th></tr>";

            for ($i = 0; $i < $num_positions; $i++) {
                $pos_id = sanitize($_POST["name-of-positions{$i}"] ?? '');
                
                // Get position name
                $pStmt = $conn->prepare("SELECT position_name FROM positions WHERE position_id = ?");
                $pStmt->execute([$pos_id]);
                $pName = $pStmt->fetchColumn();

                $selection = "Abstained / No selection";
                
                if (isset($_POST["contestant{$i}"]) && !empty($_POST["contestant{$i}"])) {
                    $c_id = sanitize($_POST["contestant{$i}"]);
                    $cStmt = $conn->prepare("SELECT cont_fname, cont_lname FROM cont_details WHERE contestant_id = ?");
                    $cStmt->execute([$c_id]);
                    $cRow = $cStmt->fetch();
                    $selection = $cRow['cont_fname'] . " " . $cRow['cont_lname'];
                } elseif (isset($_POST["onecont{$i}"]) && !empty($_POST["onecont{$i}"])) {
                    $val = explode(',', $_POST["onecont{$i}"]);
                    $choice = $val[0] ?? '';
                    $c_id = $val[1] ?? '';
                    $cStmt = $conn->prepare("SELECT cont_fname, cont_lname FROM cont_details WHERE contestant_id = ?");
                    $cStmt->execute([$c_id]);
                    $cRow = $cStmt->fetch();
                    $selection = ($choice === 'yes' ? "Approve: " : "Reject: ") . $cRow['cont_fname'] . " " . $cRow['cont_lname'];
                }

                $receipt_html .= "<tr><td>{$pName}</td><td>{$selection}</td></tr>";
            }
            $receipt_html .= "</table><p>Thank you for participating!</p>";

            send_email($voter_row['std_email'], "Voting Receipt: " . $voter_row['election_name'], $receipt_html);

            add_to_log("Voter casted vote and received email receipt", $voter_id, 'user');

            $conn->commit();

            // Do NOT destroy session here, redirect to success page
            redirect(PROOT . 'success');

        } catch (\Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            die("An error occurred while saving your vote. Please contact administration. " . $e->getMessage());
        }
    }

    public function success() {
        global $voter_result;
        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }
        $voter_row = $voter_result[0];
        echo $this->twig->render('voter/success.twig', [
            'voter' => $voter_row
        ]);
    }
}

