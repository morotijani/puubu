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
        
        $flash_error = $_SESSION['flash_error'] ?? null;
        $flash_success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        $displayErrors = $flash_error ?? '';

        // Handle legacy form submission if still used, or redirect to dynamic page
        if (isset($_POST['submitVoter'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $displayErrors = "Invalid request token.";
            } else {
                $voter_id = sanitize($_POST['voter_id']);
                $password = $_POST['voter_password'];

                $query = "
                    SELECT v.*, e.status as election_status, e.starts_at, e.ends_at, e.allow_email_login, e.allow_pin_login
                    FROM voters v 
                    INNER JOIN election e ON e.uuid = v.election_uuid
                    WHERE v.voter_id = ?
                ";
                $stmt = $conn->prepare($query);
                $stmt->execute([$voter_id]);
                $voter = $stmt->fetch();

                if ($voter && password_verify($password, $voter['password'])) {
                    // Check election timing and status
                    if ($voter['election_status'] == 1) {
                        $now = date('Y-m-d H:i:s');
                        if ($now >= $voter['starts_at'] && $now <= $voter['ends_at']) {
                             // Check if already voted
                            $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE voter_id = ? AND election_uuid = ?");
                            $checkVoted->execute([$voter['uuid'], $voter['election_uuid']]);
                            if ($checkVoted->fetchColumn() == 0) {
                                // Proceed to complete login
                                $unique_vld_id = guidv4();
                                $conn->prepare("INSERT INTO voter_security_logs (uuid, voter_id, location) VALUES (?, ?, ?)")
                                     ->execute([$unique_vld_id, $voter['uuid'], $location]);

                                $_SESSION['voter_accessed'] = $voter['uuid'];
                                $_SESSION['voter_login_details_id'] = $unique_vld_id;
                                add_to_log("voter logged in, location ('$location')!", $voter["uuid"], 'user');
                                redirect(PROOT . 'votingon');
                            } else {
                                $displayErrors = "You have already cast your vote.";
                            }
                        } else {
                            $displayErrors = "Election is not currently active.";
                        }
                    } else {
                        $displayErrors = "Election is not active.";
                    }
                } else {
                    $displayErrors = "Invalid Voter Details";
                }
            }
        }

        echo $this->twig->render('voter/login.twig', [
            'errorsMsg' => $displayErrors,
            'successMsg' => $flash_success,
            'login_issue' => $login_issue
        ]);
    }

    public function checkVoterId() {
        global $conn;
        $voter_id = sanitize($_POST['voter_id'] ?? '');
        
        if (empty($voter_id)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Please enter your Voter ID']);
        }

        $query = "
            SELECT v.*, e.allow_email_login, e.allow_sms_login, e.allow_pin_login, e.status as election_status, e.starts_at, e.ends_at
            FROM voters v 
            INNER JOIN election e ON e.uuid = v.election_uuid
            WHERE v.voter_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$voter_id]);
        $voter = $stmt->fetch();

        if (!$voter) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Identity ID not found']);
        }

        if ($voter['election_status'] != 1) {
            $this->jsonResponse(['status' => 'error', 'message' => 'The election is not active at this time.']);
        }

        $now = date('Y-m-d H:i:s');
        if ($now < $voter['starts_at']) {
            $this->jsonResponse(['status' => 'error', 'message' => 'This election has not started yet.']);
        } elseif ($now > $voter['ends_at']) {
            $this->jsonResponse(['status' => 'error', 'message' => 'This election has already ended.']);
        }

        // Check if already voted
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter['uuid'], $voter['election_uuid']]);
        if ($checkVoted->fetchColumn() > 0) {
            $this->jsonResponse(['status' => 'error', 'message' => 'You have already cast your vote.']);
        }

        $phone_masked = '';
        if ($voter['phone']) {
            $len = strlen($voter['phone']);
            $phone_masked = substr($voter['phone'], 0, 4) . str_repeat('*', $len - 6) . substr($voter['phone'], -2);
        }

        $this->jsonResponse([
            'status' => 'success',
            'voter_uuid' => $voter['uuid'],
            'settings' => [
                'email' => (int)$voter['allow_email_login'],
                'sms' => (int)$voter['allow_sms_login'],
                'pin' => (int)$voter['allow_pin_login']
            ],
            'phone_masked' => $phone_masked
        ]);
    }

    public function sendOtp() {
        global $conn;
        $voter_uuid = $_POST['voter_uuid'] ?? '';
        
        $stmt = $conn->prepare("SELECT * FROM voters WHERE uuid = ?");
        $stmt->execute([$voter_uuid]);
        $voter = $stmt->fetch();

        if (!$voter || empty($voter['phone'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Voter or phone number not found.']);
        }

        $otp = (string)rand(100000, 999999);
        $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $stmt = $conn->prepare("UPDATE voters SET otp_code = ?, otp_expires_at = ? WHERE uuid = ?");
        $stmt->execute([$otp, $expires, $voter_uuid]);

        $this->mockSendSms($voter['phone'], "Your Kokuromotie voting OTP is: $otp. Expires in 10 minutes.");

        $this->jsonResponse(['status' => 'success', 'message' => 'OTP sent to your phone.']);
    }

    public function verifyOtp() {
        global $conn;
        $voter_uuid = $_POST['voter_uuid'] ?? '';
        $otp = $_POST['otp_code'] ?? '';

        $stmt = $conn->prepare("SELECT * FROM voters WHERE uuid = ?");
        $stmt->execute([$voter_uuid]);
        $voter = $stmt->fetch();

        if (!$voter || $voter['otp_code'] !== $otp) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Invalid OTP code.']);
        }

        if (strtotime($voter['otp_expires_at']) < time()) {
            $this->jsonResponse(['status' => 'error', 'message' => 'OTP code has expired.']);
        }

        // Clear OTP and Login
        $stmt = $conn->prepare("UPDATE voters SET otp_code = NULL, otp_expires_at = NULL WHERE uuid = ?");
        $stmt->execute([$voter_uuid]);

        $this->completeLogin($voter);
    }

    public function directLogin($token) {
        global $conn;
        
        $token = sanitize($token);
        
        $query = "
            SELECT v.*, e.status as election_status, e.starts_at, e.ends_at, e.allow_direct_link
            FROM voters v 
            INNER JOIN election e ON e.uuid = v.election_uuid
            WHERE v.voting_token = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$token]);
        $voter = $stmt->fetch();

        if (!$voter) {
            $_SESSION['flash_error'] = "Invalid or expired voting link.";
            redirect(PROOT . 'signin');
        }

        if ($voter['allow_direct_link'] != 1) {
            $_SESSION['flash_error'] = "Direct link voting is not enabled for this election.";
            redirect(PROOT . 'signin');
        }

        if ($voter['election_status'] == 0) {
            $_SESSION['flash_error'] = "This election has not been started yet. Please check back later.";
            redirect(PROOT . 'signin');
        }

        if ($voter['election_status'] == 2) {
            $_SESSION['flash_error'] = "This election has already ended. Voting is no longer permitted.";
            redirect(PROOT . 'signin');
        }

        $now = date('Y-m-d H:i:s');
        if ($voter['starts_at'] && $now < $voter['starts_at']) {
            $_SESSION['flash_error'] = "Voting for this election has not started yet. It is scheduled to start on " . date("M j, Y, g:i a", strtotime($voter['starts_at'])) . ".";
            redirect(PROOT . 'signin');
        }

        if ($voter['ends_at'] && $now > $voter['ends_at']) {
            $_SESSION['flash_error'] = "The voting window for this election is closed. It ended on " . date("M j, Y, g:i a", strtotime($voter['ends_at'])) . ".";
            redirect(PROOT . 'signin');
        }

        // Check if already voted
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter['uuid'], $voter['election_uuid']]);
        if ($checkVoted->fetchColumn() > 0) {
            $_SESSION['flash_error'] = "You have already cast your vote in this election. Thank you for participating!";
            redirect(PROOT . 'signin');
        }

        // Complete Login
        $this->completeLogin($voter, true);
    }

    public function completeLogin($voter, $redirect = false) {
        global $conn, $location;
        
        $unique_vld_id = guidv4();
        $stmt = $conn->prepare("INSERT INTO voter_security_logs (uuid, voter_id, location) VALUES (?, ?, ?)");
        $stmt->execute([$unique_vld_id, $voter['uuid'], $location]);

        $_SESSION['voter_accessed'] = $voter['uuid'];
        $_SESSION['voter_login_details_id'] = $unique_vld_id;

        $log_message = "voter ['" . ucwords($voter["first_name"] . ' ' . $voter["last_name"]) . "'], loggedin via direct link/OTP, location ('" . $location . "')!";
        add_to_log($log_message, $voter["uuid"], 'user');

        if ($redirect) {
            redirect(PROOT . 'votingon');
        } else {
            $this->jsonResponse(['status' => 'success', 'redirect' => PROOT . 'votingon']);
        }
    }

    private function mockSendSms($phone, $message) {
        $logFile = __DIR__ . '/../../scratch/sms_mock.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $log = "[" . date('Y-m-d H:i:s') . "] SMS to $phone: $message" . PHP_EOL;
        file_put_contents($logFile, $log, FILE_APPEND);
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function dashboard()
    {
        global $conn, $voter_result, $started_election, $has_voted;

        if (!isset($_SESSION['voter_accessed'])) {
            redirect(PROOT . 'signin');
        }

        $voter_row = $voter_result[0];
        $now = date('Y-m-d H:i:s');
        
        $has_started = ($voter_row['election_status'] == 1 && $now >= $voter_row['starts_at']);
        $has_ended = ($voter_row['election_status'] == 2 || $now > $voter_row['ends_at']);
        $not_started_yet = ($voter_row['election_status'] == 1 && $now < $voter_row['starts_at']);

        echo $this->twig->render('voter/dashboard.twig', [
            'voter' => $voter_row,
            'started_election' => $started_election,
            'has_voted' => $has_voted,
            'has_started' => $has_started,
            'has_ended' => $has_ended,
            'not_started_yet' => $not_started_yet
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

        // Block if session not started
        if ($voter_row['election_status'] == 0 || ($voter_row['starts_at'] && $now < $voter_row['starts_at'])) {
            $_SESSION['flash_error'] = "The voting session has not started yet. Please check the countdown on your dashboard.";
            redirect(PROOT . 'votingon');
        }

        // Block if already voted
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter_row['uuid'], $voter_row['election_uuid']]);
        if ($checkVoted->fetchColumn() > 0) {
            redirect(PROOT . 'votingon');
        }

        if ($voter_row['election_status'] == 2 || ($voter_row['ends_at'] && $now > $voter_row['ends_at'])) {
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
                SELECT * FROM contestants 
                WHERE position_id = ? 
                AND election_uuid = ? 
                AND is_deleted = 'no' 
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
        global $conn, $voter_result, $location, $details;

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
            $_SESSION['flash_error'] = "The electoral session is not currently active for this identity.";
            redirect(PROOT . 'votingon');
        }

        if ($now < $voter_row['starts_at']) {
            $_SESSION['flash_error'] = "Unauthorized: The voting session has not started yet. Synchronization error detected.";
            redirect(PROOT . 'votingon');
        }
        if ($now > $voter_row['ends_at']) {
            $_SESSION['flash_error'] = "Unauthorized: The voting session has concluded.";
            redirect(PROOT . 'votingon');
        }

        // CRITICAL: Final double-check before recording votes
        $checkVoted = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE voter_id = ? AND election_uuid = ?");
        $checkVoted->execute([$voter_uuid, $election_uuid]);
        if ($checkVoted->fetchColumn() > 0) {
            die("Critical Error: Your vote has already been recorded for this election.");
        }

        $num_positions = intval($_POST['number-of-positions'] ?? 0);

        try {
            $conn->beginTransaction();

            for ($i = 0; $i < $num_positions; $i++) {
                $position_id = sanitize($_POST["name-of-positions{$i}"] ?? '');
                $voted_ip = $details->ip ?? ($_SERVER['REMOTE_ADDR'] ?? 'Unknown IP');

                if (isset($_POST["contestant{$i}"]) && !empty($_POST["contestant{$i}"])) {
                    $contestant_id = sanitize($_POST["contestant{$i}"]);
                    // Increment results
                    $stmt = $conn->prepare("UPDATE results SET votes_for = votes_for + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                    $stmt->execute([$contestant_id, $position_id, $election_uuid]);

                    // Record Individual Vote
                    $vfid = guidv4();
                    $stmt = $conn->prepare("INSERT INTO voted_for (for_id, voter_id, election_uuid, position_id, candidate_id, voted_location, voted_ip, trash) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
                    $stmt->execute([$vfid, $voter_uuid, $election_uuid, $position_id, $contestant_id, $location, $voted_ip]);

                } elseif (isset($_POST["onecont{$i}"]) && !empty($_POST["onecont{$i}"])) {
                    $val = explode(',', $_POST["onecont{$i}"]);
                    if (count($val) == 2) {
                        $choice = sanitize($val[0]);
                        $contestant_id = sanitize($val[1]);
                        if ($choice === 'yes') {
                            $stmt = $conn->prepare("UPDATE results SET votes_for = votes_for + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                            $stmt->execute([$contestant_id, $position_id, $election_uuid]);
                        } else {
                            $stmt = $conn->prepare("UPDATE results SET votes_against = votes_against + 1 WHERE contestant_id = ? AND position_id = ? AND election_uuid = ?");
                            $stmt->execute([$contestant_id, $position_id, $election_uuid]);
                        }

                        // Record Individual Vote
                        $vfid = guidv4();
                        $choice_val = ($choice === 'yes') ? $contestant_id : 'rejected_' . $contestant_id;
                        $stmt = $conn->prepare("INSERT INTO voted_for (for_id, voter_id, election_uuid, position_id, candidate_id, voted_location, voted_ip, trash) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
                        $stmt->execute([$vfid, $voter_uuid, $election_uuid, $position_id, $choice_val, $location, $voted_ip]);
                    }
                } else {
                    // Skipped
                    $stmt = $conn->prepare("UPDATE positions SET skipped_votes = skipped_votes + 1 WHERE position_id = ? AND election_uuid = ?");
                    $stmt->execute([$position_id, $election_uuid]);

                    // Record Skip
                    $vfid = guidv4();
                    $stmt = $conn->prepare("INSERT INTO voted_for (for_id, voter_id, election_uuid, position_id, candidate_id, voted_location, voted_ip, trash) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
                    $stmt->execute([$vfid, $voter_uuid, $election_uuid, $position_id, 'skipped', $location, $voted_ip]);
                }
            }
                        
                                    // Mark voter as done
                                    $vhd_id = guidv4();
                                    $stmt = $conn->prepare("INSERT INTO voter_participation (uuid, voter_id, election_uuid, status) VALUES (?, ?, ?, 1)");
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
                    <p>Hello <strong>' . ucwords($voter_row['first_name']) . '</strong>,</p>
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
                    $cStmt = $conn->prepare("SELECT first_name, last_name FROM contestants WHERE uuid = ?");
                    $cStmt->execute([$c_id]);
                    $cRow = $cStmt->fetch();
                    $selection = $cRow['first_name'] . " " . $cRow['last_name'];
                } elseif (isset($_POST["onecont{$i}"]) && !empty($_POST["onecont{$i}"])) {
                    $val = explode(',', $_POST["onecont{$i}"]);
                    $choice = $val[0] ?? '';
                    $c_id = $val[1] ?? '';
                    $cStmt = $conn->prepare("SELECT first_name, last_name FROM contestants WHERE uuid = ?");
                    $cStmt->execute([$c_id]);
                    $cRow = $cStmt->fetch();
                    $selection = ($choice === 'yes' ? "Approve: " : "Reject: ") . $cRow['first_name'] . " " . $cRow['last_name'];
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
                    &copy; ' . date('Y') . ' Kokuromotie Group. All rights reserved.
                </div>
            </div>';

            if (isset($voter_row['allow_email_login']) && $voter_row['allow_email_login'] == 1) {
                send_email($voter_row['email'], "Voting Receipt: " . $eTitle, $receipt_html);
                add_to_log("Voter casted vote and received email receipt", $voter_uuid, 'user');
            } else {
                add_to_log("Voter casted vote (email receipt disabled by auth matrix)", $voter_uuid, 'user');
            }

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

    public function logout()
    {
        global $conn;
        if (isset($_SESSION['voter_login_details_id'])) {
            $stmt = $conn->prepare("UPDATE voter_security_logs SET logout_at = NOW(), voter_login_details_status = 0 WHERE uuid = ?");
            $stmt->execute([$_SESSION['voter_login_details_id']]);
        }
        
        $voter_uuid = $_SESSION['voter_accessed'] ?? 'Unknown';
        add_to_log("voter logged out", $voter_uuid, 'user');

        session_unset();
        session_destroy();
        session_start();
        redirect(PROOT . 'signin');
    }
}


