<?php
/**
 * Kokuromotie Micro-Framework Entry Point
 */

require_once __DIR__ . '/../connection/conn.php';

// Create Router instance
$router = new \Bramus\Router\Router();
$router->setBasePath(PROOT);

// Twig Setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/../connection/cache',
    'debug' => true,
]);

// Register Global Functions
$twig->addFunction(new \Twig\TwigFunction('csrf_token', function() {
    return csrf_token();
}));

$twig->addFunction(new \Twig\TwigFunction('csrf_field', function() {
    return csrf_field();
}));

// Global Twig Variables
$twig->addGlobal('PROOT', PROOT);
$twig->addGlobal('flash', $flash);
$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('ADMIN_TOKEN', ADMIN_ACCESS_TOKEN);
if (isset($admin_data)) $twig->addGlobal('admin', $admin_data);

// Define Routes
$router->get('/', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->home();
});

$router->all('/signin', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->login();
});

$router->post('/auth/check-id', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->checkVoterId();
});

$router->post('/auth/send-otp', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->sendOtp();
});

$router->post('/auth/verify-otp', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->verifyOtp();
});

$router->get('/v/([a-zA-Z0-9\-]+)', function($token) use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->directLogin($token);
});

$router->get('/auth/logout', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->logout();
});

$router->get('/votingon', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->dashboard();
});

$router->get('/startvote', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->ballot();
});

$router->post('/submitvote', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->submitVote();
});

$router->get('/success', function() use ($twig) {
    require_once __DIR__ . '/../app/Controllers/VoterController.php';
    $controller = new \App\Controllers\VoterController($twig);
    $controller->success();
});

// Admin Routes
$router->mount('/admin', function() use ($router, $twig) {
    
    // Admin URL Secret Token Gatekeeper
    $router->before('GET|POST', '.*', function() {
        // If we haven't passed the gate yet, check for the token
        if (!isset($_SESSION['admin_gate_passed']) || $_SESSION['admin_gate_passed'] !== true) {
            if (isset($_GET['token']) && $_GET['token'] === ADMIN_ACCESS_TOKEN) {
                $_SESSION['admin_gate_passed'] = true;
                // Strip the token from the URL for a cleaner look
                $cleanUrl = strtok($_SERVER['REQUEST_URI'], '?');
                redirect($cleanUrl);
            } else {
                header('HTTP/1.1 403 Forbidden');
                die('Access Restricted: Admin secret token required.');
            }
        }
    });

    $router->all('/signin', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->login();
    });

    $router->all('/verify-2fa', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->verify2fa();
    });

    $router->get('/auth/logout', function() {
        $gate = $_SESSION['admin_gate_passed'] ?? false;
        session_unset();
        session_destroy();
        session_start();
        if ($gate) {
            $_SESSION['admin_gate_passed'] = $gate;
        }
        redirect(PROOT . 'admin/signin');
    });

    // Protected Admin Routes (require login)
    $router->before('GET|POST', '^/?(?!signin|verify-2fa).*', function() {
        if (!cadminIsLoggedIn()) {
            cadminLoginErrorRedirect();
        }
    });

    $router->get('/', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->index();
    });

    $router->all('/setup-2fa', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->setup2fa();
    });

    // Election Management
    $router->get('/elections', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->elections();
    });

    $router->post('/elections/store', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->electionStore();
    });

    $router->get('/elections/delete/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->electionDelete($id);
    });

    $router->post('/elections/start/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->startElection($id);
    });

    $router->post('/elections/stop/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->stopElection($id);
    });

    // Position Management
    $router->get('/positions', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->positions();
    });

    $router->post('/positions/store', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->positionStore();
    });

    $router->get('/positions/delete/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->positionDelete($id);
    });

    // Contestant Management
    $router->get('/contestants', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestants();
    });

    $router->get('/contestants/add', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestantForm();
    });

    $router->get('/contestants/edit/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestantForm($id);
    });

    $router->post('/contestants/store', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestantStore();
    });

    $router->get('/contestants/archive', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestantArchive();
    });

    $router->get('/contestants/toggle-delete/([a-zA-Z0-9\-]+)/(\w+)', function($id, $status) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->contestantToggleDelete($id, $status);
    });

    $router->get('/api/positions/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->getPositionsByElection($id);
    });

    $router->get('/api/election-login-settings/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->getElectionLoginSettings($id);
    });

    // Voter Management
    $router->get('/voters', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voters();
    });

    $router->get('/voters/add', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterForm();
    });

    $router->get('/voters/edit/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterForm($id);
    });

    $router->post('/voters/store', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterStore();
    });

    $router->get('/voters/delete/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterDelete($id);
    });

    $router->post('/voters/bulk-delete', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterBulkDelete();
    });

    $router->get('/voters/truncate', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterTruncate();
    });

    $router->post('/voters/truncate', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterTruncate();
    });

    $router->get('/voters/import', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterImport();
    });

    $router->post('/voters/import', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterImport();
    });

    $router->get('/voters/duplicates', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->voterDuplicates();
    });

    // Reports
    $router->get('/reports/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->reports($id);
    });

    $router->get('/reports/download/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->downloadReport($id);
    });

    $router->get('/api/reports/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->getReportData($id);
    });

    $router->get('/reports/export/voters/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->exportVoterParticipation($id);
    });

    $router->get('/reports/export/logs/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->exportSecurityLogs($id);
    });

    $router->get('/reports/export/ballots/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->exportBallots($id);
    });

    $router->get('/election/end/([a-zA-Z0-9\-]+)', function($id) use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->endElection($id);
    });

    // Organizers
    $router->get('/organizers', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->organizers();
    });

    $router->post('/organizers/store', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->organizerStore();
    });

    // Settings
    $router->get('/settings', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->settings();
    });

    $router->post('/settings/profile', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->profileUpdate();
    });

    $router->post('/settings/password', function() use ($twig) {
        require_once __DIR__ . '/../app/Controllers/AdminController.php';
        $controller = new \App\Controllers\AdminController($twig);
        $controller->passwordUpdate();
    });
});

// Run it!
$router->run();

// Clear flash messages after execution
if (isset($_SESSION['flash_success'])) unset($_SESSION['flash_success']);
if (isset($_SESSION['flash_error'])) unset($_SESSION['flash_error']);

