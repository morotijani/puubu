<?php 

    require_once("../connection/conn.php");

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
                                    $cadmin_id = $row['c_aid'];
                                    cAdminLoggedInID($cadmin_id);
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

    <!-- MAIN -->
    <div class="row">
        <div class="col-md-4 offset-md-4">

            <div class="card">
                <div class="card-body">
                    <a href="../index" class="btn btn-sm btn-link float-right mb-3">
                        visit site <span data-feather="cloud" class="ml-1"></span>
                    </a>
                    <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Dashboard</h4>
                    <form class="form-signin" method="POST">
                        <div class="text-center mb-4">
                            <code id="displayErrors"><?= $errorsMsg; ?></code>
                        </div>
                        <div class="form-group">
                            <input type="email" autocomplete="off" autofocus name="admin_email" id="admin_email" class="form-control form-control-sm form-control-dark" placeholder="Email" required value="<?= $email; ?>">
                        </div>
                        <div class="form-group">
                            <input type="password" name="admin_pass" id="admin_pass" class="form-control form-control-sm form-control-dark" placeholder="Password" required value="<?= $pswd; ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submitAdmin" id="submitAdmin" value="Crush it!" class="btn btn-sm btn-outline-secondary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     
    
    
    <!-- FOOTER -->
    <script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="media/files/popper-1.14.6.min.js"></script>
    <script type="text/javascript" src="media/files/bootstrap.min.js"></script>
    <script type="text/javascript" src="media/files/feather.min.js"></script>

    <script type="text/javascript">
        feather.replace();
        $("#temporary").fadeOut(5000);
    </script>

</body>
</html>
