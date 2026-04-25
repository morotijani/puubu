<?php
    require_once("../connection/conn.php");
    
    // Check if the admin is logged in but pending 2FA
    if (!isset($_SESSION['crAdmin']) || !isset($_SESSION['2fa_pending'])) {
        redirect(ADROOT . 'signin');
    }

    use Sonata\GoogleAuthenticator\GoogleAuthenticator;
    $errorsMsg = '';

    if (isset($_POST['verify2fa'])) {
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $errorsMsg = "Invalid request token. Please refresh and try again.";
        } else {
            $code = sanitize($_POST['otp_code']);
            $admin_id = $_SESSION['crAdmin'];

            $query = "SELECT google_auth_secret FROM puubu_admin WHERE admin_id = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$admin_id]);
            $row = $statement->fetch();

            $g = new GoogleAuthenticator();
            if ($g->checkCode($row['google_auth_secret'], $code)) {
                // Success!
                unset($_SESSION['2fa_pending']);
                $_SESSION['flash_success'] = '2FA Verified! Welcome back.';
                
                $log_message = "admin ['" . $admin_id . "'], verified 2FA!";
                add_to_log($log_message, $admin_id, 'admin');
                
                redirect(ADROOT . 'index');
            } else {
                $errorsMsg = "Invalid 2FA code. Please try again.";
            }
        }
    }

    include ("includes/header.inc.php");
?>
<body class="d-flex align-items-center" style="background: #0f172a;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-dark border-secondary shadow-lg">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-shield-lock text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="text-white mb-2">Two-Step Verification</h2>
                        <p class="text-secondary mb-4">Please enter the 6-digit code from your Google Authenticator app.</p>

                        <form method="POST">
                            <?= csrf_field(); ?>
                            <?php if(!empty($errorsMsg)): ?>
                                <div class="alert alert-danger py-2 small mb-3">
                                    <?= $errorsMsg; ?>
                                </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <input type="text" name="otp_code" class="form-control form-control-lg text-center letter-spacing-lg" 
                                       placeholder="000000" maxlength="6" autofocus required autocomplete="off"
                                       style="font-size: 2rem; font-weight: bold; background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1);">
                            </div>

                            <button type="submit" name="verify2fa" class="btn btn-warning w-100 btn-lg rounded-pill mb-3">
                                Verify & Continue
                            </button>
                            
                            <p class="small text-secondary mb-0">
                                <a href="signin" class="text-secondary text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- javascript -->
    <script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>
</body>
</html>
