<?php
namespace App\Controllers;

class VoterController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function home()
    {
        echo $this->twig->render('voter/home.twig');
    }

    public function login()
    {
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
                        SELECT v.*, e.title as election_title, e.organized_by, e.starts_at, e.ends_at, e.status as election_status, e.uuid as election_uuid 
                        FROM voters v 
                        INNER JOIN election e
                        ON e.uuid = v.election_uuid
                        WHERE v.voter_id = ?
                    ";
                    $statement = $conn->prepare($query);
                    $statement->execute([$_POST['voter_id']]);
                    $result_voterLogin = $statement->fetchAll();

                    if ($statement->rowCount() > 0) {
                        foreach ($result_voterLogin as $row) {
                            if ($row['election_status'] == 1) {
                                $now = date('Y-m-d H:i:s');
                                if ($now < $row['starts_at']) {
                                    $displayErrors = "This election has not started yet. Access will be granted on " . date('F j, Y, g:i a', strtotime($row['starts_at'])) . ".";
                                } elseif ($now > $row['ends_at']) {
                                    $displayErrors = "Access Denied: This election ended on " . date('F j, Y, g:i a', strtotime($row['ends_at'])) . ".";
                                } else {
                                    // Check if already voted
                                    $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?");
                                    $checkVoted->execute([$row['uuid'], $row['election_uuid']]);
                                    if ($checkVoted->fetchColumn() > 0) {
                                        $displayErrors = "Access Denied: You have already cast your vote in this election.";
                                    } elseif (!password_verify($_POST['voter_password'], $row['password'])) {
                                        $displayErrors = "Invalid Voter Details";
                                    } else {
                                        $current_ip = $details->ip ?? ($_SERVER['REMOTE_ADDR'] ?? 'Unknown IP');
                                        $login_issue_text = "Someone logged in with my account, on this IP: " . $current_ip;
                                        $login_issue = urlencode($login_issue_text);

                                        $to = $row["email"];
                                        $subject = 'Security Alert: New Login on Puubu';

                                        $city = $details->city ?? 'Unknown City';
                                        $region = $details->region ?? 'Unknown Region';
                                        $country = $details->country ?? 'Unknown Country';
                                        $ip = $details->ip ?? 'Unknown IP';
                                        $device_location = "{$city}, {$region}, {$country} ({$ip})";

                                        $body = "
                                        <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;'>
                                            <div style='background: #1d3d37; padding: 24px; text-align: center;'>
                                                <h2 style='color: #dcf3b0; margin: 0;'>New Login Detected</h2>
                                            </div>
                                            <div style='padding: 32px; color: #4a5568; line-height: 1.6;'>
                                                <p>Hello <strong>" . ucwords($row['first_name']) . "</strong>,</p>
                                                <p>We detected a new login to your Puubu account. To ensure your account is secure, we wanted to let you know the details:</p>
                                                
                                                <div style='background: #f8fafc; padding: 16px; border-radius: 8px; margin: 24px 0;'>
                                                    <table style='width: 100%; border-collapse: collapse;'>
                                                        <tr><td style='padding: 4px 0; color: #718096; width: 100px;'>Location:</td><td style='padding: 4px 0; font-weight: 600;'>$device_location</td></tr>
                                                        <tr><td style='padding: 4px 0; color: #718096;'>Time:</td><td style='padding: 4px 0; font-weight: 600;'>" . date('F j, Y, g:i a') . "</td></tr>
                                                    </table>
                                                </div>

                                                <p>If this was you, you can safely ignore this email. If you don't recognize this activity, please secure your account immediately by contacting our support team.</p>
                                                
                                                <div style='text-align: center; margin-top: 32px;'>
                                                    <a href='https://wa.me/+233240445410/?text=$login_issue' style='background: #1d3d37; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;'>Secure My Account</a>
                                                </div>
                                            </div>
                                            <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #a0aec0; border-top: 1px solid #e2e8f0;'>
                                                &copy; " . date('Y') . " Puubu Group. All rights reserved.
                                            </div>
                                        </div>";

                                        try {
                                            $mailSent = send_email($to, $subject, $body);
                                            if (!$mailSent) {
                                                // Log the failure but allow login to proceed or show error?
                                                // For security alerts, we should probably proceed but maybe show a warning.
                                            }

                                            $unique_vld_id = guidv4();
                                            $election_logs_query = "
                                                INSERT INTO voter_login_details (voter_login_details_id, voter_id, details_location) 
                                                VALUES (?, ?, ?)
                                            ";
                                            $statement = $conn->prepare($election_logs_query);
                                            $election_logs_result = $statement->execute([$unique_vld_id, $row['uuid'], $location]);

                                            if ($election_logs_result) {
                                                $_SESSION['voter_accessed'] = $row['uuid'];
                                                $_SESSION['voter_login_details_id'] = $unique_vld_id;

                                                $log_message = "voter ['" . ucwords($row["first_name"] . ' ' . $row["last_name"]) . "'], loggedin, location ('" . $location . "')!";
                                                add_to_log($log_message, $row["uuid"], 'user');

                                                redirect(PROOT . 'votingon');
                                            }
                                        } catch (\Exception $e) {
                                            $displayErrors = "Login failed: " . $e->getMessage();
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

    public function dashboard()
    {
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

    public function ballot()
    {
        global $conn, $voter_result, $started_election;

        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];
        $now = date('Y-m-d H:i:s');

        // Block if already voted
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter_row['uuid'], $voter_row['election_uuid']]);
        if ($checkVoted->fetchColumn() > 0) {
            redirect(PROOT . 'votingon');
        }

        if ($voter_row['election_status'] == 2 || $now > $voter_row['ends_at']) {
            redirect(PROOT . 'votingon');
        }

        $electionUuid = $voter_row['election_uuid'];

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

    public function submitVote()
    {
        global $conn, $voter_result;

        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            die("Invalid CSRF token.");
        }

        $voter_row = $voter_result[0];
        $voter_uuid = $voter_row['uuid'];
        $election_uuid = sanitize($_POST['name-of-election'] ?? '');

        $now = date('Y-m-d H:i:s');
        if ($voter_row['election_status'] != 1 || $election_uuid != $voter_row['election_uuid']) {
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
        $checkVoted->execute([$voter_uuid, $election_uuid]);
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
            $stmt->execute([$vhd_id, $voter_uuid, $election_uuid]);

            // FETCH ELECTION DETAILS EXPLICITLY FOR RECEIPT
            $eStmt = $conn->prepare("SELECT title, organized_by FROM election WHERE uuid = ?");
            $eStmt->execute([$election_uuid]);
            $election_row = $eStmt->fetch();
            $eTitle = $election_row['title'] ?? 'Unknown Election';
            $eOrg = $election_row['organized_by'] ?? 'Unknown Organizer';

            // BUILD EMAIL RECEIPT
            $receipt_html = '
            <div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <div style="background: #1d3d37; padding: 24px; text-align: center;">
                    <h2 style="color: #dcf3b0; margin: 0;">Ballot Confirmation</h2>
                </div>
                <div style="padding: 32px; color: #4a5568; line-height: 1.6;">
                    <p>Hello <strong>' . ucwords($voter_row['std_fname']) . '</strong>,</p>
                    <p>Your ballot has been securely cast and recorded for the <strong>' . $eTitle . '</strong>.</p>
                    
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin: 24px 0;">
                        <p style="margin-top: 0; font-size: 14px; color: #718096; font-weight: bold; text-transform: uppercase;">Election Details</p>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr><td style="padding: 4px 0; color: #718096; width: 120px;">Election:</td><td style="padding: 4px 0; font-weight: 600;">' . $eTitle . '</td></tr>
                            <tr><td style="padding: 4px 0; color: #718096;">Organizer:</td><td style="padding: 4px 0; font-weight: 600;">' . $eOrg . '</td></tr>
                            <tr><td style="padding: 4px 0; color: #718096;">Date Cast:</td><td style="padding: 4px 0; font-weight: 600;">' . date('F j, Y, g:i a') . '</td></tr>
                        </table>
                    </div>

                    <p style="font-weight: bold; margin-bottom: 12px;">Your Selections:</p>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                        <tr style="background: #f1f5f9;">
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Position</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Selection</th>
                        </tr>';

            for ($i = 0; $i < $num_positions; $i++) {
                $pos_id = sanitize($_POST["name-of-positions{$i}"] ?? '');

                // Get position name
                $pStmt = $conn->prepare("SELECT position_name FROM positions WHERE position_id = ?");
                $pStmt->execute([$pos_id]);
                $pName = $pStmt->fetchColumn();

                $selection = "Abstained";

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

                $receipt_html .= '
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' . $pName . '</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 500;">' . $selection . '</td>
                </tr>';
            }

            $receipt_html .= '
                    </table>

                    <p style="margin-top: 32px; font-size: 14px;">Thank you for exercising your right to vote. Your participation helps strengthen our digital democracy.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #a0aec0; border-top: 1px solid #e2e8f0;">
                    &copy; ' . date('Y') . ' Puubu Group. All rights reserved.
                </div>
            </div>';

            send_email($voter_row['email'], "Voting Receipt: " . $eTitle, $receipt_html);

            add_to_log("Voter casted vote and received email receipt", $voter_id, 'user');

            $conn->commit();

            // Do NOT destroy session here, redirect to success page
            redirect(PROOT . 'success');

        } catch (\Exception $e) {
            if ($conn->inTransaction())
                $conn->rollBack();
            die("An error occurred while saving your vote. Please contact administration. " . $e->getMessage());
        }
    }

    public function success()
    {
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

