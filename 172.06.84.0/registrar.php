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
$query = "SELECT * FROM election WHERE session = ? ORDER BY eid DESC";
$statement = $conn->prepare($query);
$statement->execute([0]);
$election_result = $statement->fetchAll();


// GET VOTER FOR EDITING
if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
    $editid = sanitize((int)$_GET['editvoter']);

    $findVoter = $conn->query("SELECT * FROM registrars INNER JOIN election ON election.eid = registrars.election_type WHERE registrars.id = '".$editid."' AND election.session = 0")->rowCount();
    if ($findVoter > 0) {
        foreach ($conn->query("SELECT * FROM registrars WHERE id = '".$editid."'")->fetchAll() as $row) {
            $voter_fname = ((isset($row['std_fname']) != '') ? $row["std_fname"] : $_POST["voter_fname"]);
            $voter_lname = ((isset($row['std_lname']) != '') ? $row["std_lname"] : $_POST["voter_lname"]);
            $voter_identity = ((isset($row['std_id']) != '') ? $row["std_id"] : $_POST["voter_identity"]);
            $voter_email = ((isset($row['std_email']) != '') ? $row["std_email"] : $_POST["voter_email"]);
            $sel_election = ((isset($row['election_type']) != '')?$row["election_type"] : $_POST["voter_election_type"]);
        }
    } else {
        $_SESSION['flash_error'] = 'Voter cannot be found!';
        echo "<script>window.location = 'registrar</script>";
    }

}

// DELETE VOTER
if (isset($_GET['deletevoter']) && !empty($_GET['deletevoter'])) {
    $deleteid = (int)$_GET['deletevoter'];

    $findVoter = $conn->query("SELECT * FROM registrars INNER JOIN election ON election.eid = registrars.election_type WHERE registrars.id = '".$deleteid."' AND election.session = 0")->rowCount();
    if ($findVoter > 0) {
        // $deleteQuery = "DELETE FROM registrars WHERE id = '".$deleteid."'";
        // $statement = $conn->prepare($deleteQuery);
        // $statement->execute();
        if ($conn->query("DELETE FROM registrars WHERE id = '".$deleteid."'")) {
            $_SESSION['flash_success'] = 'Registrar Has Been Deleted Successfully';
            echo "<script>window.location = 'registrar';</script>";
        }
    } else {
        $_SESSION['flash_error'] = 'Voter cannot be found!';
        echo "<script>window.location = 'registrar</script>";
    }
}


// MUTIPLE DELETE VOTERS
if (isset($_POST['checkbox_value'])) {
    for ($i = 0; $i < count($_POST['checkbox_value']); $i++) { 
        $query = "DELETE FROM registrars WHERE id = '".$_POST['checkbox_value'][$i]."'";
        $statement = $conn->prepare($query);
        $statement->execute();
    }
}

