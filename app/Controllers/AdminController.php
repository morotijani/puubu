<?php
namespace App\Controllers;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

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
                    $query = "SELECT * FROM puubu_admin WHERE cemail = :cAdminEmail";
                    $statement = $conn->prepare($query);
                    $statement->execute([':cAdminEmail' => $email]);
                    $row = $statement->fetch();

                    if ($row && password_verify($pswd, $row['ckey'])) {
                        cAdminLoggedInID($row['admin_id']); // This helper now handles 2FA redirect
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
        global $conn, $admin_data, $listall_election;

        // Fetch running/ended elections
        $started_election_query = "SELECT * FROM election WHERE session = ? OR session = ?";
        $statement = $conn->prepare($started_election_query);
        $statement->execute([1, 2]);
        $elections = $statement->fetchAll();

        echo $this->twig->render('admin/dashboard.twig', [
            'admin' => $admin_data,
            'elections' => $elections,
            'stats' => [
                'total_elections' => $listall_election,
                'total_contestants' => count_contestants(),
                'total_positions' => count_positions(),
                'total_voters' => count_voters()
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

                $query = "SELECT google_auth_secret FROM puubu_admin WHERE admin_id = ?";
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
            $conn->prepare("UPDATE puubu_admin SET google_auth_secret = ? WHERE admin_id = ?")
                 ->execute([$secret, $admin_data['admin_id']]);
        }

        $qrCodeUrl = GoogleQrUrl::generate($admin_data['cemail'], $secret, 'Puubu E-Voting');
        
        $errorsMsg = '';
        if (isset($_POST['activate2fa'])) {
            $code = sanitize($_POST['otp_code']);
            if ($g->checkCode($secret, $code)) {
                $conn->prepare("UPDATE puubu_admin SET is_2fa_enabled = 1 WHERE admin_id = ?")
                     ->execute([$admin_data['admin_id']]);
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
        global $conn;
        
        $stmt = $conn->prepare("SELECT * FROM election ORDER BY election_id DESC");
        $stmt->execute();
        $elections = $stmt->fetchAll();

        $edit_id = $_GET['edit'] ?? null;
        $edit_election = null;
        if ($edit_id) {
            $stmt = $conn->prepare("SELECT * FROM election WHERE election_id = ? AND session = 0");
            $stmt->execute([$edit_id]);
            $edit_election = $stmt->fetch();
        }

        echo $this->twig->render('admin/elections.twig', [
            'elections' => $elections,
            'edit_election' => $edit_election
        ]);
    }

    public function electionStore() {
        global $conn, $admin_id;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/elections');
        }

        $name = sanitize($_POST['election_name']);
        $by = sanitize($_POST['election_by']);
        $edit_id = $_POST['edit_id'] ?? null;

        if (empty($name) || empty($by)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/elections' . ($edit_id ? '?edit=' . $edit_id : ''));
        }

        if ($edit_id) {
            $stmt = $conn->prepare("UPDATE election SET election_name = ?, election_by = ? WHERE election_id = ? AND session = 0");
            $result = $stmt->execute([$name, $by, $edit_id]);
            if ($result) {
                add_to_log("Updated election: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Election updated successfully.";
            }
        } else {
            $unique_id = guidv4();
            $stmt = $conn->prepare("INSERT INTO election (election_id, election_name, election_by) VALUES (?, ?, ?)");
            $result = $stmt->execute([$unique_id, $name, $by]);
            if ($result) {
                add_to_log("Created new election: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Election created successfully.";
            }
        }

        redirect(PROOT . 'admin/elections');
    }

    public function electionDelete($id) {
        global $conn, $admin_id;
        
        $stmt = $conn->prepare("SELECT * FROM election WHERE election_id = ? AND session = 0");
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            $conn->prepare("DELETE FROM election WHERE election_id = ?")->execute([$id]);
            add_to_log("Deleted election: $id", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Election deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Election cannot be deleted (it may be active or ended).";
        }
        
        redirect(PROOT . 'admin/elections');
    }

    public function positions() {
        global $conn;
        
        // Fetch positions with election names
        $stmt = $conn->prepare("SELECT p.*, e.election_name, e.election_by, e.session FROM positions p INNER JOIN election e ON p.election_id = e.election_id ORDER BY p.position_id DESC");
        $stmt->execute();
        $positions = $stmt->fetchAll();

        // Fetch draft elections for the dropdown
        $stmt = $conn->prepare("SELECT * FROM election WHERE session = 0 ORDER BY election_name ASC");
        $stmt->execute();
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
            'edit_position' => $edit_position
        ]);
    }

    public function positionStore() {
        global $conn, $admin_id;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/positions');
        }

        $name = sanitize($_POST['position_name']);
        $election_id = sanitize($_POST['election_id']);
        $edit_id = $_POST['edit_id'] ?? null;

        if (empty($name) || empty($election_id)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/positions' . ($edit_id ? '?edit=' . $edit_id : ''));
        }

        if ($edit_id) {
            $stmt = $conn->prepare("UPDATE positions SET position_name = ?, election_id = ? WHERE position_id = ?");
            $result = $stmt->execute([$name, $election_id, $edit_id]);
            if ($result) {
                add_to_log("Updated position: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Position updated successfully.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO positions (position_id, position_name, election_id) VALUES (?, ?, ?)");
            $result = $stmt->execute([guidv4(), $name, $election_id]);
            if ($result) {
                add_to_log("Created new position: $name", $admin_id, 'admin');
                $_SESSION['flash_success'] = "Position created successfully.";
            }
        }

        redirect(PROOT . 'admin/positions');
    }

    public function positionDelete($id) {
        global $conn, $admin_id;
        
        // Security: Ensure position is for a draft election before deleting
        $stmt = $conn->prepare("SELECT p.* FROM positions p INNER JOIN election e ON p.election_id = e.election_id WHERE p.position_id = ? AND e.session = 0");
        $stmt->execute([$id]);
        
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
        global $conn;
        
        $query = "
            SELECT c.*, p.position_name, e.election_name, e.election_by, e.session 
            FROM cont_details c
            INNER JOIN positions p ON c.cont_position = p.position_id
            INNER JOIN election e ON c.contestant_election = e.election_id
            WHERE c.del_cont = 'no'
            ORDER BY c.contestant_id DESC
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $contestants = $stmt->fetchAll();

        // Fetch all elections for the filter
        $stmt = $conn->prepare("SELECT * FROM election ORDER BY election_name ASC");
        $stmt->execute();
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/index.twig', [
            'contestants' => $contestants,
            'elections' => $elections
        ]);
    }

    public function contestantForm($id = null) {
        global $conn;
        
        $contestant = null;
        $positions = [];
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM cont_details WHERE contestant_id = ?");
            $stmt->execute([$id]);
            $contestant = $stmt->fetch();
            
            if ($contestant) {
                $stmt = $conn->prepare("SELECT * FROM positions WHERE election_id = ?");
                $stmt->execute([$contestant['contestant_election']]);
                $positions = $stmt->fetchAll();
            }
        }

        $stmt = $conn->prepare("SELECT * FROM election WHERE session = 0 ORDER BY election_name ASC");
        $stmt->execute();
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/form.twig', [
            'contestant' => $contestant,
            'elections' => $elections,
            'positions' => $positions
        ]);
    }

    public function contestantStore() {
        global $conn, $admin_id;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/contestants');
        }

        $id = $_POST['contestant_id'] ?? null;
        $fname = sanitize($_POST['cont_fname']);
        $lname = sanitize($_POST['cont_lname']);
        $gender = sanitize($_POST['cont_gender']);
        $ballot_no = sanitize($_POST['contestant_ballot_number']);
        $position_id = sanitize($_POST['cont_position']);
        $election_id = sanitize($_POST['sel_election']);
        $old_profile = $_POST['old_profile'] ?? '';

        $profile_img = $old_profile;
        if (isset($_FILES['cont_profile']) && $_FILES['cont_profile']['error'] == 0) {
            $ext = pathinfo($_FILES['cont_profile']['name'], PATHINFO_EXTENSION);
            $profile_img = uniqid('', true) . '.' . $ext;
            $target = BASEURL . 'media/uploadedprofile/' . $profile_img;
            
            if (move_uploaded_file($_FILES['cont_profile']['tmp_name'], $target)) {
                if ($old_profile && file_exists(BASEURL . 'media/uploadedprofile/' . $old_profile)) {
                    unlink(BASEURL . 'media/uploadedprofile/' . $old_profile);
                }
            }
        }

        if ($id) {
            $query = "UPDATE cont_details SET cont_fname = ?, cont_lname = ?, cont_gender = ?, contestant_ballot_number = ?, cont_position = ?, contestant_election = ?, cont_profile = ? WHERE contestant_id = ?";
            $conn->prepare($query)->execute([$fname, $lname, $gender, $ballot_no, $position_id, $election_id, $profile_img, $id]);
            $_SESSION['flash_success'] = "Contestant updated successfully.";
        } else {
            $new_id = guidv4();
            $query = "INSERT INTO cont_details (contestant_id, cont_fname, cont_lname, cont_gender, contestant_ballot_number, cont_position, contestant_election, cont_profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $conn->prepare($query)->execute([$new_id, $fname, $lname, $gender, $ballot_no, $position_id, $election_id, $profile_img]);
            
            // Initialize vote counts
            $conn->prepare("INSERT INTO vote_counts (vote_count_id, results, contestant_id, position_id, election_id) VALUES (?, ?, ?, ?, ?)")
                 ->execute([guidv4(), 0, $new_id, $position_id, $election_id]);
            
            $_SESSION['flash_success'] = "Contestant added successfully.";
        }

        redirect(PROOT . 'admin/contestants');
    }

    public function getPositionsByElection($election_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT position_id, position_name FROM positions WHERE election_id = ? ORDER BY position_name ASC");
        $stmt->execute([$election_id]);
        $positions = $stmt->fetchAll();
        
        header('Content-Type: application/json');
        echo json_encode($positions);
        exit;
    }

    public function contestantArchive() {
        global $conn;
        
        $query = "
            SELECT c.*, p.position_name, e.election_name, e.election_by 
            FROM cont_details c
            INNER JOIN positions p ON c.cont_position = p.position_id
            INNER JOIN election e ON c.contestant_election = e.election_id
            WHERE c.del_cont = 'yes'
            ORDER BY c.contestant_id DESC
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $contestants = $stmt->fetchAll();

        echo $this->twig->render('admin/contestants/archive.twig', [
            'contestants' => $contestants
        ]);
    }

    public function contestantToggleDelete($id, $status) {
        global $conn;
        $conn->prepare("UPDATE cont_details SET del_cont = ? WHERE contestant_id = ?")->execute([$status, $id]);
        $_SESSION['flash_success'] = ($status == 'yes') ? "Contestant moved to archive." : "Contestant restored.";
        redirect(PROOT . 'admin/contestants' . ($status == 'no' ? '/archive' : ''));
    }

    public function voters() {
        global $conn;
        
        $query = "
            SELECT r.*, e.election_name, e.election_by, e.session 
            FROM registrars r
            INNER JOIN election e ON r.registrar_election = e.election_id
            ORDER BY r.id DESC
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $voters = $stmt->fetchAll();

        echo $this->twig->render('admin/voters/index.twig', [
            'voters' => $voters
        ]);
    }

    public function voterForm($id = null) {
        global $conn;
        
        $voter = null;
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM registrars WHERE voter_id = ?");
            $stmt->execute([$id]);
            $voter = $stmt->fetch();
        }

        $stmt = $conn->prepare("SELECT * FROM election WHERE session = 0 ORDER BY election_name ASC");
        $stmt->execute();
        $elections = $stmt->fetchAll();

        echo $this->twig->render('admin/voters/form.twig', [
            'voter' => $voter,
            'elections' => $elections
        ]);
    }

    public function voterStore() {
        global $conn, $admin_id;
        
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = "Invalid CSRF token.";
            redirect(PROOT . 'admin/voters');
        }

        $id = $_POST['voter_id'] ?? null;
        $std_id = sanitize($_POST['std_id']);
        $fname = sanitize($_POST['std_fname']);
        $lname = sanitize($_POST['std_lname']);
        $email = sanitize($_POST['std_email']);
        $election_id = sanitize($_POST['registrar_election']);

        if (empty($std_id) || empty($fname) || empty($lname) || empty($email) || empty($election_id)) {
            $_SESSION['flash_error'] = "All fields are required.";
            redirect(PROOT . 'admin/voters' . ($id ? '/edit/' . $id : '/add'));
        }

        if ($id) {
            $query = "UPDATE registrars SET std_id = ?, std_fname = ?, std_lname = ?, std_email = ?, registrar_election = ? WHERE voter_id = ?";
            $conn->prepare($query)->execute([$std_id, $fname, $lname, $email, $election_id, $id]);
            $_SESSION['flash_success'] = "Voter updated successfully.";
        } else {
            // Generate password
            $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
            $password = substr(str_shuffle($string), 0, 8);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $new_id = guidv4();
            $query = "INSERT INTO registrars (voter_id, std_id, std_password, std_fname, std_lname, std_email, registrar_election) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $conn->prepare($query)->execute([$new_id, $std_id, $hashed, $fname, $lname, $email, $election_id]);
            
            $_SESSION['flash_success'] = "Voter added successfully. Password is: $password";
        }

        redirect(PROOT . 'admin/voters');
    }

    public function voterDelete($id) {
        global $conn;
        $conn->prepare("DELETE FROM registrars WHERE voter_id = ?")->execute([$id]);
        $_SESSION['flash_success'] = "Voter deleted.";
        redirect(PROOT . 'admin/voters');
    }

    public function voterBulkDelete() {
        global $conn;
        if (isset($_POST['voter_ids']) && is_array($_POST['voter_ids'])) {
            $placeholders = implode(',', array_fill(0, count($_POST['voter_ids']), '?'));
            $conn->prepare("DELETE FROM registrars WHERE voter_id IN ($placeholders)")->execute($_POST['voter_ids']);
            $_SESSION['flash_success'] = count($_POST['voter_ids']) . " voters deleted.";
        }
        redirect(PROOT . 'admin/voters');
    }

    public function voterTruncate() {
        global $conn;
        $conn->exec("TRUNCATE TABLE registrars");
        $_SESSION['flash_success'] = "Voters table cleared.";
        redirect(PROOT . 'admin/voters');
    }

    public function voterDuplicates() {
        global $conn;
        $query = "
            SELECT r.*, e.election_name, e.election_by 
            FROM registrars r
            INNER JOIN election e ON r.registrar_election = e.election_id
            WHERE r.std_email IN (
                SELECT std_email FROM registrars GROUP BY std_email HAVING COUNT(*) > 1
            )
            ORDER BY r.std_email ASC
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $duplicates = $stmt->fetchAll();

        echo $this->twig->render('admin/voters/duplicates.twig', [
            'voters' => $duplicates
        ]);
    }

    public function reports($election_id) {
        global $conn;
        
        $stmt = $conn->prepare("SELECT * FROM election WHERE election_id = ?");
        $stmt->execute([$election_id]);
        $election = $stmt->fetch();
        
        if (!$election || !in_array($election['session'], [1, 2])) {
            $_SESSION['flash_error'] = "Election not found or not active/ended.";
            redirect(PROOT . 'admin');
        }

        echo $this->twig->render('admin/reports/live.twig', [
            'election' => $election
        ]);
    }

    public function getReportData($election_id) {
        global $conn;
        
        // 1. Overall stats
        $stmt = $conn->prepare("SELECT COUNT(*) FROM voterhasdone WHERE election_id = ?");
        $stmt->execute([$election_id]);
        $total_votes_cast = $stmt->fetchColumn();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM registrars WHERE registrar_election = ?");
        $stmt->execute([$election_id]);
        $total_voters = $stmt->fetchColumn();

        // 2. Positions and Contestants
        $stmt = $conn->prepare("SELECT * FROM positions WHERE election_id = ? ORDER BY position_id ASC");
        $stmt->execute([$election_id]);
        $positions = $stmt->fetchAll();

        $results = [];
        foreach ($positions as $pos) {
            $stmt = $conn->prepare("
                SELECT c.*, v.results, v.results_no
                FROM cont_details c
                INNER JOIN vote_counts v ON c.contestant_id = v.contestant_id
                WHERE c.cont_position = ? AND c.del_cont = 'no'
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
        global $conn, $admin_id;
        
        $stmt = $conn->prepare("UPDATE election SET session = 2 WHERE election_id = ? AND session = 1");
        if ($stmt->execute([$election_id])) {
            add_to_log("Election ended: $election_id", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Election ended successfully.";
        }
        redirect(PROOT . 'admin/reports/' . $election_id);
    }

    public function settings() {
        global $admin_data;
        echo $this->twig->render('admin/settings.twig', [
            'admin' => $admin_data
        ]);
    }

    public function profileUpdate() {
        global $conn, $admin_id;
        
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

        $stmt = $conn->prepare("UPDATE puubu_admin SET cfname = ?, clname = ?, cemail = ? WHERE admin_id = ?");
        if ($stmt->execute([$fname, $lname, $email, $admin_id])) {
            add_to_log("Profile updated", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Profile updated successfully.";
        }

        redirect(PROOT . 'admin/settings');
    }

    public function passwordUpdate() {
        global $conn, $admin_id, $admin_data;
        
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

        if (!password_verify($old, $admin_data['ckey'])) {
            $_SESSION['flash_error'] = "Incorrect old password.";
            redirect(PROOT . 'admin/settings');
        }

        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE puubu_admin SET ckey = ? WHERE admin_id = ?");
        if ($stmt->execute([$hashed, $admin_id])) {
            add_to_log("Password changed", $admin_id, 'admin');
            $_SESSION['flash_success'] = "Password updated successfully.";
        }

        redirect(PROOT . 'admin/settings');
    }
}
