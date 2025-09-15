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
    $message = '';

    $voter_fname = '';
    $voter_lname = '';
    $voter_identity = '';
    $voter_email = '';
    $sel_election = '';
    $sel_election = ((isset($_POST['sel_election']) && !empty($_POST['sel_election']))?sanitize($_POST['sel_election']):'');
    
    // $voter_fname = ((isset($_POST['voter_fname']) != '')?$_POST["voter_fname"]:'');
    // $voter_lname = ((isset($_POST['voter_lname']) != '')?$_POST["voter_lname"]:'');
    // $voter_identity = ((isset($_POST['voter_identity']) != '')?$_POST["voter_identity"]:'');
    
    // FETCH ELECTIONS THAT HAS NOT YET BEEN STATED
    $query = "SELECT * FROM election WHERE session = ? ORDER BY election_id DESC";
    $statement = $conn->prepare($query);
    $statement->execute([0]);
    $election_result = $statement->fetchAll();


    // GET VOTER FOR EDITING
    if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
        $editid = sanitize($_GET['editvoter']);

        $findVoter = $conn->query("SELECT * FROM registrars INNER JOIN election ON election.election_id = registrars.registrar_election WHERE registrars.voter_id = '" . $editid . "' AND election.session = 0")->rowCount();
        if ($findVoter > 0) {
            foreach ($conn->query("SELECT * FROM registrars WHERE id = '".$editid."'")->fetchAll() as $row) {
                $voter_fname = ((isset($row['std_fname']) != '') ? $row["std_fname"] : $_POST["voter_fname"]);
                $voter_lname = ((isset($row['std_lname']) != '') ? $row["std_lname"] : $_POST["voter_lname"]);
                $voter_identity = ((isset($row['std_id']) != '') ? $row["std_id"] : $_POST["voter_identity"]);
                $voter_email = ((isset($row['std_email']) != '') ? $row["std_email"] : $_POST["voter_email"]);
                $sel_election = ((isset($row['registrar_election']) != '')?$row["registrar_election"] : $_POST["voter_election_type"]);
            }
        } else {
            $log_message = "voter ['" . $editid . "'], selected to be edited, but did not exist!";
            add_to_log($log_message, $admin_id, 'admin');

            $_SESSION['flash_error'] = 'Voter cannot be found!';
            redirect(ADROOT . 'registrar');
        }

    }

    // DELETE VOTER
    if (isset($_GET['deletevoter']) && !empty($_GET['deletevoter'])) {
        $deleteid = $_GET['deletevoter'];

        $findVoter = $conn->query("SELECT * FROM registrars INNER JOIN election ON election.election_id = registrars.registrar_election WHERE registrars.voter_id = '".$deleteid."' AND election.session = 0")->rowCount();
        if ($findVoter > 0) {
            // $deleteQuery = "DELETE FROM registrars WHERE id = '".$deleteid."'";
            // $statement = $conn->prepare($deleteQuery);
            // $statement->execute();
            if ($conn->query("DELETE FROM registrars WHERE voter_id = '".$deleteid."'")) {
                $log_message = "voter ['" . $deleteid . "'], deleted!";
                add_to_log($log_message, $admin_id, 'admin');

                $_SESSION['flash_success'] = 'Registrar Has Been Deleted Successfully';
                echo "<script>window.location = 'registrar';</script>";
            }
        } else {
            $log_message = "voter ['" . $deleteid . "'], selected to be deleted, but did not exist!";
            add_to_log($log_message, $admin_id, 'admin');

            $_SESSION['flash_error'] = 'Voter cannot be found!';
            redirect(ADROOT . 'registrar');
        }
    }


    // MUTIPLE DELETE VOTERS
    if (isset($_POST['checkbox_value'])) {
        for ($i = 0; $i < count($_POST['checkbox_value']); $i++) { 
            $query = "DELETE FROM registrars WHERE voter_id = '".$_POST['checkbox_value'][$i]."'";
            $statement = $conn->prepare($query);
            $statement->execute();

            $log_message = count($_POST['checkbox_value']) . " multiple voters, deleted!";
            add_to_log($log_message, $admin_id, 'admin');
        }
    }

    // TRUNCATE VOTERS TABLE
    if (isset($_POST['dataValue'])) {
        if ($_POST['dataValue'] == 'emptyVotersTable') {
            $query = "TRUNCATE `puubu`.`registrars`";
            $statement = $conn->prepare($query);
            $statement->execute();
            
            $log_message = "voters table, emptyed!";
            add_to_log($log_message, $admin_id, 'admin');
        }
    }

    // ADD A NEW VOTER
    if (isset($_POST['submitVoters'])) {

        for ($i = 0; $i < $_POST['total_fields']; $i++) {

            $query = "SELECT * FROM registrars WHERE std_id = '".$_POST['voter_identity'][$i]."'";
            if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
                $query = "SELECT * FROM registrars WHERE std_id = '".$_POST['voter_identity'][$i]."' AND id != '".$_GET['editvoter']."'";
            }
            $statement = $conn->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();

            if ($count > 0) {
                $message = '<div class="alert alert-danger" id="temporary">This Voter Already Exists</div>';
            } else {

                if ($message == '') {

              // str_shuffle() RANDOMLY SHUFFLE ALL CHARACTERS OF A STRING (SIMPLY MEANS IT RANDOMLY REARRANGE STRING CHARACTERS) AND
              // substr() RETURNS A PART OF THAT STRING
              //$string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
              $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
              $generatedpassword = substr(str_shuffle($string), 0, 8);

              if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
                $update = "UPDATE registrars SET std_id = '".$_POST['voter_identity'][$i]."', std_fname = '".$_POST['voter_fname'][$i]."', std_lname = '".$_POST['voter_lname'][$i]."', std_email = '".$_POST['voter_email'][$i]."', registrar_election = '".$_POST['voter_election_type'][$i]."' WHERE id = '".$_GET['editvoter']."'";
                $statement = $conn->prepare($update);
                $statement->execute();
                $_SESSION['flash_success'] = 'The Voter Has Been Successfully Updated';
                echo "<script>window.location = 'registrar';</script>";
              } else {
                $query = "INSERT INTO registrars (std_id, std_password, std_fname, std_lname, std_email, registrar_election) VALUES ('".$_POST['voter_identity'][$i]."', '".$generatedpassword."', '".$_POST['voter_fname'][$i]."', '".$_POST['voter_lname'][$i]."', '".$_POST['voter_email'][$i]."', '".$_POST['voter_election_type'][$i]."')";
                $statement = $conn->prepare($query);
                $result = $statement->execute();
                if (isset($result)) {

                  // function smtpmailer($to, $from, $from_name, $subject, $body) {
                  //   $mail = new PHPMailer();
                  //   $mail->IsSMTP();
                  //   $mail->SMTPAuth = true; 
                     
                  //   $mail->SMTPSecure = 'ssl'; 
                  //   $mail->Host = 'smtp.namibra.com';
                  //   $mail->Port = 465;  
                  //   $mail->Username = 'castright@namibra.com';
                  //   $mail->Password = 'Um9f985c2'; 
                       
                  //   $mail->IsHTML(true);
                  //   $mail->From="castright@namibra.com";
                  //   $mail->FromName=$from_name;
                  //   $mail->Sender=$from;
                  //   $mail->AddReplyTo($from, $from_name);
                  //   $mail->Subject = $subject;
                  //   $mail->Body = $body;
                  //   $mail->AddAddress($to);
                  //   if (!$mail->Send()) {
                  //       $error ="Please try Later, Error Occured while Processing...";
                  //       return $error; 
                  //   } else {
                  //     $error = "Thanks You !! Your email is sent.";  
                  //     return $error;
                  //   }
                  // }
                        
                  // $to   = $_POST['voter_email'][$i];
                  // $from = 'castright@namibra.com';
                  // $name = 'Castright ~ Namibra, Inc';
                  // $subj = 'Your password for Voting';
                  // $msg = '<p>This is you password for voting. <b>'.$generatedpassword.'</b></p><br><p>Visit this link to vote <a href="evoting.namibra.com">castRight</a></p>';
                        
                  // $error=smtpmailer($to[$i],$from, $name ,$subj, $msg);
                  // $_SESSION['flash_success'] = $error;

                  $_SESSION['flash_success'] = ''.$_POST['total_fields'].' Voter(s) Successfully Added';
                  echo "<script>window.location = 'registrar';</script>";
                }
              }
            }

          }
        
      }
    
  }



