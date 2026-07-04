<?php
namespace App\Controllers;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use PDO;

class AdminController {
    protected $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function login() {
        global $conn;

        if (cadminIsLoggedIn()) {
            redirect(PROOT . 'admin');
        }

        $errorsMsg = '';
        if (isset($_POST['submitAdmin'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $errorsMsg = "Invalid request token.";
            } else {
                $email = sanitize($_POST['admin_email']);
                $pswd = sanitize($_POST['admin_pass']);

                if (!empty($pswd) && !empty($email)) {
                    $query = "SELECT * FROM admins WHERE email = :email";
                    $statement = $conn->prepare($query);
                    $statement->execute([':email' => $email]);
                    $row = $statement->fetch();

                    if ($row && password_verify($pswd, $row['password'])) {
                        cAdminLoggedInID($row['uuid']); // This helper now handles 2FA redirect
                    } else {
                        $errorsMsg = 'Invalid details.';
                    }
                }
            }
        }

        echo $this->twig->render('admin/login.twig', [
            'errorsMsg' => $errorsMsg
        ]);
    }

    public function index() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';

        if ($role === 'super_admin') {
            $statement = $conn->prepare("SELECT * FROM election WHERE status IN (1, 2)");
            $statement->execute();
            
            $total_elections = $conn->query("SELECT COUNT(*) FROM election")->fetchColumn();
            $total_contestants = $conn->query("SELECT COUNT(*) FROM contestants WHERE is_deleted = 'no'")->fetchColumn();
            $total_positions = $conn->query("SELECT COUNT(*) FROM positions")->fetchColumn();
            $total_voters = $conn->query("SELECT COUNT(*) FROM voters")->fetchColumn();
            $total_organizers = $conn->query("SELECT COUNT(*) FROM admins WHERE role = 'organizer'")->fetchColumn();
            
            $stmt_orgs = $conn->prepare("SELECT * FROM admins WHERE role = 'organizer' ORDER BY id DESC LIMIT 5");
            $stmt_orgs->execute();
            $recent_organizers = $stmt_orgs->fetchAll();
        } else {
            $statement = $conn->prepare("SELECT * FROM election WHERE status IN (1, 2) AND organizer_id = ?");
            $statement->execute([$admin_id]);
            
            $stmt = $conn->prepare("SELECT COUNT(*) FROM election WHERE organizer_id = ?");
            $stmt->execute([$admin_id]);
            $total_elections = $stmt->fetchColumn();
            
            $stmt = $conn->prepare("SELECT COUNT(c.id) FROM contestants c INNER JOIN election e ON c.election_uuid = e.uuid WHERE c.is_deleted = 'no' AND e.organizer_id = ?");
            $stmt->execute([$admin_id]);
            $total_contestants = $stmt->fetchColumn();
            
            $stmt = $conn->prepare("SELECT COUNT(p.id) FROM positions p INNER JOIN election e ON p.election_uuid = e.uuid WHERE e.organizer_id = ?");
            $stmt->execute([$admin_id]);
            $total_positions = $stmt->fetchColumn();
            
            $stmt = $conn->prepare("SELECT COUNT(v.id) FROM voters v INNER JOIN election e ON v.election_uuid = e.uuid WHERE e.organizer_id = ?");
            $stmt->execute([$admin_id]);
            $total_voters = $stmt->fetchColumn();
        }

        $elections = $statement->fetchAll();

        echo $this->twig->render('admin/dashboard.twig', [
            'admin' => $admin_data,
            'elections' => $elections,
            'recent_organizers' => $recent_organizers ?? [],
            'stats' => [
                'total_elections' => $total_elections,
                'total_contestants' => $total_contestants,
                'total_positions' => $total_positions,
                'total_voters' => $total_voters,
                'total_organizers' => $total_organizers ?? 0
            ]
        ]);
    }

    public function verify2fa() {
        global $conn;
        if (!isset($_SESSION['crAdmin']) || !isset($_SESSION['2fa_pending'])) {
            redirect(PROOT . 'admin/signin');
        }

        $errorsMsg = '';

        if (isset($_POST['verify2fa'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $errorsMsg = "Invalid token.";
            } else {
                $code = sanitize($_POST['otp_code']);
                $admin_id = $_SESSION['crAdmin'];

                $query = "SELECT google_auth_secret FROM admins WHERE uuid = ?";
                $statement = $conn->prepare($query);
                $statement->execute([$admin_id]);
                $row = $statement->fetch();

                $g = new GoogleAuthenticator();
                if ($g->checkCode($row['google_auth_secret'], $code)) {
                    unset($_SESSION['2fa_pending']);
                    $_SESSION['flash_success'] = '2FA Verified!';
                    add_to_log("admin verified 2FA", $admin_id, 'admin');
                    redirect(PROOT . 'admin');
                } else {
                    $errorsMsg = "Invalid code.";
                }
            }
        }

        echo $this->twig->render('admin/verify_2fa.twig', [
            'errorsMsg' => $errorsMsg
        ]);
    }

    public function setup2fa() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn()) {
            cadminLoginErrorRedirect();
        }

        $g = new GoogleAuthenticator();
        $secret = $admin_data['google_auth_secret'];
        if (empty($secret)) {
            $secret = $g->generateSecret();
            $conn->prepare("UPDATE admins SET google_auth_secret = ? WHERE uuid = ?")
                 ->execute([$secret, $admin_data['uuid']]);
        }

        $qrCodeUrl = GoogleQrUrl::generate($admin_data['email'], $secret, 'Kokuromotie E-Voting');
        
        $errorsMsg = '';
        if (isset($_POST['activate2fa'])) {
            $code = sanitize($_POST['otp_code']);
            if ($g->checkCode($secret, $code)) {
                $conn->prepare("UPDATE admins SET is_2fa_enabled = 1 WHERE uuid = ?")
                     ->execute([$admin_data['uuid']]);
                $_SESSION['flash_success'] = '2FA Activated!';
                redirect(PROOT . 'admin/settings');
            } else {
                $errorsMsg = "Invalid code.";
            }
        }

