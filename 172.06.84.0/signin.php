<?php 

    require_once("../connection/conn.php");
    if (cadminIsLoggedIn()) {
        redirect(PROOT . '172.06.84.0/index');
    }
    $flash_in = $flash;

    include ("includes/header.inc.php");

    $errorsMsg = '';

    $email = ((isset($_POST['admin_email']))? sanitize($_POST['admin_email']): '');
    $email = trim($email);
    $pswd = ((isset($_POST['admin_pass']))? sanitize($_POST['admin_pass']): '');
    $pswd = trim($pswd);

    if (isset($_POST['submitAdmin'])) {
        $email = sanitize($_POST['admin_email']);
        $pswd = sanitize($_POST['admin_pass']);

        if (!empty($pswd) && !empty($email)) {
          
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
              
                if (strlen($pswd) > 6) {

                    $data = array(
                    ':cAdminEmail' => $email
                    );
                    $query = "SELECT * FROM puubu_admin WHERE cemail = :cAdminEmail";
                    $statement = $conn->prepare($query);
                    $statement->execute($data);
                    $result = $statement->fetchAll();
                    $row_count = $statement->rowCount();

                    if ($row_count > 0) {
                        foreach ($result as $row) {
                            if (password_verify($pswd, $row['ckey'])) {
                                if (!empty($errorsMsg)) {
                                    $errorsMsg = 'Oops... try again';
                                } else {
                                    $admin_id = $row['admin_id'];
                                    cAdminLoggedInID($admin_id);
                                }
                            } else {
                                $errorsMsg = 'Invalid details.';
                            }
                        }
                    } else {
                        $errorsMsg = 'Invalid details.';
                    }
                } else {
                    $errorsMsg = 'Invalid details.';
                }
            } else {
                $errorsMsg = 'Invalid details';
            }
        }
    }

 ?>


    <body class="d-flex align-items-center">
        <div class="container">
            <?= $flash_in; ?>
            <div class="row justify-content-center">
                <div class="col-12" style="max-width: 25rem">
                    <!-- Heading -->
                    <h1 class="fs-1 text-center">Sign in</h1>

                    <!-- Subheading -->
                    <p class="lead text-center text-body-secondary">Access our dashboard and start tracking your tasks.</p>

                    <!-- Form -->
                    <form class="mb-5" method="POST">
                        <span class="badge badge-sm bg-danger" id="displayErrors"><?= $errorsMsg; ?></span>
                        <div class="mb-4 mt-2">
                            <label class="visually-hidden" for="email">Email Address</label>
                            <input class="form-control" autocomplete="off" autofocus id="admin_email" name="admin_email" type="email" placeholder="Enter your email address..." value="<?= $email; ?>" required />
                        </div>
                        <div class="mb-4">
                            <label class="visually-hidden" for="email">Password</label>
                            <input class="form-control" id="admin_pass" name="admin_pass" type="password" placeholder="..." value="<?= $pswd; ?>" required />
                        </div>
                        <button class="btn btn-secondary w-100" name="submitAdmin" id="submitAdmin" type="submit">Sign in</button>
                    </form>

                    <!-- Text -->
                    <p class="text-center text-body-secondary mb-0">Want to visit site? <a href="../index"> visit site</a>.</p>
                </div>
            </div>
        </div>
     
    
    
    <!-- FOOTER -->
    <script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="media/files/feather.min.js"></script>
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    <!-- Theme JS -->
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>

    <script type="text/javascript">
        feather.replace();
        $("#temporary").fadeOut(5000);
    </script>
</body>
</html>