// FIND DULICATED EMAILS
  if (isset($_GET['fde']) && !empty($_GET['fde'])) {
      $query = "
        SELECT *
        FROM registrars 
        INNER JOIN election
        ON election.election_id = registrars.registrar_election 
        WHERE registrars.std_email 
        IN (
                SELECT registrars.std_email 
                FROM registrars 
                GROUP BY registrars.std_email 
                HAVING COUNT(*) > 1
        );
      ";
      $statement = $conn->prepare($query);
      $statement->execute();
      $result_fde = $statement->fetchAll();
      $fde_count = $statement->rowCount();
  }



?>
<style>
.tr-bg-danger {
    /* background-color: red !Important; */
    border-color: red;
}
</style>

    <!-- Main -->
    <main class="main px-lg-6">
        <!-- Content -->
        <div class="container-lg">
            <!-- Page header -->
            <div class="row align-items-center mb-7">
                <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl rounded text-primary">
                        <i class="fs-2" data-duoicon="app"></i>
                    </div>
                </div>
                <div class="col">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Voters</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Voters</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Voters</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">

                    <div class="row gx-2">
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-light w-100" href="<?= PROOT; ?>172.06.84.0/registrar?addnewvoter=1"><span class="material-symbols-outlined me-1">add</span> Add</a>
                        </div>
                        <div class="col-6 col-sm-auto">
                            <a href="javascript:;" class="btn btn-danger w-100" data-toggle="modal" data-target="#registrarsModal">Other options</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="row">
                <div class="col-12">
                    <!-- Filters -->
                    <div class="card card-line bg-body-tertiary border-transparent mb-7">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="text-body-secondary">No voters selected</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="row gx-3  ">
                                        <div class="col col-lg-auto ms-auto">
                                            <div class="input-group bg-body">
                                                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" />
                                                <span class="input-group-text" id="search">
                                                    <span class="material-symbols-outlined">search</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <a class="btn btn-dark px-3" href="<?= ADROOT; ?>registrar">
                                                Refresh
                                            </a>
                                        </div>

                                        <div class="col-auto ms-n2">
                                            <a class="btn btn-dark px-3" href="<?= goBack(); ?>">
                                                Go back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div>

    <?php if(isset($_GET['addnewvoter']) || isset($_GET['editvoter']) && !empty($_GET['editvoter'])): ?>
    <!-- MAIN -->
    <div class="card">
        <div class="card-body">
            <h4 class="mt-2"><?= ((isset($_GET['editvoter'])? 'Edit' : 'Add new')); ?> voter</h4>
            <form action="?<?= ((isset($_GET['editvoter']))?'editvoter='.$editid:'addnewvoter=1') ?>" method="post" id="AddVoter">
                <span id="errorMsg"><?= $message; ?></span>
                <div id="dynamic_field">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="">Voter Identity No:</label>
                            <input type="text" name="voter_identity[]" id="voter_identity1" placeholder="Student ID" class="form-control voter_details" value="<?= $voter_identity; ?>" required autofocus>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="">Voter Email:</label>
                            <input type="email" name="voter_email[]" id="voter_email1" placeholder="Student Email" class="form-control voter_details" value="<?= $voter_email; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="">Voter First Name:</label>
                            <input type="text" name="voter_fname[]" id="voter_fname1" placeholder="First Name" class="form-control voter_details" value="<?= $voter_fname; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="">Voter Last Name:</label>
                            <input type="text" name="voter_lname[]" id="voter_lname1" placeholder="Last Name" class="form-control voter_details" value="<?= $voter_lname; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="">Election Type</label>
                            <select class="form-control voter_details" id="voter_election_type1" name="voter_election_type[]" required>
                                <option value=""> -- Select election type for voter -- </option>
                                <?php foreach ($election_result as $election_row): ?>
                                <option value="<?= $election_row['election_id']; ?>"<?= (($sel_election == $election_row['election_id'])?' selected' : '');?>><?= ucwords($election_row['election_name']); ?> / <?= ucwords($election_row['election_by']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <input type="hidden" name="total_fields" id="total_fields" value="1">
                    <button type="submit" name="submitVoters" id="submitVoters" class="btn btn-outline-warning"><?= ((isset($_GET['editvoter'])?'Edit':'Add')); ?> Now!</button>
                    <a href="registrar" class="btn btn-danger">Cancel</a>
                    <?php if (isset($_GET['addnewvoter'])): ?>
                        <button type="button" name="add" id="add" class="btn btn-dark float-right">Add New Field</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
      
  <?php elseif (isset($_GET['ricsv'])): ?>
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Import CSV file data</h4>
            <span id="csv_message"></span>
            <form method="POST" action="" id="uploadRCSV" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Select CSV File</label>
                    <input type="file" name="csvfile" id="csvfile" class="form-control">
                    <small class="text-warning">Student ID, Password(Automatically generated), First name, Last name, Email, Election type.</small>
                </div>
                <div class="mb-3">
                    <input type="hidden" name="csv_hidden_field" value="1">
                    <button type="submit" name="importCSV" class="btn btn-danger" id="importCSV">Import</button>
                </div>
            </form>
            <div class="mb-3" id="process" style="display: none;">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: flow-root;">
                        <span id="process_data">0</span>
                        - <span id="total_csv_data">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <!-- LIST REGISTRARS -->
    <div class="card">
        <div class="card-body">

            <div class="row align-items-center">
                <div class="col-auto">
                    <a href="javascript:;" class="text-info">
                        <span class="material-symbols-outlined me-1">cloud_download</span> Export
                    </a>
                </div>
                <div class="col">
                    <input type="text" name="searchR" id="searchR" class="form-control" placeholder="Search for registrar here ...">
                </div>
                <div class="col-auto">
                    <a href="?fde=1" class="text-danger">
                        <span class="material-symbols-outlined me-1">notification_multiple</span> Find Duplicated Emails
                    </a>
                </div>
            </div>
            
      <?php if (isset($_GET['fde']) && !empty($_GET['fde'])): ?>
            <h4 class="mt-2">List of duplicated users</h4>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identity Number</th>
                            <th>Full Name</th>
                            <th>Election Type</th>
                            <th>
                                <span id="delete_checkedDisplay" style="display: none;">
                                    <button type="button" name="delete_checked" id="delete_checked" class="btn btn-sm btn-danger">delete All</button> <label>Select All <input type="checkbox" id="selectAll"></label>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($fde_count > 0): ?>
                        <?php $i = 1; foreach ($result_fde as $fde_row): ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-4">
                                            <div><?= ucwords(strtolower($fde_row["std_fname"].' '.$fde_row["std_lname"])); ?></div>
                                            <div class="fs-sm text-body-secondary">
                                                <span class="text-reset"><?= $fde_row["std_email"]; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= strtoupper($fde_row["std_id"]); ?>
                                    <span class="badge bg-<?= (($fde_row['status'] == '1') ? 'success' : 'danger') ; ?>">
                                        <span class="material-symbols-outlined"><?= (($fde_row['status'] == '1')?'done_all' : 'close'); ?></span>
                                    </span>
                                </td>
                                <td>
                                    <?= ucwords($fde_row['election_name']); ?> ~ <span class="text-muted"><?= ucwords($fde_row['election_by']); ?></spn>
                                </td>
                                <td>
                                    <a href="?deletevoter=<?= $fde_row["id"]; ?>" class="btn btn-sm btn-primary">
                                        <span class="material-symbols-outlined me-1">delete</span> Delete
                                    </a>&nbsp;
                                    <a href="?editvoter=<?= $fde_row["id"]; ?>" class="btn btn-smbtn-danger">
                                        <span class="material-symbols-outlined me-1">stylus_note</span> Edit
                                    </a>
                                    <input type="checkbox" class="checkToDelete form-check-input" value="<?= $fde_row["id"]; ?>" style="display: none;">
                                </td>
                            </tr>
                        <?php $i++; endforeach; ?>  
                    <?php else: ?>
                        <tr class="text-warning">
                            <td colspan="6">No data found!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
                
        <?php else: ?>
            <div id="dynamic_content_onR"></div>
        <?php endif; ?>

    </div>
</div>
<?php endif; 
    include ('includes/footer.inc.php');
?>

    <div class="modal fade" id="registrarsModal" tabindex="-1" aria-labelledby="registrarsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h1 class="modal-title fs-5" id="registrarsLabel">Registrars option</h1>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <a href="?ricsv=1" type="button" class="btn btn-info w-100 mt-4">Import CSV</a>
                    <button type="button" class="btn btn-warning w-100 mt-4 selectMoreToDelete">Select To Delete More</button>
                    <button type="button" class="btn btn-danger w-100 mt-4" id="emptyTable">Truncate Table</button>
                </div>
            </div>
        </div>
    </div>




    <script type="text/javascript">

      $(document).ready(function() {

        // ADD MORE FIELDS TO ADD A VOTER
        var i = 1;
        $('#add').click(function() {
          i = i + 1;
          // INCREASE total_fields
          $('#total_fields').val(i);

          // APPEND THE NEW ADDED FIELDS TO THE FORM
          $('#dynamic_field').append('<div id="row'+i+'" class="mb-3"><hr><div class="row"><div class="col-md-6 mb-3"><label class="form-label" for="">Voter Identity No:</label><input type="text" name="voter_identity[]" id="voter_identity'+i+'" placeholder="Student ID" class="form-control voter_details"></div><div class="col-md-6 mb-3"><label class="form-label" for="">Voter Email:</label><input type="email" name="voter_email[]" id="voter_email'+i+'" placeholder="Student Email" class="form-control voter_details" value="<?= $voter_email; ?>"></div><div class="col-md-4 mb-3"><label class="form-label" for="">Voter First Name:</label><input type="text" name="voter_fname[]" id="voter_fname'+i+'" placeholder="First Name" class="form-control voter_details"></div><div class="col-md-4 mb-3"><label class="form-label" for="">Voter Last Name:</label><input type="text" name="voter_lname[]" id="voter_lname'+i+'" placeholder="Last Name" class="form-control voter_details"></div><div class="col-md-4 mb-3"><label class="form-label" for="">Election Type</label><select class="form-control voter_details" id="voter_election_type'+i+'" name="voter_election_type[]"><option value="">-- Select election type for voter --</option><?php foreach ($election_result as $election_row): ?><option value="<?= $election_row['election_id']; ?>"<?= (($sel_election == $election_row['election_id'])?' selected' : '');?>><?= ucwords($election_row['election_name']); ?> / <?= ucwords($election_row['election_by']); ?></option><?php endforeach; ?></select></div></div><div class="mt-2"><button type="button" id="'+i+'" name="remove" class="btn btn-sm btn-danger btn_remove">remove</button></div></div>');

          // IF SUBMIT BUTTON IS BEEN CLICKED AND THERE IS MORE FIELD ADDED RUN THE FOLLOWING VALIDATION
          $('#submitVoters').click(function () {

            if ($.trim($('#voter_identity1').val()).length == 0) {
              alert("Please Voter ID is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter ID is reqired</div>');
              $('#voter_identity1').focus();
              return false;
            }

            if ($.trim($('#voter_fname1').val()).length == 0) {
              alert("Please Voter First Name is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter First Name is reqired</div>');
              $('#voter_fname1').focus();
              return false;
            }

            if ($.trim($('#voter_lname1').val()).length == 0) {
              alert("Please Voter Last Name is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter Last Name is reqired</div>');
              $('#voter_lname1').focus();
              return false;
            }

            if ($.trim($('#voter_identity'+i).val()).length == 0) {
              alert("Please Voter ID is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter ID is reqired</div>');
              $('#voter_identity'+i).focus();
              return false;
            }

            if ($.trim($('#voter_fname'+i).val()).length == 0) {
              alert("Please Voter First Name is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter First name is reqired</div>');
              $('#voter_fname'+i).focus();
              return false;
            }

            if ($.trim($('#voter_lname'+i).val()).length == 0) {
              alert("Please Voter Last Name is required");
              $('#errorMsg').html('<div class="alert alert-danger">Voter First name is reqired</div>');
              $('#voter_lname'+i).focus();
              return false;
            }


            //$('#AddVoter').submit();

        });



        });

        // CLICK TO REMOVE FIELD
        $(document).on('click', '.btn_remove', function() {
          var button_id = $(this).attr('id');
          $('#row'+button_id+'').remove();
          i--;
          // REDUCE total_fields
          $('#total_fields').val(i);
        }); 

        // $('#submitVoters').click(function () {

        //   if ($.trim($('#voter_fname').val()).length == 0) {
        //     alert("Please Voter Full Name");
        //     $('#errorMsg').html('<div class="alert alert-danger">Voter First name is reqired</div>');
        //     $('#voter_fname').focus();
        //     return false;
        //   }

        //   if ($.trim($('#voter_lname').val()).length == 0) {
        //     alert("Please Voter Full Name");
        //     $('#errorMsg').html('<div class="alert alert-danger">Voter First name is reqired</div>');
        //     $('#voter_lname').focus();
        //     return false;
        //   }

        //   if ($.trim($('#voter_identity').val()).length == 0) {
        //     alert("Please Voter ID");
        //     $('#errorMsg').html('<div class="alert alert-danger">Voter ID is reqired</div>');
        //     $('#voter_identity').focus();
        //     return false;
        //   }

        //   $('#AddVoter').submit();

        // });

        // TRUNCATE OR DELETE EVERY SIGLE ROW FROM THE VOTERS DATABASE
        $(document).on('click', '#emptyTable', function() {
            var dataValue = 'emptyVotersTable';
            $.ajax({
                url : "registrar",
                method : "POST",
                data : {dataValue : dataValue},
                success: function(data) {
                window.location = '<?= ADROOT; ?>registrar';
                }
            });
        });

        // DISPLAY BUTTON AND CHECKE BOX TO DELETE VOTERS
        $(document).on('click', '.selectMoreToDelete', function() {
            $('#delete_checkedDisplay').fadeIn(1500);
            $('.checkToDelete').fadeIn(1500);
            // HIDE MODAL AFTER selectMoreToDelete HAS BEEN CLICKED
            $('#registrarsModal').modal('hide');
        });

        var checkBoxChecked = false;
        $(document).on('click', '#selectAll', function() {
          if (checkBoxChecked === false) {
            $("input").prop("checked", true);
            $(".checkToDelete").closest('tr').addClass(['tr-bg-danger', 'removeRow']);
            checkBoxChecked = true;
          } else if (checkBoxChecked === true) {
            $("input").prop("checked", false);
            $(".checkToDelete").closest('tr').removeClass(['tr-bg-danger', 'removeRow']);
            checkBoxChecked = false;
          }
        });

        // ADD AND REMOVE BACKGROUND COLOR OF RED AND ADD A CLASS OF removeRow FOR CHECKED AND UNCHECKED BOXES
        $(document).on('click', '.checkToDelete', function() {
          //alert('ass');
          if ($(this).is(':checked')) {
            $(this).closest('tr').addClass(['tr-bg-danger', 'removeRow']);
          } else {
            $(this).closest('tr').removeClass(['tr-bg-danger', 'removeRow']);
          }
        });

        $(document).on('click', '#delete_checked', function() {
          var checkbox = $('.checkToDelete:checked');
          if (checkbox.length > 0) {
            // GET VALUE OF A CHECKED BOX
            var checkbox_value = [];
            $(checkbox).each(function() {
              checkbox_value.push($(this).val());
            });
            // SEND AJAX REQUEST TO DELETE CHECKED VOTERS
            $.ajax({
              url: 'registrar',
              type: 'POST',
              data: {checkbox_value : checkbox_value},
              success: function(data) {
                // REMOVE DELETED VOTER FROM TABLE ROW
                $('.removeRow').fadeOut(1500);
                $('#delete_checkedDisplay').fadeOut(1500);
                $('.checkToDelete').fadeOut(1500);
              }
            });
          } else {
            // IF delete_checked IS BEEN CLICKED AND THERE IS NO CHECK BOX CLICKED SEND IN AN ERROR
            alert('Select At least 1')
          }
        });

        // SEND SINGLE MAIL OR BULK
        $(document).on('click', '.email_button', function() {
          $(this).attr('disabled', 'disabled');
          var id = $(this).attr("id");
          var action = $(this).data("action");
          var email_data = [];
          if (action == 'single') {
            email_data.push ({
              email: $(this).data("email"),
              password: $(this).data("password")
            });
          } else {
            $('.single_select').each(function() {
              if ($(this). prop("checked") == true) {
                email_data.push({
                  email: $(this).data("email"),
                  password: $(this).data('password')
                });
              }
            });
          }

          $.ajax ({
            url : "controller/control_send_mail.php",
            method : "POST",
            data : {
              email_data : email_data
            },
            beforeSend: function(){
              $('#'+id).html('Sending mail...');
              $('#'+id).addClass('btn-danger');
            },
            success : function(data) {
              if (data = 'ok') {
                $('#'+id).text('Success');
                $('#'+id).removeClass('btn-danger');
                $('#'+id).removeClass('btn-info');
                $('#'+id).addClass('btn-success');
              } else {
                $('#'+id).text(data);
              }
              $('#'+id).attr('disabled', false);
            }
   
          });
        });

        // IMPORT CSV FILE
        var clear_timer;
        $('#uploadRCSV').on('submit', function(event) {
          $('#csv_message').html('');
          event.preventDefault();

          $.ajax({
            url : "controller/control.upload.registrar.csv.php",
            method : 'POST',
            data : new FormData(this),
            dataType : "json",
            contentType : false,
            cache : false,
            processData : false,
            beforeSend :  function() {
              $('#importCSV').attr('disabled', 'disabled');
              $('#importCSV').val('Importing ...');
            },
            success : function(data) {
              if (data.success) {
                $('#total_csv_data').text(data.total_line);
                start_import();
                clear_timer = setInterval(get_import_data, 2000);
              }
              if (data.error) {
                $('#csv_message').html('<div class="alert alert-danger" id="temporary">'+data.error+'</div>');
                $('#importCSV').attr('disabled', false);
                $('#importCSV').val('Import');
              }
            }
          });
        });

        function start_import() {
          $('#process').css('display', 'block');
          $.ajax({
            url : "controller/control.import.registrar.csv.php",
            success: function() {

            }
          });
        }

        function get_import_data() {
          $.ajax({
            url : "controller/control.process.csv.php",
            success : function(data) {
              var total_data = $("#total_csv_data").text();
              var width = Math.round((data/total_data)*100);
              $("#process_data").text(data);
              $(".progress-bar").css('width', width + '%');
              if (width >= 100) {
                clearInterval(clear_timer);
                $('#process').css('display', 'none');
                $('#csvfile').val('');
                $('#csv_message').html('<div class="alert alert-success" id="temporary">Data successfully Imported</div>');
                $('#importCSV').attr('disabled',false);
                $('#importCSV').val('Import');
              }
            }

          });
        }



        function load_data(page, query = '') {
        $.ajax({
          url : "controller/contol.search.registrars.php",
          method : "POST",
          data : {page:page, query : query},
          success : function(data) {
            $("#dynamic_content_onR").html(data);
          }
        });
      }

      load_data(1);
      $('#searchR').keyup(function() {
        var query = $('#searchR').val();
        load_data(1, query);
      });

      $(document).on('click', '.page-link-go', function() {
        var page = $(this).data('page_number');
        var query = $('#searchR').val();
        load_data(page, query);
      });





      });
    </script>