        echo $this->twig->render('admin/setup_2fa.twig', [
            'qrCodeUrl' => $qrCodeUrl,
            'errorsMsg' => $errorsMsg,
            'is_enabled' => $admin_data['is_2fa_enabled']
        ]);
    }

    public function elections() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT e.*, a.first_name as org_fname, a.last_name as org_lname FROM election e LEFT JOIN admins a ON e.organizer_id = a.uuid WHERE e.is_deleted = 0 ORDER BY e.uuid DESC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT e.*, a.first_name as org_fname, a.last_name as org_lname FROM election e LEFT JOIN admins a ON e.organizer_id = a.uuid WHERE e.organizer_id = ? AND e.is_deleted = 0 ORDER BY e.uuid DESC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        $edit_id = $_GET['edit'] ?? null;
        $edit_election = null;
        if ($edit_id) {
            if ($role === 'super_admin') {
                $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND status = 0");
                $stmt->execute([$edit_id]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND status = 0 AND organizer_id = ?");
                $stmt->execute([$edit_id, $admin_id]);
            }
            $edit_election = $stmt->fetch();
        }

        echo $this->twig->render('admin/elections.twig', [
            'elections' => $elections,
            'edit_election' => $edit_election
        ]);
    }

    public function electionView($id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';

        // 1. Fetch Election Details
        $stmt = $conn->prepare("SELECT e.*, a.first_name as org_fname, a.last_name as org_lname 
                               FROM election e 
                               LEFT JOIN admins a ON e.organizer_id = a.uuid 
                               WHERE e.uuid = ?");
        $stmt->execute([$id]);
        $election = $stmt->fetch();

        if (!$election || ($role !== 'super_admin' && $election['organizer_id'] !== $admin_id)) {
            $_SESSION['flash_error'] = "Invalid election or access denied.";
            redirect(PROOT . 'admin/elections');
        }

        // 2. Fetch Positions and Candidates
        $stmt = $conn->prepare("SELECT * FROM positions WHERE election_uuid = ? ORDER BY position_name ASC");
        $stmt->execute([$id]);
        $positions = $stmt->fetchAll();

        foreach ($positions as &$pos) {
            $stmtC = $conn->prepare("SELECT * FROM contestants WHERE position_id = ? AND is_deleted = 'no' ORDER BY contestant_ballot_number ASC");
            $stmtC->execute([$pos['position_id']]);
            $pos['candidates'] = $stmtC->fetchAll();
        }

        // 3. Fetch Voters with Participation Status
        $stmtV = $conn->prepare("
            SELECT v.*, vp.uuid as participation_uuid
            FROM voters v 
            LEFT JOIN voter_participation vp ON v.uuid = vp.voter_id AND vp.election_uuid = ?
            WHERE v.election_uuid = ?
            ORDER BY v.last_name ASC
        ");
        $stmtV->execute([$id, $id]);
        $voters = $stmtV->fetchAll();

        $total_voters = count($voters);

        // 4. Fetch Participation Stats
        $stmtP = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE election_uuid = ?");
        $stmtP->execute([$id]);
        $total_votes = $stmtP->fetchColumn();

        echo $this->twig->render('admin/elections/view.twig', [
            'election' => $election,
            'positions' => $positions,
            'voters' => $voters,
            'stats' => [
                'total_voters' => $total_voters,
                'total_votes' => $total_votes,
                'participation_rate' => $total_voters > 0 ? round(($total_votes / $total_voters) * 100, 1) : 0
            ]
        ]);
    }

    public function electionStore() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/elections');
        }

        $name = sanitize($_POST['title']);
        $by = sanitize($_POST['organized_by']);
        $edit_id = $_POST['edit_id'] ?? null;
        
        $allow_email = isset($_POST['allow_email_login']) ? 1 : 0;
        $allow_sms = isset($_POST['allow_sms_login']) ? 1 : 0;
        $allow_pin = isset($_POST['allow_pin_login']) ? 1 : 0;
        $allow_direct = isset($_POST['allow_direct_link']) ? 1 : 0;

        if (empty($name) || empty($by)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/elections' . ($edit_id ? '?edit=' . $edit_id : ''));
        }
        
        if ($allow_email == 0 && $allow_sms == 0 && $allow_pin == 0 && $allow_direct == 0) {
            $_SESSION['flash_error'] = "You must select at least one login option.";
            redirect(PROOT . 'admin/elections' . ($edit_id ? '?edit=' . $edit_id : ''));
        }

        $admin_id_to_use = $admin_id; // Default organizer is current user
        // Super admins can assign organizers (future feature), for now default to self
        
        if ($edit_id) {
            if (($admin_data['role'] ?? 'organizer') === 'super_admin') {
                $stmt = $conn->prepare("UPDATE election SET title = ?, organized_by = ?, allow_email_login = ?, allow_sms_login = ?, allow_pin_login = ?, allow_direct_link = ? WHERE uuid = ? AND status = 0");
                $result = $stmt->execute([$name, $by, $allow_email, $allow_sms, $allow_pin, $allow_direct, $edit_id]);
            } else {
                $stmt = $conn->prepare("UPDATE election SET title = ?, organized_by = ?, allow_email_login = ?, allow_sms_login = ?, allow_pin_login = ?, allow_direct_link = ? WHERE uuid = ? AND status = 0 AND organizer_id = ?");
                $result = $stmt->execute([$name, $by, $allow_email, $allow_sms, $allow_pin, $allow_direct, $edit_id, $admin_id]);
            }
            if ($result && $stmt->rowCount() > 0) {
                add_to_log("Updated election: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Election updated successfully.";
            } else {
                $_SESSION['flash_error'] = "No changes made. The election may be active, ended, or you don't have permission to edit it.";
            }
        } else {
            $unique_id = guidv4();
            $stmt = $conn->prepare("INSERT INTO election (uuid, title, organized_by, organizer_id, allow_email_login, allow_sms_login, allow_pin_login, allow_direct_link, status, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0)");
            $result = $stmt->execute([$unique_id, $name, $by, $admin_id, $allow_email, $allow_sms, $allow_pin, $allow_direct]);
            if ($result) {
                add_to_log("Created new election: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Election created successfully.";
            } else {
                $_SESSION['flash_error'] = "Failed to create election.";
            }
        }

        redirect(PROOT . 'admin/elections');
    }

    public function startElection($id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';

        // Check ownership
        $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ?");
        $stmt->execute([$id]);
        $election = $stmt->fetch();

        if (!$election || ($role !== 'super_admin' && $election['organizer_id'] !== $admin_id)) {
            $_SESSION['flash_error'] = "Invalid election or permission denied.";
            redirect(PROOT . 'admin/elections');
        }

        if (isset($_POST['start_election'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = "Invalid CSRF token.";
            } else {
                $start_date = sanitize($_POST['start_date']);
                $end_date = sanitize($_POST['end_date']);
                $otp_code = sanitize($_POST['otp_code']);

                if (empty($start_date) || empty($end_date) || empty($otp_code)) {
                    $_SESSION['flash_error'] = "All fields are required.";
                } elseif ($start_date >= $end_date) {
                    $_SESSION['flash_error'] = "Start date must be before end date.";
                } else {
                    // Verify 2FA
                    $g = new GoogleAuthenticator();
                    if ($g->checkCode($admin_data['google_auth_secret'], $otp_code)) {
                        $stmt = $conn->prepare("UPDATE election SET status = 1, starts_at = ?, ends_at = ? WHERE uuid = ?");
                        if ($stmt->execute([$start_date, $end_date, $id])) {
                            $_SESSION['flash_success'] = "Election has been started successfully.";
                            add_to_log("Started election: " . $election['title'], $admin_id, 'admin');
                        } else {
                            $_SESSION['flash_error'] = "Failed to start election.";
                        }
                    } else {
                        $_SESSION['flash_error'] = "Invalid 2FA code.";
                    }
                }
            }
        }
        redirect(PROOT . 'admin/elections');
    }

    public function stopElection($id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';

        // Check ownership
        $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ?");
        $stmt->execute([$id]);
        $election = $stmt->fetch();

        if (!$election || ($role !== 'super_admin' && $election['organizer_id'] !== $admin_id)) {
            $_SESSION['flash_error'] = "Invalid election or permission denied.";
            redirect(PROOT . 'admin/elections');
        }

        if (isset($_POST['stop_election'])) {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = "Invalid CSRF token.";
            } else {
                $reason = sanitize($_POST['manual_stop_reason']);
                $otp_code = sanitize($_POST['otp_code']);

                if (empty($reason) || empty($otp_code)) {
                    $_SESSION['flash_error'] = "Reason and 2FA code are required.";
                } else {
                    // Verify 2FA
                    $g = new GoogleAuthenticator();
                    if ($g->checkCode($admin_data['google_auth_secret'], $otp_code)) {
                        $stmt = $conn->prepare("UPDATE election SET status = 2, manual_stop_reason = ?, election_manual_stop_time = NOW() WHERE uuid = ?");
                        if ($stmt->execute([$reason, $id])) {
                            $_SESSION['flash_success'] = "Election has been stopped and marked as completed.";
                            add_to_log("Stopped election: " . $election['title'] . " Reason: " . $reason, $admin_id, 'admin');
                        } else {
                            $_SESSION['flash_error'] = "Failed to stop election.";
                        }
                    } else {
                        $_SESSION['flash_error'] = "Invalid 2FA code.";
                    }
                }
            }
        }
        redirect(PROOT . 'admin/elections');
    }

    public function electionDelete($id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND status = 0");
            $stmt->execute([$id]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND status = 0 AND organizer_id = ?");
            $stmt->execute([$id, $admin_id]);
        }
        
        if ($stmt->rowCount() > 0) {
            $conn->prepare("DELETE FROM election WHERE uuid = ?")->execute([$id]);
            add_to_log("Deleted election: $id", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Election deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Election cannot be deleted (it may be active or ended).";
        }
        
        redirect(PROOT . 'admin/elections');
    }

    public function positions() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT p.*, e.title, e.organized_by, e.status FROM positions p INNER JOIN election e ON p.election_uuid = e.uuid WHERE e.is_deleted = 0 ORDER BY p.position_id DESC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT p.*, e.title, e.organized_by, e.status FROM positions p INNER JOIN election e ON p.election_uuid = e.uuid WHERE e.organizer_id = ? AND e.is_deleted = 0 ORDER BY p.position_id DESC");
            $stmt->execute([$admin_id]);
        }
        $positions = $stmt->fetchAll();

        // Fetch all non-deleted elections for the dropdown and filters
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE is_deleted = 0 ORDER BY title ASC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE organizer_id = ? AND is_deleted = 0 ORDER BY title ASC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        $edit_id = $_GET['edit'] ?? null;
        $edit_position = null;
        if ($edit_id) {
            $stmt = $conn->prepare("SELECT * FROM positions WHERE position_id = ?");
            $stmt->execute([$edit_id]);
            $edit_position = $stmt->fetch();
        }

        echo $this->twig->render('admin/positions.twig', [
            'positions' => $positions,
            'elections' => $elections,
            'draft_elections' => array_filter($elections, function($e) { return $e['status'] == 0; }),
            'edit_position' => $edit_position
        ]);
    }

    public function positionStore() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/positions');
        }

        $name = sanitize($_POST['position_name']);
        $election_id = sanitize($_POST['election_uuid']);
        $gender_restriction = sanitize($_POST['gender_restriction'] ?? 'all');
        $edit_id = $_POST['edit_id'] ?? null;

        if (empty($name) || empty($election_id)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/positions' . ($edit_id ? '?edit=' . $edit_id : ''));
        }

        // Security check: only allow adding/editing positions for draft elections
        $stmtCheck = $conn->prepare("SELECT status FROM election WHERE uuid = ?");
        $stmtCheck->execute([$election_id]);
        $election_status = $stmtCheck->fetchColumn();
        
        if ($election_status !== false && $election_status != 0) {
            $_SESSION['flash_error'] = "You cannot add or modify positions for an active or completed election.";
            redirect(PROOT . 'admin/positions');
        }

        if ($edit_id) {
            $stmt = $conn->prepare("UPDATE positions SET position_name = ?, election_uuid = ?, gender_restriction = ? WHERE position_id = ?");
            $result = $stmt->execute([$name, $election_id, $gender_restriction, $edit_id]);
            if ($result) {
                add_to_log("Updated position: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Position updated successfully.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO positions (position_id, position_name, election_uuid, gender_restriction) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([guidv4(), $name, $election_id, $gender_restriction]);
            if ($result) {
                add_to_log("Created position: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Position created successfully.";
            }
        }

        redirect(PROOT . 'admin/positions');
    }

    public function positionDelete($id) {
        global $conn, $admin_id, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $role = $admin_data['role'] ?? 'organizer';
        
        // Security: Ensure position is for a draft election before deleting
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT p.* FROM positions p INNER JOIN election e ON p.election_uuid = e.uuid WHERE p.position_id = ? AND e.status = 0");
            $stmt->execute([$id]);
        } else {
            $stmt = $conn->prepare("SELECT p.* FROM positions p INNER JOIN election e ON p.election_uuid = e.uuid WHERE p.position_id = ? AND e.status = 0 AND e.organizer_id = ?");
            $stmt->execute([$id, $admin_id]);
        }
        
        if ($stmt->rowCount() > 0) {
            $conn->prepare("DELETE FROM positions WHERE position_id = ?")->execute([$id]);
            add_to_log("Deleted position: $id", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Position deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Position cannot be deleted (election is active or ended).";
        }
        
        redirect(PROOT . 'admin/positions');
    }

    public function contestants() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        // Pagination & Filter Parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        $election_id = $_GET['election'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $whereClauses = ["c.is_deleted = 'no'"];
        $params = [];
        
        if ($role !== 'super_admin') {
            $whereClauses[] = "e.organizer_id = ?";
            $params[] = $admin_id;
        }
        
        if ($election_id !== 'all') {
            $whereClauses[] = "c.election_uuid = ?";
            $params[] = $election_id;
        }
        
        if (!empty($search)) {
            $whereClauses[] = "(c.first_name LIKE ? OR c.last_name LIKE ? OR p.position_name LIKE ? OR e.title LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereSql = "WHERE " . implode(" AND ", $whereClauses);
        
        // Get Total Count for Pagination
        $countQuery = "
            SELECT COUNT(*) 
            FROM contestants c
            INNER JOIN positions p ON c.position_id = p.position_id
            INNER JOIN election e ON c.election_uuid = e.uuid
            $whereSql
        ";
        $stmtCount = $conn->prepare($countQuery);
        $stmtCount->execute($params);
        $totalItems = $stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $limit);
        
        // Fetch Paginated Data
        $query = "
            SELECT c.*, p.position_name, e.title, e.organized_by, e.status 
            FROM contestants c
            INNER JOIN positions p ON c.position_id = p.position_id
            INNER JOIN election e ON c.election_uuid = e.uuid
            $whereSql
            ORDER BY c.uuid DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $contestants = $stmt->fetchAll();

        // Fetch all elections for the filter
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election ORDER BY title ASC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE organizer_id = ? ORDER BY title ASC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/index.twig', [
            'contestants' => $contestants,
            'elections' => $elections,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'limit' => $limit,
                'election' => $election_id,
                'search' => $search
            ]
        ]);
    }

    public function contestantForm($id = null) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        $return_to_election = $_GET['return_to_election'] ?? null;
        $contestant = null;
        $positions = [];
        
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM contestants WHERE uuid = ?");
            $stmt->execute([$id]);
            $contestant = $stmt->fetch();
            
            if ($contestant) {
                $stmt = $conn->prepare("SELECT * FROM positions WHERE election_uuid = ?");
                $stmt->execute([$contestant['election_uuid']]);
                $positions = $stmt->fetchAll();
            }
        }

        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 ORDER BY title ASC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 AND organizer_id = ? ORDER BY title ASC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/form.twig', [
            'contestant' => $contestant,
            'elections' => $elections,
            'positions' => $positions,
            'return_to_election' => $return_to_election
        ]);
    }

    public function contestantStore() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/contestants');
        }

        $id = $_POST['uuid'] ?? null;
        $fname = sanitize($_POST['first_name']);
        $lname = sanitize($_POST['last_name']);
        $gender = sanitize($_POST['gender']);
        $ballot_no = sanitize($_POST['contestant_ballot_number']);
        $position_id = sanitize($_POST['position_id']);
        $election_id = sanitize($_POST['sel_election']);
        $old_profile = $_POST['old_profile'] ?? '';

        $profile_img = $old_profile;

        // Security Check: Unique Ballot Number per Position/Election
        $checkQuery = "SELECT * FROM contestants WHERE election_uuid = ? AND position_id = ? AND contestant_ballot_number = ? AND is_deleted = 'no'";
        $checkParams = [$election_id, $position_id, $ballot_no];
        if ($id) {
            $checkQuery .= " AND uuid != ?";
            $checkParams[] = $id;
        }
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->execute($checkParams);
        
        if ($stmtCheck->rowCount() > 0) {
            $_SESSION['flash_error'] = "Security Violation: Ballot Intelligence ID #$ballot_no is already deployed for this office in the current session.";
            if ($id) {
                redirect(PROOT . "admin/contestants/edit/$id");
            } else {
                redirect(PROOT . "admin/contestants/add");
            }
        }

        $return_to_election = $_POST['return_to_election'] ?? null;
        $redirect_query = $return_to_election ? "?return_to_election=$return_to_election" : "";

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $profile_img = uniqid('', true) . '.' . $ext;
            $target = BASEURL . 'media/uploadedprofile/' . $profile_img;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
                if ($old_profile && file_exists(BASEURL . 'media/uploadedprofile/' . $old_profile)) {
                    unlink(BASEURL . 'media/uploadedprofile/' . $old_profile);
                }
            }
        }

        if ($id) {
            $query = "UPDATE contestants SET first_name = ?, last_name = ?, gender = ?, contestant_ballot_number = ?, position_id = ?, election_uuid = ?, profile_image = ? WHERE uuid = ?";
            $conn->prepare($query)->execute([$fname, $lname, $gender, $ballot_no, $position_id, $election_id, $profile_img, $id]);
            $_SESSION['flash_success'] = "Contestant updated successfully.";
        } else {
            $new_id = guidv4();
            $query = "INSERT INTO contestants (uuid, first_name, last_name, gender, contestant_ballot_number, position_id, election_uuid, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $conn->prepare($query)->execute([$new_id, $fname, $lname, $gender, $ballot_no, $position_id, $election_id, $profile_img]);
            
            // Initialize vote counts
            $conn->prepare("INSERT INTO results (uuid, votes_for, votes_against, contestant_id, position_id, election_uuid) VALUES (?, 0, 0, ?, ?, ?)")
                 ->execute([guidv4(), $new_id, $position_id, $election_id]);
            
            $_SESSION['flash_success'] = "Contestant added successfully.";
        }

        if ($return_to_election) {
            redirect(PROOT . "admin/elections/view/$return_to_election");
        }
        redirect(PROOT . 'admin/contestants');
    }

    public function getElectionLoginSettings($uuid) {
        global $conn;
        $stmt = $conn->prepare("SELECT allow_email_login, allow_sms_login, allow_pin_login, allow_direct_link FROM election WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $settings = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$settings) {
            $settings = ['allow_email_login' => 1, 'allow_sms_login' => 0, 'allow_pin_login' => 0, 'allow_direct_link' => 0];
        }
        
        header('Content-Type: application/json');
        echo json_encode($settings);
        exit;
    }

    public function getPositionsByElection($uuid) {
        global $conn;
        $stmt = $conn->prepare("SELECT position_id, position_name FROM positions WHERE election_uuid = ? ORDER BY position_name ASC");
        $stmt->execute([$uuid]);
        $positions = $stmt->fetchAll();
        
        header('Content-Type: application/json');
        echo json_encode($positions);
        exit;
    }

    public function getAvailableBallotNumbers($election_uuid, $position_id) {
        global $conn;
        $contestant_uuid = $_GET['contestant_uuid'] ?? null;
        
        // Get already taken numbers for this position in this election
        $query = "SELECT contestant_ballot_number FROM contestants WHERE election_uuid = ? AND position_id = ? AND is_deleted = 'no'";
        $params = [$election_uuid, $position_id];
        
        if ($contestant_uuid) {
            $query .= " AND uuid != ?";
            $params[] = $contestant_uuid;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $taken = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $available = [];
        for ($i = 1; $i <= 30; $i++) {
            if (!in_array($i, $taken)) {
                $available[] = $i;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($available);
        exit;
    }

    public function contestantArchive() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $query = "
                SELECT c.*, p.position_name, e.title, e.organized_by 
                FROM contestants c
                INNER JOIN positions p ON c.position_id = p.position_id
                INNER JOIN election e ON c.election_uuid = e.uuid
                WHERE c.is_deleted = 'yes'
                ORDER BY c.uuid DESC
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute();
        } else {
            $query = "
                SELECT c.*, p.position_name, e.title, e.organized_by 
                FROM contestants c
                INNER JOIN positions p ON c.position_id = p.position_id
                INNER JOIN election e ON c.election_uuid = e.uuid
                WHERE c.is_deleted = 'yes' AND e.organizer_id = ?
                ORDER BY c.uuid DESC
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute([$admin_id]);
        }
        $contestants = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/archive.twig', [
            'contestants' => $contestants
        ]);
    }

    public function contestantToggleDelete($id, $status) {
        global $conn;
        if (!cadminIsLoggedIn()) {
            cadminLoginErrorRedirect();
        }
        $conn->prepare("UPDATE contestants SET is_deleted = ? WHERE uuid = ?")->execute([$status, $id]);
        $_SESSION['flash_success'] = ($status == 'yes') ? "Contestant moved to archive." : "Contestant restored.";
        redirect(PROOT . 'admin/contestants' . ($status == 'no' ? '/archive' : ''));
    }

    public function voters() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        $role = $admin_data['role'] ?? 'organizer';

        // Pagination & Filter Parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 25;
        $offset = ($page - 1) * $limit;
        
        $election_id = $_GET['election'] ?? 'all';
        $search = $_GET['search'] ?? '';

        // Ensure all voters have a token for direct link access (do this for the whole table or just the view? Better to do it once in a while or on demand)
        // For performance, we'll only do it for voters without token in the background or small batches if needed.
        
        $whereClauses = ["1=1"];
        $params = [];
        
        if ($role !== 'super_admin') {
            $whereClauses[] = "e.organizer_id = ?";
            $params[] = $admin_id;
        }
        
        if ($election_id !== 'all') {
            $whereClauses[] = "v.election_uuid = ?";
            $params[] = $election_id;
        }
        
        if (!empty($search)) {
            $whereClauses[] = "(v.first_name LIKE ? OR v.last_name LIKE ? OR v.voter_id LIKE ? OR v.email LIKE ? OR v.phone LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $whereSql = "WHERE " . implode(" AND ", $whereClauses);

        // Get Total Count
        $countQuery = "
            SELECT COUNT(*) 
            FROM voters v
            INNER JOIN election e ON v.election_uuid = e.uuid
            $whereSql
        ";
        $stmtCount = $conn->prepare($countQuery);
        $stmtCount->execute($params);
        $totalItems = $stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        // Fetch Paginated Data
        $query = "
            SELECT v.*, e.title, e.organized_by, e.status as election_status, e.allow_direct_link 
            FROM voters v
            INNER JOIN election e ON v.election_uuid = e.uuid
            $whereSql
            ORDER BY v.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $voters = $stmt->fetchAll();

        if ($role === 'super_admin') {
            $e_stmt = $conn->prepare("SELECT * FROM election ORDER BY id DESC");
            $e_stmt->execute();
        } else {
            $e_stmt = $conn->prepare("SELECT * FROM election WHERE organizer_id = ? ORDER BY id DESC");
            $e_stmt->execute([$admin_id]);
        }
        $elections = $e_stmt->fetchAll();

        echo $this->twig->render('admin/voters/index.twig', [
            'voters' => $voters,
            'elections' => $elections,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'limit' => $limit,
                'election' => $election_id,
                'search' => $search
            ]
        ]);
    }

    public function voterForm($id = null) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        $return_to_election = $_GET['return_to_election'] ?? null;
        $voter = null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM voters WHERE uuid = ?");
            $stmt->execute([$id]);
            $voter = $stmt->fetch();
        }

        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 ORDER BY title ASC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 AND organizer_id = ? ORDER BY title ASC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/voters/form.twig', [
            'voter' => $voter,
            'elections' => $elections,
            'return_to_election' => $return_to_election
        ]);
    }

    public function voterStore() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/voters');
        }

        $id = $_POST['uuid'] ?? null;
        $voter_id = sanitize($_POST['voter_id']);
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $gender = sanitize($_POST['gender'] ?? 'male');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $election_id = sanitize($_POST['election_uuid']);

        if (empty($voter_id) || empty($first_name) || empty($last_name) || empty($election_id)) {
            $_SESSION['flash_error'] = "Voter ID, First Name, Last Name, and Election are required.";
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }

        // Fetch election settings
        $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ?");
        $stmt->execute([$election_id]);
        $election = $stmt->fetch();
        if (!$election) {
            $_SESSION['flash_error'] = "Invalid election.";
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }

        if ($election['allow_email_login'] && empty($email)) {
            $_SESSION['flash_error'] = "Email is required for this election's login settings.";
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }
        if ($election['allow_sms_login'] && empty($phone)) {
            $_SESSION['flash_error'] = "Phone number is required for this election's login settings.";
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }

        // Validation: Unique Identity ID, Email, and Phone within the same election
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM voters WHERE (voter_id = ? OR (email != '' AND email = ?) OR (phone != '' AND phone = ?)) AND election_uuid = ? AND uuid != ?");
            $stmt->execute([$voter_id, $email, $phone, $election_id, $id]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM voters WHERE (voter_id = ? OR (email != '' AND email = ?) OR (phone != '' AND phone = ?)) AND election_uuid = ?");
            $stmt->execute([$voter_id, $email, $phone, $election_id]);
        }
        
        $duplicate = $stmt->fetch();
        if ($duplicate) {
            if ($duplicate['voter_id'] == $voter_id) {
                $msg = "Identity ID '$voter_id' is already registered for this election.";
            } elseif ($duplicate['email'] == $email && !empty($email)) {
                $msg = "Email '$email' is already registered for this election.";
            } else {
                $msg = "Phone '$phone' is already registered for this election.";
            }
            $_SESSION['flash_error'] = $msg;
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }

        if ($id) {
            $query = "UPDATE voters SET voter_id = ?, first_name = ?, last_name = ?, gender = ?, email = ?, phone = ?, election_uuid = ? WHERE uuid = ?";
            $conn->prepare($query)->execute([$voter_id, $first_name, $last_name, $gender, $email, $phone, $election_id, $id]);
            $_SESSION['flash_success'] = "Voter updated successfully.";
        } else {
            // Generate password
            $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
            $password = substr(str_shuffle($string), 0, 8);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $new_uuid = guidv4();
            $voting_token = guidv4();
            $query = "INSERT INTO voters (uuid, voter_id, password, pin_code, first_name, last_name, gender, email, phone, election_uuid, voting_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $conn->prepare($query)->execute([$new_uuid, $voter_id, $hashed, $password, $first_name, $last_name, $gender, $email, $phone, $election_id, $voting_token]);
            
            $_SESSION['flash_success'] = "Voter added successfully. Password is: $password";
        }

        $return_to_election = $_POST['return_to_election'] ?? null;
        if ($return_to_election) {
            redirect(PROOT . "admin/elections/view/$return_to_election");
        }
        redirect(PROOT . 'admin/voters');
    }

    public function voterDelete($id) {
        global $conn;
        if (!cadminIsLoggedIn()) {
            cadminLoginErrorRedirect();
        }
        $conn->prepare("DELETE FROM voters WHERE uuid = ?")->execute([$id]);
        $_SESSION['flash_success'] = "Voter deleted.";
        redirect(PROOT . 'admin/voters');
    }

    public function voterBulkDelete() {
        global $conn;
        if (!cadminIsLoggedIn()) {
            cadminLoginErrorRedirect();
        }
        if (isset($_POST['voter_ids']) && is_array($_POST['voter_ids'])) {
            $placeholders = implode(',', array_fill(0, count($_POST['voter_ids']), '?'));
            $conn->prepare("DELETE FROM voters WHERE uuid IN ($placeholders)")->execute($_POST['voter_ids']);
            $_SESSION['flash_success'] = count($_POST['voter_ids']) . " voters deleted.";
        }
        redirect(PROOT . 'admin/voters');
    }

    public function voterTruncate() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = "Invalid CSRF token.";
                redirect(PROOT . 'admin/voters');
            }

            $scope = $_POST['wipe_scope'] ?? 'election';
            $election_id = $_POST['election_uuid'] ?? null;

            if ($scope === 'all') {
                $conn->exec("TRUNCATE TABLE voters");
                add_to_log("Wiped all voters", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Entire voter registry cleared.";
            } else {
                if (!$election_id) {
                    $_SESSION['flash_error'] = "Please select an election to wipe.";
                    redirect(PROOT . 'admin/voters');
                }
                $stmt = $conn->prepare("DELETE FROM voters WHERE election_uuid = ?");
                $stmt->execute([$election_id]);
                add_to_log("Wiped voters for election: $election_id", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Voters for the selected election have been removed.";
            }
        }
        redirect(PROOT . 'admin/voters');
    }

    public function voterImport() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = "Invalid CSRF token.";
                redirect(PROOT . 'admin/voters/import');
            }

            $election_id = $_POST['election_uuid'] ?? null;
            $password_verify = $_POST['security_pin'] ?? '';

            // Security check (2FA fallback to password)
            if (!password_verify($password_verify, $admin_data['password'])) {
                $_SESSION['flash_error'] = "Security verification failed. Incorrect password.";
                redirect(PROOT . 'admin/voters/import');
            }

            if (!$election_id || !isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== 0) {
                $_SESSION['flash_error'] = "Please select an election and a valid CSV file.";
                redirect(PROOT . 'admin/voters/import');
            }

            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");
            
            // Skip header
            fgetcsv($handle);

            $imported = 0;
            $errors = 0;

            $conn->beginTransaction();
            try {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) < 4) continue;

                    $voter_id = sanitize($data[0]);
                    $first_name = sanitize($data[1]);
                    $last_name = sanitize($data[2]);
                    $email = sanitize($data[3]);
                    $gender = sanitize($data[4] ?? 'male');

                    // Check for duplicate in this election
                    $stmt = $conn->prepare("SELECT id FROM voters WHERE (voter_id = ? OR email = ?) AND election_uuid = ?");
                    $stmt->execute([$voter_id, $email, $election_id]);
                    if ($stmt->fetch()) {
                        $errors++;
                        continue;
                    }

                    // Generate password
                    $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
                    $raw_pass = substr(str_shuffle($string), 0, 8);
                    $hashed = password_hash($raw_pass, PASSWORD_DEFAULT);
                    $new_uuid = guidv4();
                    $voting_token = guidv4();
                    
                    // If voter_id is numeric and no separate phone, use voter_id as phone
                    $phone = (is_numeric(str_replace(['+', ' ', '(', ')', '-'], '', $voter_id))) ? $voter_id : '';
                    
                    $query = "INSERT INTO voters (uuid, voter_id, password, first_name, last_name, gender, email, phone, election_uuid, voting_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $conn->prepare($query)->execute([$new_uuid, $voter_id, $hashed, $first_name, $last_name, $gender, $email, $phone, $election_id, $voting_token]);
                    $imported++;
                }
                $conn->commit();
                $_SESSION['flash_success'] = "Successfully imported $imported voters. " . ($errors > 0 ? "Skipped $errors duplicates." : "");
                add_to_log("Imported $imported voters to election $election_id", $admin_id, 'admin');
            } catch (\Exception $e) {
                $conn->rollBack();
                $_SESSION['flash_error'] = "Import failed: " . $e->getMessage();
            }

            fclose($handle);
            redirect(PROOT . 'admin/voters');
        }

        // GET: Show form
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 ORDER BY id DESC");
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE status = 0 AND organizer_id = ? ORDER BY id DESC");
            $stmt->execute([$admin_id]);
        }
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/voters/import.twig', [
            'elections' => $elections
        ]);
    }

    public function voterDuplicates() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        $election_id = $_GET['election_uuid'] ?? null;
        
        if ($role === 'super_admin') {
            $e_stmt = $conn->prepare("SELECT * FROM election ORDER BY id DESC");
            $e_stmt->execute();
            
            $query = "
                SELECT v.*, e.title, e.organized_by 
                FROM voters v
                INNER JOIN election e ON v.election_uuid = e.uuid
                WHERE v.email IN (
                    SELECT email FROM voters " . ($election_id ? "WHERE election_uuid = ?" : "") . " GROUP BY email HAVING COUNT(*) > 1
                )
                " . ($election_id ? "AND v.election_uuid = ?" : "") . "
                ORDER BY v.email ASC
            ";
            $stmt = $conn->prepare($query);
            if ($election_id) {
                $stmt->execute([$election_id, $election_id]);
            } else {
                $stmt->execute();
            }
        } else {
            $e_stmt = $conn->prepare("SELECT * FROM election WHERE organizer_id = ? ORDER BY id DESC");
            $e_stmt->execute([$admin_id]);

            $query = "
                SELECT v.*, e.title, e.organized_by 
                FROM voters v
                INNER JOIN election e ON v.election_uuid = e.uuid
                WHERE e.organizer_id = ? AND v.email IN (
                    SELECT email FROM voters WHERE " . ($election_id ? "election_uuid = ?" : "1=1") . " GROUP BY email HAVING COUNT(*) > 1
                )
                " . ($election_id ? "AND v.election_uuid = ?" : "") . "
                ORDER BY v.email ASC
            ";
            $stmt = $conn->prepare($query);
            if ($election_id) {
                $stmt->execute([$admin_id, $election_id, $election_id]);
            } else {
                $stmt->execute([$admin_id]);
            }
        }
        $duplicates = $stmt->fetchAll();
        $elections = $e_stmt->fetchAll();

        echo $this->twig->render('admin/voters/duplicates.twig', [
            'voters' => $duplicates,
            'elections' => $elections,
            'selected_election' => $election_id
        ]);
    }

    public function reports($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ?");
            $stmt->execute([$election_id]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND organizer_id = ?");
            $stmt->execute([$election_id, $admin_id]);
        }
        $election = $stmt->fetch();
        
        if (!$election || !in_array($election['status'], [1, 2])) {
            $_SESSION['flash_error'] = "Election not found or not active/ended.";
            redirect(PROOT . 'admin');
        }

        echo $this->twig->render('admin/reports/live.twig', [
            'election' => $election
        ]);
    }

    public function getReportData($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        
        // 1. Overall stats
        $stmt = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE election_uuid = ?");
        $stmt->execute([$election_id]);
        $total_votes_cast = $stmt->fetchColumn();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM voters WHERE election_uuid = ?");
        $stmt->execute([$election_id]);
        $total_voters = $stmt->fetchColumn();

        // 2. Positions and Contestants
        $stmt = $conn->prepare("SELECT * FROM positions WHERE election_uuid = ? ORDER BY position_id ASC");
        $stmt->execute([$election_id]);
        $positions = $stmt->fetchAll();

        $results = [];
        foreach ($positions as $pos) {
            $stmt = $conn->prepare("
                SELECT c.*, IFNULL(v.votes_for, 0) as votes_for, IFNULL(v.votes_against, 0) as votes_against
                FROM contestants c
                LEFT JOIN results v ON c.uuid = v.contestant_id
                WHERE c.position_id = ? AND c.is_deleted = 'no'
                ORDER BY c.contestant_ballot_number ASC
            ");
            $stmt->execute([$pos['position_id']]);
            $contestants = $stmt->fetchAll();
            
            $results[] = [
                'position' => $pos,
                'contestants' => $contestants
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'total_votes_cast' => $total_votes_cast,
            'total_voters' => $total_voters,
            'results' => $results
        ]);
        exit;
    }

    public function endElection($election_id) {
        global $conn, $admin_id, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $role = $admin_data['role'] ?? 'organizer';
        
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("UPDATE election SET status = 2 WHERE uuid = ? AND status = 1");
            $result = $stmt->execute([$election_id]);
        } else {
            $stmt = $conn->prepare("UPDATE election SET status = 2 WHERE uuid = ? AND status = 1 AND organizer_id = ?");
            $result = $stmt->execute([$election_id, $admin_id]);
        }
        
        if ($stmt->rowCount() > 0) {
            add_to_log("Election ended: $election_id", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Election ended successfully.";
        } else {
            $_SESSION['flash_error'] = "Unable to end election. It may not belong to you or is not currently active.";
        }
        redirect(PROOT . 'admin/reports/' . $election_id);
    }

    public function downloadReport($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'];
        $role = $admin_data['role'] ?? 'organizer';
        
        // Fetch election details
        if ($role === 'super_admin') {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ?");
            $stmt->execute([$election_id]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM election WHERE uuid = ? AND organizer_id = ?");
            $stmt->execute([$election_id, $admin_id]);
        }
        $election = $stmt->fetch();
        
        if (!$election) {
            $_SESSION['flash_error'] = "Election not found.";
            redirect(PROOT . 'admin');
        }

        // Fetch Stats
        $stmt = $conn->prepare("SELECT COUNT(*) FROM voter_participation WHERE election_uuid = ?");
        $stmt->execute([$election_id]);
        $total_votes_cast = $stmt->fetchColumn();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM voters WHERE election_uuid = ?");
        $stmt->execute([$election_id]);
        $total_voters = $stmt->fetchColumn();

        // Fetch Results
        $stmt = $conn->prepare("SELECT * FROM positions WHERE election_uuid = ? ORDER BY position_id ASC");
        $stmt->execute([$election_id]);
        $positions = $stmt->fetchAll();

        $results = [];
        foreach ($positions as $pos) {
            $stmt = $conn->prepare("
                SELECT c.*, IFNULL(v.votes_for, 0) as votes_for, IFNULL(v.votes_against, 0) as votes_against
                FROM contestants c
                LEFT JOIN results v ON c.uuid = v.contestant_id
                WHERE c.position_id = ? AND c.is_deleted = 'no'
                ORDER BY v.votes_for DESC
            ");
            $stmt->execute([$pos['position_id']]);
            $contestants = $stmt->fetchAll();
            
            $results[] = [
                'position' => $pos,
                'contestants' => $contestants
            ];
        }

        // Generate PDF
        $html = $this->twig->render('admin/reports/pdf.twig', [
            'election' => $election,
            'total_votes_cast' => $total_votes_cast,
            'total_voters' => $total_voters,
            'results' => $results,
            'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] . '/Kokuromotie'
        ]);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = php_url_slug($election['title']) . "-report.pdf";
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }

    public function settings() {
        global $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        echo $this->twig->render('admin/settings.twig', [
            'admin' => $admin_data
        ]);
    }

    public function profileUpdate() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/settings');
        }

        $fname = sanitize($_POST['fname']);
        $lname = sanitize($_POST['lname']);
        $email = sanitize($_POST['email']);

        if (empty($fname) || empty($lname) || empty($email)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/settings');
        }

        $stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ? WHERE uuid = ?");
        if ($stmt->execute([$fname, $lname, $email, $admin_id])) {
            add_to_log("Profile updated", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Profile updated successfully.";
        }

        redirect(PROOT . 'admin/settings');
    }

    public function passwordUpdate() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/settings');
        }

        $old = $_POST['old_password'];
        $new = $_POST['password'];
        $confirm = $_POST['confirm'];

        if (empty($old) || empty($new) || empty($confirm)) {
            $_SESSION['flash_error'] = "All password fields are required.";
            redirect(PROOT . 'admin/settings');
        }

        if ($new !== $confirm) {
            $_SESSION['flash_error'] = "New passwords do not match.";
            redirect(PROOT . 'admin/settings');
        }

        if (!password_verify($old, $admin_data['password'])) {
            $_SESSION['flash_error'] = "Incorrect old password.";
            redirect(PROOT . 'admin/settings');
        }

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE uuid = ?");
        if ($stmt->execute([$hashed, $admin_id])) {
            add_to_log("Password changed", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Password updated successfully.";
        }

        redirect(PROOT . 'admin/settings');
    }
    public function organizers() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        if (($admin_data['role'] ?? 'organizer') !== 'super_admin') {
            $_SESSION['flash_error'] = "Access Denied.";
            redirect(PROOT . 'admin');
        }

        $stmt = $conn->prepare("SELECT * FROM admins WHERE role = 'organizer' ORDER BY id DESC");
        $stmt->execute();
        $organizers = $stmt->fetchAll();

        echo $this->twig->render('admin/organizers.twig', [
            'organizers' => $organizers
        ]);
    }

    public function organizerStore() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        $admin_id = $admin_data['uuid'] ?? null;
        if (($admin_data['role'] ?? 'organizer') !== 'super_admin') {
            $_SESSION['flash_error'] = "Access Denied.";
            redirect(PROOT . 'admin');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/organizers');
        }

        $fname = sanitize($_POST['fname']);
        $lname = sanitize($_POST['lname']);
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['password']);

        if (empty($fname) || empty($email) || empty($password)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/organizers');
        }

        $new_id = guidv4();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO admins (uuid, first_name, last_name, email, password, role, trash) VALUES (?, ?, ?, ?, ?, 'organizer', 0)");
        if ($stmt->execute([$new_id, $fname, $lname, $email, $hashed])) {
            add_to_log("Created Organizer: $email", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Organizer account created.";
        } else {
            $_SESSION['flash_error'] = "Failed to create organizer.";
        }

        redirect(PROOT . 'admin/organizers');
    }

    public function exportVoterParticipation($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        
        $stmt = $conn->prepare("
            SELECT v.first_name, v.last_name, v.email, v.voter_id, 
                   IF(vp.status = 1, 'Voted', 'Not Voted') as participation_status,
                   vp.voted_at as voting_time
            FROM voters v
            LEFT JOIN voter_participation vp ON v.uuid = vp.voter_id AND vp.election_uuid = ?
            WHERE v.election_uuid = ?
            ORDER BY v.last_name ASC
        ");
        $stmt->execute([$election_id, $election_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "voter-participation-" . $election_id . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['First Name', 'Last Name', 'Email', 'Voter ID', 'Status', 'Voting Time']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    public function exportSecurityLogs($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        
        $stmt = $conn->prepare("
            SELECT v.first_name, v.last_name, v.email, 
                   vsl.location, vsl.login_at, vsl.logout_at,
                   IF(vsl.voter_login_details_status = 1, 'Active', 'Closed') as session_status
            FROM voter_security_logs vsl
            JOIN voters v ON vsl.voter_id = v.uuid
            WHERE v.election_uuid = ?
            ORDER BY vsl.login_at DESC
        ");
        $stmt->execute([$election_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "security-audit-logs-" . $election_id . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['First Name', 'Last Name', 'Email', 'Location/IP', 'Login Time', 'Logout Time', 'Session Status']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    public function exportBallots($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }
        
        $stmt = $conn->prepare("
            SELECT v.first_name as voter_fname, v.last_name as voter_lname, 
                   p.position_name, 
                   vf.candidate_id, vf.voted_datetime, vf.voted_location, vf.voted_ip
            FROM voted_for vf
            JOIN voters v ON vf.voter_id = v.uuid
            JOIN positions p ON vf.position_id = p.position_id
            WHERE vf.election_uuid = ?
            ORDER BY vf.voted_datetime DESC
        ");
        $stmt->execute([$election_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "detailed-ballots-" . $election_id . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Voter First Name', 'Voter Last Name', 'Position', 'Selection/ID', 'Time', 'Location', 'IP Address']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    public function exportVoterLinks($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        // Check if election allows direct links
        $eStmt = $conn->prepare("SELECT title, allow_direct_link FROM election WHERE uuid = ?");
        $eStmt->execute([$election_id]);
        $election = $eStmt->fetch();

        if (!$election) {
            $_SESSION['flash_error'] = "Intelligence Error: Session not found.";
            redirect(PROOT . 'admin/voters');
        }

        if (!$election['allow_direct_link']) {
            $_SESSION['flash_error'] = "Security Violation: Direct Link authentication is disabled for '{$election['title']}'. Export blocked.";
            redirect(PROOT . 'admin/voters');
        }
        
        $stmt = $conn->prepare("
            SELECT v.first_name, v.last_name, v.voter_id, v.phone, v.email, v.voting_token, e.title
            FROM voters v
            INNER JOIN election e ON v.election_uuid = e.uuid
            WHERE v.election_uuid = ?
            ORDER BY v.last_name ASC
        ");
        $stmt->execute([$election_id]);
        $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($voters)) {
            $_SESSION['flash_error'] = "No voters found for this election.";
            redirect(PROOT . 'admin/voters');
        }

        $filename = "voter-links-" . $election_id . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['First Name', 'Last Name', 'Voter ID', 'Phone', 'Email', 'Election', 'Direct Voting Link']);
        
        // Build base URL for voting
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $baseUrl = $protocol . "://$_SERVER[HTTP_HOST]" . PROOT . "v/";

        foreach ($voters as $row) {
            $link = $baseUrl . $row['voting_token'];
            fputcsv($output, [
                $row['first_name'],
                $row['last_name'],
                $row['voter_id'],
                $row['phone'],
                $row['email'],
                $row['title'],
                $link
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportVoterCredentials($election_id) {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        // Check if election allows PIN login
        $eStmt = $conn->prepare("SELECT title, allow_pin_login FROM election WHERE uuid = ?");
        $eStmt->execute([$election_id]);
        $election = $eStmt->fetch();

        if (!$election) {
            $_SESSION['flash_error'] = "Intelligence Error: Session not found.";
            redirect(PROOT . 'admin/voters');
        }

        if (!$election['allow_pin_login']) {
            $_SESSION['flash_error'] = "Configuration Error: PIN authentication is disabled for '{$election['title']}'. Export blocked.";
            redirect(PROOT . 'admin/voters');
        }
        
        $stmt = $conn->prepare("
            SELECT v.first_name, v.last_name, v.voter_id, v.pin_code, e.title
            FROM voters v
            INNER JOIN election e ON v.election_uuid = e.uuid
            WHERE v.election_uuid = ?
            ORDER BY v.last_name ASC
        ");
        $stmt->execute([$election_id]);
        $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($voters)) {
            $_SESSION['flash_error'] = "No voters found for this election.";
            redirect(PROOT . 'admin/voters');
        }

        $filename = "offline-credentials-" . $election_id . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['First Name', 'Last Name', 'Voter ID', 'Access PIN / Password', 'Election']);
        
        foreach ($voters as $row) {
            fputcsv($output, [
                $row['first_name'],
                $row['last_name'],
                $row['voter_id'],
                $row['pin_code'] ?: 'N/A (Reset Required)',
                $row['title']
            ]);
        }
        fclose($output);
        exit;
    }

    public function logs() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        $stmt = $conn->prepare("
            SELECT al.*, a.first_name, a.last_name, a.email as admin_email
            FROM activity_logs al
            LEFT JOIN admins a ON al.user_uuid = a.uuid
            ORDER BY al.createdAt DESC
            LIMIT 500
        ");
        $stmt->execute();
        $logs = $stmt->fetchAll();

        echo $this->twig->render('admin/logs.twig', [
            'logs' => $logs
        ]);
    }

    public function clearLogs() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || ($admin_data['role'] ?? '') !== 'super_admin') {
            $_SESSION['flash_error'] = "Access Denied.";
            redirect(PROOT . 'admin/logs');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/logs');
        }

        $scope = $_POST['clear_scope'] ?? '30_days';
        $admin_id = $admin_data['uuid'];

        if ($scope === 'all') {
            $conn->exec("TRUNCATE TABLE activity_logs");
            add_to_log("Cleared all activity logs", $admin_id, 'admin');
        } elseif ($scope === '30_days') {
            $stmt = $conn->prepare("DELETE FROM activity_logs WHERE createdAt < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stmt->execute();
            add_to_log("Cleared logs older than 30 days", $admin_id, 'admin');
        } elseif ($scope === '90_days') {
            $stmt = $conn->prepare("DELETE FROM activity_logs WHERE createdAt < DATE_SUB(NOW(), INTERVAL 90 DAY)");
            $stmt->execute();
            add_to_log("Cleared logs older than 90 days", $admin_id, 'admin');
        }

        $_SESSION['flash_success'] = "Logs cleared successfully for scope: " . str_replace('_', ' ', $scope);
        redirect(PROOT . 'admin/logs');
    }

    public function exportLogs() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        $start = $_GET['start_date'] ?? null;
        $end = $_GET['end_date'] ?? null;

        $query = "
            SELECT al.*, a.first_name, a.last_name, a.email 
            FROM activity_logs al 
            LEFT JOIN admins a ON al.user_uuid = a.uuid 
        ";
        $params = [];

        if ($start && $end) {
            $query .= " WHERE al.createdAt BETWEEN ? AND ? ";
            $params = [$start . ' 00:00:00', $end . ' 23:59:59'];
        }

        $query .= " ORDER BY al.createdAt DESC ";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "system-logs-" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Message', 'Type', 'Admin Name', 'Admin Email', 'Timestamp']);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['uuid'],
                $row['log_message'],
                $row['log_type'],
                $row['first_name'] . ' ' . $row['last_name'],
                $row['email'],
                $row['createdAt']
            ]);
        }
        fclose($output);
        exit;
    }

    public function help() {
        global $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        echo $this->twig->render('admin/help.twig');
    }

    public function disable2FA() {
        global $conn, $admin_data;
        if (!cadminIsLoggedIn() || empty($admin_data)) {
            cadminLoginErrorRedirect();
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/settings');
        }

        $admin_id = $admin_data['uuid'];
        $stmt = $conn->prepare("UPDATE admins SET google_auth_secret = NULL, is_2fa_enabled = 0 WHERE uuid = ?");
        $result = $stmt->execute([$admin_id]);

        if ($result) {
            add_to_log("Disabled Multi-Factor Authentication", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Multi-Factor Authentication has been disabled.";
        } else {
            $_SESSION['flash_error'] = "Failed to disable MFA.";
        }

        redirect(PROOT . 'admin/settings');
    }
}

