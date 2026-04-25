<?php
    require_once("../connection/conn.php");
    
    // Admin must be logged in to setup 2FA
    if (!cadminIsLoggedIn()) {
        cadminLoginErrorRedirect();
    }

    use Sonata\GoogleAuthenticator\GoogleAuthenticator;
    use Sonata\GoogleAuthenticator\GoogleQrUrl;

    $errorsMsg = '';
    $g = new GoogleAuthenticator();

    // Get current secret or generate new one
    $admin_id = $_SESSION['crAdmin'];
    $query = "SELECT google_auth_secret, is_2fa_enabled, cemail FROM puubu_admin WHERE admin_id = ?";
    $statement = $conn->prepare($query);
    $statement->execute([$admin_id]);
    $admin = $statement->fetch();

    $secret = $admin['google_auth_secret'];
    if (empty($secret)) {
        $secret = $g->generateSecret();
        $update = $conn->prepare("UPDATE puubu_admin SET google_auth_secret = ? WHERE admin_id = ?");
        $update->execute([$secret, $admin_id]);
    }

    $qrCodeUrl = GoogleQrUrl::generate($admin['cemail'], $secret, 'Puubu E-Voting');

    if (isset($_POST['activate2fa'])) {
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $errorsMsg = "Invalid request token.";
        } else {
            $code = sanitize($_POST['otp_code']);
            if ($g->checkCode($secret, $code)) {
                // Verified! Enable it.
                $update = $conn->prepare("UPDATE puubu_admin SET is_2fa_enabled = 1 WHERE admin_id = ?");
                $update->execute([$admin_id]);
                
                $_SESSION['flash_success'] = 'Google Authenticator 2FA has been successfully activated!';
                redirect(ADROOT . 'settings');
            } else {
                $errorsMsg = "Invalid verification code. Scan the QR code again if necessary.";
            }
        }
    }

    if (isset($_POST['disable2fa'])) {
        $update = $conn->prepare("UPDATE puubu_admin SET is_2fa_enabled = 0, google_auth_secret = NULL WHERE admin_id = ?");
        $update->execute([$admin_id]);
        $_SESSION['flash_success'] = '2FA has been disabled.';
        redirect(ADROOT . 'settings');
    }

    include ("includes/header.inc.php");
    include ('includes/top-nav.inc.php');
    include ('includes/left-nav.inc.php');
?>
    <main class="main px-lg-6">
        <div class="container-lg">
            <div class="row align-items-center mb-7">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="settings">Settings</a></li>
                            <li class="breadcrumb-item active">Two-Factor Authentication</li>
                        </ol>
                    </nav>
                    <h1 class="fs-4 mb-0">Secure Your Account</h1>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-xl-8">
                    <div class="card card-line border-warning mb-7">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <h3 class="mb-3">Google Authenticator</h3>
                                    <?php if ($admin['is_2fa_enabled'] == 0): ?>
                                        <p class="text-secondary mb-4">Protect your account with an extra layer of security. Scan the QR code with your Google Authenticator app and enter the 6-digit code below to activate.</p>
                                        
                                        <div class="mb-4">
                                            <strong>Step 1:</strong> Install Google Authenticator on your phone.<br>
                                            <strong>Step 2:</strong> Scan this QR code:
                                        </div>
                                        
                                        <div class="text-center bg-white p-3 d-inline-block rounded mb-4">
                                            <img src="<?= $qrCodeUrl; ?>" alt="QR Code" style="width: 200px;">
                                        </div>

                                        <form method="POST">
                                            <?= csrf_field(); ?>
                                            <?php if(!empty($errorsMsg)): ?>
                                                <div class="alert alert-danger py-2 mb-3"><?= $errorsMsg; ?></div>
                                            <?php endif; ?>
                                            <div class="form-group mb-3">
                                                <label class="form-label">Step 3: Enter 6-digit code</label>
                                                <input type="text" name="otp_code" class="form-control" placeholder="000 000" maxlength="6" required autocomplete="off">
                                            </div>
                                            <button type="submit" name="activate2fa" class="btn btn-warning">Activate 2FA</button>
                                        </form>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="avatar avatar-lg bg-success-subtle text-success me-3">
                                                <i class="bi bi-shield-check fs-3"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">2FA is Active</h4>
                                                <p class="text-secondary small mb-0">Your account is secured with Google Authenticator.</p>
                                            </div>
                                        </div>
                                        
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to disable 2FA? This will reduce your account security.');">
                                            <?= csrf_field(); ?>
                                            <button type="submit" name="disable2fa" class="btn btn-outline-danger btn-sm">Disable 2FA</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-5 d-none d-md-block text-center">
                                    <i class="bi bi-phone text-body-secondary" style="font-size: 10rem; opacity: 0.2;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include ('includes/footer.inc.php');?>
