<?php

  // CONNECTION TO DATABASE
    require_once("../connection/conn.php");
    if (!cadminIsLoggedIn()) {
        cadminLoginErrorRedirect();
    }

       // REQUIREMENT OF EXTERNAL FILES
    include ('includes/header.inc.php');
    include ('includes/top-nav.inc.php');
    include ('includes/left-nav.inc.php');

    $errors = '';
    if (isset($_POST["submit_settings"])) {
        if (empty($_POST['email']) || empty($_POST['fname']) || empty($_POST['lname'])) {
            $errors = 'Fill out all empty fileds';
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors = 'The email you provided is not valid';
        }

        if (!empty($errors)) {
            $errors;
        } else {
            $data = [
                ':cfname' => sanitize($_POST['fname']),
                ':clname' => sanitize($_POST['lname']),
                ':cemail' => sanitize($_POST['email']),
                ':c_aid' => $row['c_aid']
            ];
            $query = "
                UPDATE puubu_admin 
                SET cfname = :cfname, clname = :clname, cemail = :cemail
                WHERE c_aid = :c_aid
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute($data);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'Admin\'s profile has been <span class="bg-info">Updated</span></div>';
                echo "<script>window.location = 'settings';</script>";
            }
        }
    }


 
    if (isset($_GET['cp']) && $_GET['cp'] == 1) {

        $errors = '';
        $hashed = $row['ckey'];
        $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
        $old_password = trim($old_password);
        $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
        $password = trim($password);
        $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
        $confirm = trim($confirm);
        $new_hashed = password_hash($password, PASSWORD_BCRYPT);
        $admin_id = $row['c_aid'];

        if (isset($_POST['edit_pasword'])) {
            if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
                $errors = 'You must fill out all fields';
            } else {

                if (strlen($password) < 6) {
                    $errors = 'Password must be at least 6 characters';
                }

                if ($password != $confirm) {
                    $errors = 'The new password and confirm new password does not match.';
                }

                if (!password_verify($old_password, $hashed)) {
                    $errors = 'Your old password does not our records.';
                }
            }

            if (!empty($errors)) {
                $errors;
            } else {
                $query = '
                    UPDATE puubu_admin 
                    SET ckey = :ckey 
                    WHERE c_aid = :c_aid
                ';
                $satement = $conn->prepare($query);
                $result = $satement->execute(
                    array(
                        ':ckey' => $new_hashed,
                        ':c_aid' => $admin_id
                    )
                );
                if (isset($result)) {
                    $_SESSION['flash_success'] = 'Password successfully <span class="bg-info">UPDATED</span></div>';
                    echo "<script>window.location = 'details';</script>";
                }
            }
        }

?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="text-white" style="font-size: 18px;">Dashboard</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="settings" class="btn btn-sm btn-outline-secondary">Go back</a>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <a href="details" class="btn btn-sm btn-link float-right mb-3">
                    Profile <span data-feather="user" class="ml-1"></span>
                </a>
                <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Change Password</h4>
                <hr>
                <div class="container">
                    <form method="POST" action="settings.php?cp=1" id="edit_passwordForm">
                        <span class="text-danger lead"><?= $errors; ?></span>
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old password</label>
                            <input type="password" class="form-control form-control-sm form-control-dark" name="old_password" id="old_password" value="<?= $old_password; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input type="password" class="form-control form-control-sm form-control-dark" name="password" id="password" value="<?= $password; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm" class="form-label">Confirm new password</label>
                            <input type="password" class="form-control form-control-sm form-control-dark" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-warning" name="edit_pasword" id="edit_pasword">Change Password</button>&nbsp;
                        <a href="details" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>

        </div>

<?php } else { ?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="text-white" style="font-size: 18px;">Dashboard</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="?cp=1" class="btn btn-sm btn-outline-secondary">Change Password</a>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <a href="details" class="btn btn-sm btn-link float-right mb-3">
                Profile <span data-feather="user" class="ml-1"></span>
            </a>
            <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Update Profile</h4>
            <hr>
            <div class="container">
                <form method="POST" action="settings.php" id="settingsForm">
                    <span class="text-danger lead"><?= $errors; ?></span>
                    <div class="mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control-sm form-control form-control-dark" name="fname" id="fname" value="<?= ((isset($_POST["fname"]))?sanitize($_POST["fname"]):$row["cfname"]); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control-sm form-control form-control-dark" name="lname" id="lname" value="<?= ((isset($_POST["lname"]))?sanitize($_POST["lname"]):$row["clname"]); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control-sm form-control form-control-dark" name="email" id="email" value="<?= ((isset($_POST["email"]))?sanitize($_POST["email"]):$row["cemail"]); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark" name="submit_settings" id="submit_settings">Update</button>&nbsp;
                    <a href="details" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>

    </div>
<?php } include ('includes/main-footer.inc.php');?>

<!-- FOOTER -->
<script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="media/files/popper-1.14.6.min.js"></script>
<script type="text/javascript" src="media/files/bootstrap.min.js"></script>
<script type="text/javascript" src="media/files/feather.min.js"></script>
<script>
  feather.replace()
</script>
<script>
  $(document).ready(function() {
    $("#temporary").fadeOut(5000);
});
</script>
</body>
</html>