// TRUNCATE VOTERS TABLE
if (isset($_POST['dataValue'])) {
    if ($_POST['dataValue'] == 'emptyVotersTable') {
        $query = "TRUNCATE `evt`.`registrars`";
        $statement = $conn->prepare($query);
        $statement->execute();
    }
}

  // ADD A NEW VOTER
  if (isset($_POST['submitVoters'])) {

      for ($i = 0; $i < $_POST['total_fields']; $i++) {

          $query = "SELECT * FROM registrars WHERE std_id = '".$_POST['voter_identity'][$i]."'";
          if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
            $query = "SELECT * FROM registrars WHERE std_id = '".$_POST['voter_identity'][$i]."' AND id != '".(int)$_GET['editvoter']."'";
          }
          $statement = $conn->prepare($query);
          $statement->execute();
          $count = $statement->rowCount();

          if($count > 0) {
            $message = '<div class="alert alert-danger" id="temporary">This Voter Already Exists</div>';
          } else {

            if ($message == '') {

              // str_shuffle() RANDOMLY SHUFFLE ALL CHARACTERS OF A STRING (SIMPLY MEANS IT RANDOMLY REARRANGE STRING CHARACTERS) AND
              // substr() RETURNS A PART OF THAT STRING
              //$string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
              $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
              $generatedpassword = substr(str_shuffle($string), 0, 8);

              if (isset($_GET['editvoter']) && !empty($_GET['editvoter'])) {
                $update = "UPDATE registrars SET std_id = '".$_POST['voter_identity'][$i]."', std_fname = '".$_POST['voter_fname'][$i]."', std_lname = '".$_POST['voter_lname'][$i]."', std_email = '".$_POST['voter_email'][$i]."', election_type = '".$_POST['voter_election_type'][$i]."' WHERE id = '".(int)$_GET['editvoter']."'";
                $statement = $conn->prepare($update);
                $statement->execute();
                $_SESSION['flash_success'] = 'The Voter Has Been Successfully Updated';
                echo "<script>window.location = 'registrar';</script>";
              } else {
                $query = "INSERT INTO registrars (std_id, std_password, std_fname, std_lname, std_email, election_type) VALUES ('".$_POST['voter_identity'][$i]."', '".$generatedpassword."', '".$_POST['voter_fname'][$i]."', '".$_POST['voter_lname'][$i]."', '".$_POST['voter_email'][$i]."', '".$_POST['voter_election_type'][$i]."')";
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
        ON election.eid = registrars.election_type 
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

                            </div>
                        </div>
                    </div>
                </div>
            <div>




    <?php if(isset($_GET['addnewvoter']) || isset($_GET['editvoter']) && !empty($_GET['editvoter'])): ?>
    <!-- MAIN -->
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);"><?= ((isset($_GET['editvoter'])?'Edit':'Add New')); ?> Voter</h4>
                <form action="?<?= ((isset($_GET['editvoter']))?'editvoter='.$editid:'addnewvoter=1') ?>" method="post" id="AddVoter">
                    <span id="errorMsg"><?= $message; ?></span>
                    <div id="dynamic_field">
                    <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="label">Voter Identity No:</label>
                        <input type="text" name="voter_identity[]" id="voter_identity1" placeholder="Student ID" class="form-control form-control-sm form-control-dark voter_details" value="<?= $voter_identity; ?>" required autofocus>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="label">Voter Email:</label>
                        <input type="email" name="voter_email[]" id="voter_email1" placeholder="Student Email" class="form-control form-control-sm form-control-dark voter_details" value="<?= $voter_email; ?>" required>
              </div>
              <div class="col-md-4 mb-2">
                <label class="label">Voter First Name:</label>
                <input type="text" name="voter_fname[]" id="voter_fname1" placeholder="First Name" class="form-control form-control-sm form-control-dark voter_details" value="<?= $voter_fname; ?>" required>
              </div>
              <div class="col-md-4 mb-2">
                <label class="label">Voter Last Name:</label>
                <input type="text" name="voter_lname[]" id="voter_lname1" placeholder="Last Name" class="form-control form-control-sm form-control-dark voter_details" value="<?= $voter_lname; ?>" required>
              </div>
              <div class="col-md-4 mb-2">
                <label class="label">Election Type</label>
                <select class="form-control form-control-sm form-control-dark voter_details" id="voter_election_type1" name="voter_election_type[]">
                    <option value=""> -- Select election type for voter -- </option>
                     <?php foreach ($election_result as $election_row): ?>
                  <option value="<?= $election_row['eid']; ?>"<?= (($sel_election == $election_row['eid'])?' selected' : '');?>><?= ucwords($election_row['election_name']); ?> / <?= ucwords($election_row['election_by']); ?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>
            </div>
            <div class="mt-2">
            <input type="hidden" name="total_fields" id="total_fields" value="1">
            <button type="submit" name="submitVoters" id="submitVoters" class="btn btn-outline-warning btn-sm"><?= ((isset($_GET['editvoter'])?'Edit':'Add')); ?> Now!</button>
            <a href="registrar" class="btn btn-danger btn-sm">Cancel</a>
            <?php if (isset($_GET['addnewvoter'])): ?>
              <button type="button" name="add" id="add" class="btn btn-sm btn-dark float-right">Add New Field</button>
            <?php endif; ?>
          </div>
      </form>
    </div>
  </div>
      
  <?php elseif (isset($_GET['ricsv'])): ?>
  <div class="card">
    <div class="card-body">
      <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Import CSV File Data</h4>
      <span id="csv_message"></span>
    <form method="POST" action="" id="uploadRCSV" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group">
                <label class="col-md-4 control-label">Select CSV File</label>
                <input type="file" name="csvfile" id="csvfile" class="form-control-dark form-control-sm form-control">
                <small class="text-warning">Student ID, Password(Automatically generated), First name, Last name, Email, Election type.</small>
              </div>
              <div class="form-group">
                <input type="hidden" name="csv_hidden_field" value="1">
                <button type="submit" name="importCSV" class="btn-sm btn btn-danger" id="importCSV">Import</button>
              </div>
            </form>
            <div class="form-group" id="process" style="display: none;">
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
                <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">List Of Duplicated email</h4>
                <div class="table-responsive">
                    <table class="table table-nowrap table-centered table-hover mb-0" style="color: #aab8c5;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Identity Number</th>
                                <th>Full Name</th>
                                <th>Email</th>
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
                                <td><?= strtoupper($fde_row["std_id"]); ?></td>
                                <td><?= ucwords(strtolower($fde_row["std_fname"].' '.$fde_row["std_lname"])); ?> <span class="text-<?= (($fde_row['status'] == '1')?'success':'danger'); ?>" data-feather="<?= (($fde_row['status'] == '1')?'check':'x'); ?>"></span></td>
                                <td><?= $fde_row["std_email"]; ?></td>
                                <td>
                                    <?= ucwords($fde_row['election_name']); ?> / <?= ucwords($fde_row['election_by']); ?>
                                </td>
                                <td>
                                    <a href="?deletevoter=<?= $fde_row["id"]; ?>" class="badge badge-dark text-primary"><span data-feather="trash"></span></a>&nbsp;
                                    <a href="?editvoter=<?= $fde_row["id"]; ?>" class="badge badge-dark text-danger"><span data-feather="edit-3"></span></a>
                                    <input type="checkbox" class="checkToDelete" value="<?= $fde_row["id"]; ?>" style="display: none;">
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

   <div class="modal fade" id="registrarsModal" tabindex="-1" role="dialog" aria-labelledby="registrarsLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content bg-dark border-secondary">
        <div class="modal-header">
          <h5 class="modal-title" id="registrarsLabel">Registrars Options</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <a href="?ricsv=1" type="button" class="btn-sm btn btn-info">Import CSV</a>
            <button type="button" class="btn-sm btn btn-warning selectMoreToDelete">Select To Delete More</button>
            <button type="button" class="btn-sm btn btn-danger" id="emptyTable">Truncate Table</button>
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
      </div>
    </div>
  </div>




    <!-- FOOTER -->
    <script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="media/files/popper-1.14.6.min.js"></script>
    <script type="text/javascript" src="media/files/bootstrap.min.js"></script>

    <script type="text/javascript">
        $("#temporary").fadeOut(3000);

      $(document).ready(function() {

        // ADD MORE FIELDS TO ADD A VOTER
        var i = 1;
        $('#add').click(function() {
          i = i + 1;
          // INCREASE total_fields
          $('#total_fields').val(i);

          // APPEND THE NEW ADDED FIELDS TO THE FORM
          $('#dynamic_field').append('<div id="row'+i+'" class="mb-3"><hr><div class="row"><div class="col-md-6 mb-2"><label class="label">Voter Identity No:</label><input type="text" name="voter_identity[]" id="voter_identity'+i+'" placeholder="Student ID" class="form-control form-control-sm form-control-dark voter_details"></div><div class="col-md-6 mb-2"><label class="label">Voter Email:</label><input type="email" name="voter_email[]" id="voter_email'+i+'" placeholder="Student Email" class="form-control form-control-sm form-control-dark voter_details" value="<?= $voter_email; ?>"></div><div class="col-md-4 mb-2"><label class="label">Voter First Name:</label><input type="text" name="voter_fname[]" id="voter_fname'+i+'" placeholder="First Name" class="form-control form-control-sm form-control-dark voter_details"></div><div class="col-md-4 mb-2"><label class="label">Voter Last Name:</label><input type="text" name="voter_lname[]" id="voter_lname'+i+'" placeholder="Last Name" class="form-control form-control-sm form-control-dark voter_details"></div><div class="col-md-4 mb-2"><label class="label">Election Type</label><select class="form-control form-control-sm form-control-dark voter_details" id="voter_election_type'+i+'" name="voter_election_type[]"><option value="">-- Select election type for voter --</option><?php foreach ($election_result as $election_row): ?><option value="<?= $election_row['eid']; ?>"<?= (($sel_election == $election_row['eid'])?' selected' : '');?>><?= ucwords($election_row['election_name']); ?> / <?= ucwords($election_row['election_by']); ?></option><?php endforeach; ?></select></div></div><div class="mt-2"><button type="button" id="'+i+'" name="remove" class="btn btn-sm btn-danger btn_remove">remove</button></div></div>');

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
              window.location = 'registrar';
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
            $(".checkToDelete").closest('tr').addClass(['bg-danger', 'removeRow']);
            checkBoxChecked = true;
          } else if (checkBoxChecked === true) {
            $("input").prop("checked", false);
            $(".checkToDelete").closest('tr').removeClass(['bg-danger', 'removeRow']);
            checkBoxChecked = false;
          }
        });

        // ADD AND REMOVE BACKGROUND COLOR OF RED AND ADD A CLASS OF removeRow FOR CHECKED AND UNCHECKED BOXES
        $(document).on('click', '.checkToDelete', function() {
          //alert('ass');
          if ($(this).is(':checked')) {
            $(this).closest('tr').addClass(['bg-danger', 'removeRow']);
          } else {
            $(this).closest('tr').removeClass(['bg-danger', 'removeRow']);
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
