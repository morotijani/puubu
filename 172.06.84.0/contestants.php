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

// OUPUT ERRORS
$message = '';

// DECLARING VARIABLES AS EMPTY VALUE TO PREVENT ERRORS
$cont_gender = '';
$sel_election = '';

$cont_position = ((isset($_POST['cont_position']) && !empty($_POST['cont_position']))? sanitize($_POST['cont_position']) : '');
$cont_gender = ((isset($_POST['cont_gender']) && !empty($_POST['cont_gender'])) ? sanitize($_POST['cont_gender']) : '');
$cont_lname = ((isset($_POST['cont_lname']) && !empty($_POST['cont_lname'])) ? sanitize($_POST['cont_lname']) : '');
$cont_fname = ((isset($_POST['cont_fname']) && !empty($_POST['cont_fname']))? sanitize($_POST['cont_fname']) : '');
$contestant_ballot_number = ((isset($_POST['contestant_ballot_number']) && !empty($_POST['contestant_ballot_number'])) ? sanitize($_POST['contestant_ballot_number']) : '');
$sel_election = ((isset($_POST['sel_election']) && !empty($_POST['sel_election'])) ? sanitize($_POST['sel_election']):'');
$pp_path = '';
$saved_passport = '';

// FETCH ELECTIONS THAT HAS NOT YET BEEN STATED
$query = "SELECT * FROM election WHERE session = ? ORDER BY election_id DESC";
$statement = $conn->prepare($query);
$statement->execute([0]);
$election_result = $statement->fetchAll();

// DELETE A CONTESTANT TEMPORARY
if (isset($_GET['deletecontestant']) && !empty($_GET['deletecontestant'])) {
    $deleteid = sanitize($_GET['deletecontestant']);
    $delnoyes = $_GET['del'];

    $findContestant = $conn->query("SELECT * FROM cont_details INNER JOIN election ON election.election_id = cont_details.contestant_election WHERE cont_details.contestant_id = '".$deleteid."' AND election.session = 0 AND cont_details.del_cont = 'no'")->rowCount();
    if ($findContestant > 0) {
        if ($conn->query("UPDATE cont_details SET del_cont = '".$delnoyes."' WHERE contestant_id = '".$deleteid."'"))
            $log_message = "contestant ['" . $deleteid . "'], temporary deleted!";
            add_to_log($log_message, $admin_id, 'admin');

            $_SESSION['flash_success'] = 'Contestant Has Been Temporary <span class="bg-danger">DELETED</span>';
            redirect(ADROOT . 'contestants');
    } else {
        $log_message = "contestant ['" . $deleteid . "'], selected to be deleted temporary, but did not exist!";
        add_to_log($log_message, $admin_id, 'admin');

        $_SESSION['flash_error'] = 'Contestant was not found or cannot be deleted Temporary!';
        redirect(ADROOT . 'contestants'); 
    }
}

// DELETE A CONTESTANT PERMANENTLY
if (isset($_GET['permanentdel']) && !empty($_GET['permanentdel'])) {
    $p_deleteid = sanitize($_GET['permanentdel']);

    $findContestant = $conn->query("SELECT * FROM cont_details WHERE cont_details.contestant_id = '".$p_deleteid."' AND cont_details.del_cont = 'yes'")->rowCount();
    if ($findContestant > 0) {
        // REMOVE PASSPORT PICTURE FROM uploadedprofile FOLDER
        $uploadedpp_loc = BASEURL.'media/uploadedprofile/'.$_GET['uploadedpp_name'];
        if (unlink($uploadedpp_loc)) {
            if ($conn->query("DELETE FROM cont_details WHERE contestant_id = ".$p_deleteid." AND del_cont = 'yes'")) {
                $_SESSION['flash_success'] = 'Contestant Has Been Permanently <span class="bg-danger">DELETED</span>';
                redirect(ADROOT . 'contestants?achived_contestants');
            } else {
                $log_message = "contestant ['" . $p_deleteid . "'], selected to be deleted permanently, but did not exist!";
                add_to_log($log_message, $admin_id, 'admin');

                $_SESSION['flash_error'] = 'Something went wrong, please try again or contact System Administrator!';
                redirect(ADROOT . 'contestants?achived_contestants');
            }
        } else {
            $log_message = "contestant ['" . $p_deleteid . "'], selected to be deleted permanently, but was unable to delete becuase contestant profile picture was unable to  delete!";
            add_to_log($log_message, $admin_id, 'admin');

            $_SESSION['flash_error'] = 'Could not find contestant profile picture to delete, please try again or contact System Administrator!';
            redirect(ADROOT . 'contestants?achived_contestants');
        }
    } else {
        $log_message = "contestant ['" . $p_deleteid . "'], selected to be deleted permanently, but did not exist!";
        add_to_log($log_message, $admin_id, 'admin');

        $_SESSION['flash_error'] = 'Contestant was not found or cannot be deleted Permanently!';
        redirect(ADROOT . 'contestants'); 
    }
}

// RESTORE A TEMPORARY DELETED CONTESTANT
if (isset($_GET['restorecontestant']) && !empty($_GET['restorecontestant'])) {
    $restoreid = sanitize($_GET['restorecontestant']);
    $restorenoyes = $_GET['restore'];

    $findContestant = $conn->query("SELECT * FROM cont_details INNER JOIN election ON election.election_id = cont_details.contestant_election WHERE cont_details.contestant_id = '".$restoreid."' AND election.session = 0 AND cont_details.del_cont = 'yes'")->rowCount();
    if ($findContestant > 0) {
        if ($conn->query("UPDATE cont_details SET del_cont = '".$restorenoyes."' WHERE contestant_id = '".$restoreid."'")) {
            $log_message = "contestant ['" . $restoreid . "'], restored!";
            add_to_log($log_message, $admin_id, 'admin');

            $_SESSION['flash_success'] = 'Contestant Has Been Successfully <span class="bg-danger">Restored</span>';
            redirect(ADROOT . 'contestants');
        } else {
            $_SESSION['flash_success'] = 'Contestant restore <span class="bg-danger">Failed</span>';
            redirect(ADROOT . 'contestants');
        }
    } else {
        $log_message = "contestant ['" . $restoreid . "'], selected to be restored, but did not exist!";
        add_to_log($log_message, $admin_id, 'admin');

        $_SESSION['flash_error'] = 'Contestant was not found to be restored, either the election he/she is under is already going on or ended!';
        redirect(ADROOT . 'contestants'); 
    }
}


// FETCH DELETED CONTESTANTS
$query = "
    SELECT * FROM cont_details 
    INNER JOIN positions 
    ON positions.position_id = cont_details.cont_position 
    LEFT JOIN election 
    ON election.election_id = cont_details.contestant_election 
    WHERE cont_details.del_cont = ? 
    ORDER BY cont_details.contestant_id DESC
";
$statement = $conn->prepare($query);
$statement->execute(['yes']);
$result = $statement->fetchAll();
$achive_contList = '';
$i = 1;
if ($statement->rowCount() > 0) {

    foreach ($result as $row) {
        $achive_contList .= '
            <tr class="text-center">
                <td>' . $i . '</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                          	<img class="avatar-img" src="../media/uploadedprofile/' . $row["cont_profile"] . '" alt="'.ucwords($row["cont_fname"]) . '" />
                        </div>
						<div class="ms-4">
							<div>' . ucwords($row["cont_fname"].' '.$row["cont_lname"]).'</div>
							<div class="fs-sm text-body-secondary">
								<span class="text-reset">deleted</span>
							</div>
						</div>
					</div>
				</td>
                <td>' . $row["contestant_ballot_number"] . '</td>
                <td>'.$row["cont_gender"].'</td>
                <td>'.ucwords($row["position_name"]).'</td>
                <td>'.ucwords($row["election_name"]).' / <span class="text-muted">' . ucwords($row["election_by"]) . '</span></td>
                <td>
                    <a href="'.ADROOT.'contestants?permanentdel='.$row["contestant_id"].'&uploadedpp_name='.$row["cont_profile"].'" class="btn btn-sm btn-danger" title="Permanently Delete Contestant">
                        <span class="material-symbols-outlined me-1">delete</span> Delete
                    </a>&nbsp;
                    <a href="'.ADROOT.'contestants?restorecontestant='.$row["contestant_id"].'&restore='.(($row["del_cont"] == 'yes')?'no':'yes').'" class="btn btn-sm btn-success" title="Restore Contestant">
                        <span class="material-symbols-outlined me-1">cycle</span> Restore
                    </a>
                </td>
            </tr>
        ';
        $i++;
    }
} else {
    $achive_contList .= '
        <tr>
            <td colspan="8"><span class="text-muted">No Data Found!</span></td>
        </tr>
    ';
}


// FETCH CONTESTANT DETAILS FOR EDITING OR UPDATE
if (isset($_GET['editcontestant']) && !empty($_GET['editcontestant'])) {
    $editid = sanitize($_GET['editcontestant']);

    $findContestant = $conn->query("SELECT * FROM cont_details INNER JOIN election ON election.election_id = cont_details.contestant_election WHERE cont_details.contestant_id = '".$editid."' AND election.session = 0 AND cont_details.del_cont = 'no'")->rowCount();
    if ($findContestant > 0) {
        $editQuery = $conn->query("SELECT * FROM cont_details WHERE contestant_id = '".$editid."' LIMIT 1")->fetchAll();
        foreach ($editQuery as $sub_row) {
            $contestant_ballot_number = ((isset($_POST['contestant_ballot_number']) && $_POST['contestant_ballot_number'] != '') ? sanitize($_POST['contestant_ballot_number']):$sub_row['contestant_ballot_number']);
            $cont_fname = ((isset($_POST['cont_fname']) && $_POST['cont_fname'] != '') ? sanitize($_POST['cont_fname']) : $sub_row['cont_fname']);
            $cont_lname = ((isset($_POST['cont_lname']) && $_POST['cont_lname'] != '') ? sanitize($_POST['cont_lname']) : $sub_row['cont_lname']);
            $cont_position = ((isset($_POST['cont_position']) && $_POST['cont_position'] != '') ? sanitize($_POST['cont_position']) : $sub_row['cont_position']);
            $cont_gender = ((isset($_POST['cont_gender']) && $_POST['cont_gender'] != '') ? sanitize($_POST['cont_gender']) : $sub_row['cont_gender']);
            $sel_election = ((isset($_POST['sel_election']) && $_POST['sel_election'] != '') ? sanitize($_POST['sel_election']) : $sub_row['contestant_election']);
            $saved_passport = (($sub_row['cont_profile'] != '') ? $sub_row['cont_profile'] : '');
            $pp_path = $saved_passport;
        }

        // DELETE CONTESTANT UPLOADED IMAGE ON EDITING PAGE
        if (isset($_GET['del_pp']) && !empty($_GET['contpp'])) {
            $contpp = $_GET['contpp'];
            $contpp_loc = $_SERVER['DOCUMENT_ROOT'].'/puubu/media/uploadedprofile/'.$contpp;
            if (unlink($contpp_loc)) {
                unset($contpp);
                if ($conn->query("UPDATE cont_details SET cont_profile = '' WHERE contestant_id = '".$editid."'")) {
                    echo '<script>window.location = "'. ADROOT .'contestants?editcontestant='.$editid.'"</script>';
                }
            }
        }
        

        // GET LIST POSITIONS FOR EDIT CONTESTANT
        $positionQuery = $conn->query("SELECT * FROM positions WHERE election_id = '".$sub_row['contestant_election']."' ORDER BY position_name ASC")->fetchAll();
    } else {
        $_SESSION['flash_error'] = 'Contestant was not found or cannot be deleted Permanently!';
        redirect(ADROOT . 'contestants'); 
    }
}


// CREATE NEW CONTESTANT
if (isset($_POST['createcont'])) {

    // CHECK FOR EMPTY FIELDS
    if (empty($_POST['contestant_ballot_number']) || empty($_POST['cont_fname']) || empty($_POST['cont_lname']) || empty($_POST['cont_position']) || empty($_POST['cont_gender'])) {
        if ($_POST['uploadedPassport'] != '') {
            unlink($_POST['uploadedPassport']);
        }
        $message = '<div class="alert alert-danger">Empty Fields are Required</div>';
    } else {
        $findContestant = $conn->query("SELECT * FROM cont_details INNER JOIN election ON election.election_id = cont_details.contestant_election WHERE contestant_ballot_number = '".$_POST['contestant_ballot_number']."' AND election.election_id = '".$_POST['sel_election']."' AND cont_position = '".$_POST['cont_position']."'")->rowCount();
        if (isset($_GET['editcontestant']) && !empty($_GET['editcontestant'])) {
            $findContestant = $conn->query("SELECT * FROM cont_details INNER JOIN election ON election.election_id = cont_details.contestant_election WHERE election.election_id = '".$_POST['sel_election']."' AND contestant_ballot_number = '".$_POST['contestant_ballot_number']."' AND cont_position = '".$_POST['cont_position']."' AND contestant_id != '".$_GET['editcontestant']."'")->rowCount();
        }
        if ($findContestant > 0) {
            if (isset($_POST['uploadedPassport']) != '') {
                unlink($_POST['uploadedPassport']);
            }
            $message = '<div class="alert alert-danger">Contestant Identity No Already Exists.</div>';
        } else {
    
            // UPLOAD PASSPORT PICTURE TO uploadedprofile IF FIELD IS NOT EMPTY
            if ($_POST['cont_up_profile'] == '') {
                if ($_FILES["cont_profile"]["name"] != '') {

                    // CONTESTANT PROFILE PICTURE UPLOAD DETAILS
                    $image_test = explode(".", $_FILES["cont_profile"]["name"]);
                    $image_extension = end($image_test);
                    $image_name = uniqid('', true).".".$image_extension;

                    $location = BASEURL.'media/uploadedprofile/'.$image_name;
                    if (!move_uploaded_file($_FILES["cont_profile"]["tmp_name"], $location)) {
                        $message = 'Something went wrong uploading contestant passport picture, please refresh and try again!';
                    }
                
                    if ($_POST['uploadedPassport'] != '') {
                        unlink($_POST['uploadedPassport']);
                    }
                } else {
                    if ($_POST['uploadedPassport'] != '') {
                        unlink($_POST['uploadedPassport']);
                    }
                    $message = '<div class="alert alert-danger">Passport Picture Can not be Empty</div>';
                }
            } else {
                $image_name = $_POST['cont_up_profile'];
            }

            // INSERT DATA TO DATABASE IF ERRORS OR MESSAGES ARE EMPTY
            if ($message == '') {
                if (isset($_GET['editcontestant']) && !empty($_GET['editcontestant'])) {
                    $updateQ = "UPDATE cont_details SET contestant_ballot_number = '".$contestant_ballot_number."', cont_fname = '".$cont_fname."', cont_lname = '".$cont_lname."', cont_gender = '".$cont_gender."', cont_position = '".$cont_position."', contestant_election = '".$sel_election."', cont_profile = '".$image_name."'  WHERE contestant_id = '".$_GET["editcontestant"]."'";
                    $statement = $conn->prepare($updateQ);
                    $resultQ = $statement->execute();
                    if (isset($resultQ)) {
                        $log_message = "contestant ['" . $_GET["editcontestant"] . "'], updated!";
                        add_to_log($log_message, $admin_id, 'admin');

                        $_SESSION['flash_success'] = ucwords($sub_row["cont_fname"] .' '. $sub_row["cont_lname"]) .' Contestant Successfully <span class="bg-danger">Updated</span>';
                        redirect(ADROOT . 'contestants');
                    }
                } else {
                    $unique_id = guidv4();
                    $query = "INSERT INTO cont_details (contestant_id, contestant_ballot_number, cont_fname, cont_lname, cont_gender, cont_position, contestant_election, cont_profile) VALUES ('" . $unique_id . "', '".$contestant_ballot_number."', '".$cont_fname."', '".$cont_lname."', '".$cont_gender."', '".$cont_position."',  '".$sel_election."', '".$image_name."')";
                    $statement = $conn->prepare($query);
                    $result = $statement->execute();
                    $lastinsetedID = $conn->lastinsertId();
                    if (isset($result)) {
                        $queryVoteCounts = "INSERT INTO vote_counts (results, contestant_id, position_id, election_id) VALUE (:results, :contestant_id, :position_id, :election_id)";
                        $statement = $conn->prepare($queryVoteCounts);
                        $statement->execute(
                            array(
                                ':results' => 0,
                                ':contestant_id' => $unique_id,
                                ':position_id' => $cont_position,
                                ':election_id' => $sel_election
                            )
                        );
                        
                        $log_message = "new contestant, added!";
                        add_to_log($log_message, $admin_id, 'admin');

                        $_SESSION['flash_success'] = 'A Contestant Successfully <span class="bg-danger">Created</span>';
                        redirect(ADROOT . 'contestants');
                    }
                }
            } else {
                if ($_POST['uploadedPassport'] != '') {
                    unlink($_POST['uploadedPassport']);
                }
                $message;
            }
        } 
    }
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
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Contestatnt</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contestant</li>
                        </ol>
                    </nav>
                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Contestant</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <div class="row gx-2">
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-light w-100" href="<?= PROOT; ?>172.06.84.0/contestants?createcontestant=1"><span class="material-symbols-outlined me-1">add</span> Add</a>
                        </div>
                        <div class="col-6 col-sm-auto">
                            <a href="<?= ADROOT; ?>contestants?achived_contestants=1" class="btn btn-danger w-100">Achive contestants</a>
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
                                            <div class="text-body-secondary">Contestants</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="row gx-3  ">
                                        <div class="col col-lg-auto ms-auto">
                                            <!-- <div class="input-group bg-body">
                                                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" />
                                                <span class="input-group-text" id="search">
                                                    <span class="material-symbols-outlined">search</span>
                                                </span>
                                            </div> -->
                                        </div>

                                        <div class="col-auto">
                                            <a class="btn btn-dark px-3" href="<?= ADROOT; ?>contestants">
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

<?php
    if (isset($_GET['createcontestant']) || isset($_GET['editcontestant']) && !empty($_GET['editcontestant'])):
?>
  
    <!-- ADD OR UPDATE CONTESTANT -->
    <div class="card">
        <div class="card-body">
            <h4 class="mt-2"><?= ((isset($_GET['editcontestant']))?'Edit':'Add new') ?> contestant</h4>
            <form class="" action="contestants.php?<?= ((isset($_GET['editcontestant']))?'editcontestant='.$editid:'createcontestant=1'); ?>" method="post" id="submitcontestant" enctype="multipart/form-data">
                <div class="container">
                    <span><?= $message; ?></span>
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3">
                                <label class="form-label" for="contestant_ballot_number">Ballot Number</label>
                                <input type="text" name="contestant_ballot_number" value="<?= $contestant_ballot_number; ?>" placeholder="Contestant ID or Ballot No" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label" for="cont_gender">Gender</label>
                                <select class="form-control" name="cont_gender" id="cont_gender">
                                    <option value=""<?=(($cont_gender == '')?' selected':'');?>>Select Gender</option>
                                    <option value="male"<?=(($cont_gender == 'male')?' selected':'');?>>Male</option>
                                    <option value="female"<?=(($cont_gender == 'female')?' selected':'');?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label" for="cont_fname">First name</label>
                                <input type="text" name="cont_fname" value="<?= $cont_fname; ?>" placeholder="Contestant First Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label" for="cont_lname">Last name</label>
                                <input type="text" name="cont_lname" value="<?= $cont_lname; ?>" placeholder="Contestant Last Name" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label" for="sel_election">Election</label>
                                <select class="form-control select2getpositions" name="sel_election" id="sel_election">
                                    <option value=""<?= (($sel_election == '') ? ' selected ': ''); ?>>Select an Election</option>
                                    <?php foreach ($election_result as $election_row): ?>
                                    <option value="<?= $election_row['election_id']; ?>"<?= (($sel_election == $election_row['election_id']) ? ' selected': ''); ?>><?= ucwords($election_row['election_name']); ?> ~ <?= ucwords($election_row['election_by']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label" for="cont_position">Position</label>
                                <select class="form-control" name="cont_position" id="cont_position">
                                    <?php if (isset($_GET['editcontestant'])): ?>
                                        <?php foreach($positionQuery as $prow): ?>
                                            <option value="<?= $prow['position_id']; ?>"<?= (($cont_position == $prow['position_id']) ? ' selected': ''); ?>><?= ucwords($prow['position_name']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($saved_passport != ''): ?>
                        <label>Saved Image</label><br>
                        <img src="../media/uploadedprofile/<?= $saved_passport; ?>" class="img-fluid img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                        <a href="contestants?del_pp=1&editcontestant=<?=$editid;?>&contpp=<?=$saved_passport?>" class="badge badge-danger">Change Image</a><br>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label" for="cont_profile">Picture</label>
                            <input type="file" name="cont_profile" id="cont_profile"  class="form-control">
                        </div>
                        <span id="upload_file"></span>
                    <?php endif; ?>
                    <input type="hidden" name="cont_up_profile" id="cont_up_profile" value="<?= $saved_passport; ?>">
                    <br>
                    <button type="submit" class="btn btn-dark" id="createcont" name="createcont"><?= (isset($_GET['editcontestant']))? 'Update': 'Add'; ?> contestant</button>
                    <a href="<?= ADROOT; ?>contestants" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- ACHIVE CONTESTANTS -->
    <?php elseif(isset($_GET['achived_contestants'])): ?>
    
    <div class="card">
        <div class="card-body">
            <h4 class="">Achived contestants</h4>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Contestant</th>
                            <th>ID / Ballot Number</th>
                            <th>Gender</th>
                            <th>Position</th>
                            <th>Election</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $achive_contList; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php else: ?>

    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="material-symbols-outlined text-body-tertiary">search</span>
                </div>
                <div class="col">
                    <input type="text" name="searchC" id="searchC" class="form-control" placeholder="Search for contestants here ...">
                </div>
                <div class="col-auto">
                    <a href="contestants" class="text-primary float-right mb-3">
                        <span class="material-symbols-outlined me-1">refresh</span> Refresh
                    </a>
                </div>
            </div>
            
            <div id="dynamic_content_onC"></div>
        </div>
    </div>
<?php 
    endif;
    include ('includes/footer.inc.php');
?>

    <script type="text/javascript">
        
        $(document).ready(function() {
            $("#temporary").fadeOut(3000);

            $('.select2getpositions').change(function() {
                if ($(this).val() != '') {
                    var action = $(this).attr("id");
                    var query = $(this).val();
                    var result = '';
                    if (action == 'sel_election') {
                      result = 'election';
                    }
                    $.ajax ({
                      url : "controller/control.select2getpositions.contenstants.php",
                        method : "POST",
                        data : {action : action, query : query},
                        success : function(data) {
                          $('#cont_position').html(data);
                        }
                    });
                  }
            });

            // Upload IMAGE Temporary Without Clicking On A Post Button On Post A Post Section
            $(document).on('change','#cont_profile', function() {
                var property = document.getElementById("cont_profile").files[0];
                var image_name = property.name;
                var image_extension = image_name.split(".").pop().toLowerCase();
                if (jQuery.inArray(image_extension, ['jpeg', 'png', 'gif', 'svg', 'jpg']) == -1) {
                    alert("Invalid Image File");
                    $('#cont_profile').val('');
                    return false;
                }
                var image_size = property.size;
                if (image_size > 22000000) {
                    alert('Image Size Is Too Big');
                    $('#cont_profile').val('');
                    return false;
                } else {
                    var form_data = new FormData();
                    form_data.append("cont_profile", property);
                    $.ajax({
                        url : "controller/tempuploadedprofile.php",
                        method : "POST",
                        data : form_data,
                        contentType : false,
                        cache: false,
                        processData : false,
                        beforeSend : function() {
                            $("#upload_file").html("<div class='text-primary font-weight-bolder'>Uploading contestant picture ...</div>");
                        },
                        success: function(data) {
                            $("#upload_file").html(data);
                            $("#cont_profile").css('display', 'none');
                        }
                    });
                }
            });

            // DELETE TEMPORARY UPLOADED IMAGES
            $(document).on('click', '.removeImg', function() {
                var tempuploded_file_id = $(this).attr('id');
              
                $.ajax ({
                    url : "controller/deltempimguploaded.php",
                    method : "POST",
                    data : {
                        tempuploded_file_id : tempuploded_file_id
                    },
                    success: function(data) {
                        $('#removeTempuploadedFile').remove();
                        $('#cont_profile').val('');
                        $("#cont_profile").css('display', 'block');
                    }
                });
            });

            // SEARCH AND PAGINATION FOR CONTESTANTS
            function load_data(page, query = '') {
                $.ajax({
                    url : "controller/contol.search.contestants.php",
                    method : "POST",
                    data : {
                        page : page, 
                        query : query
                    },
                    success : function(data) {
                        $("#dynamic_content_onC").html(data);
                    }
                });
            }

            load_data(1);
            $('#searchC').keyup(function() {
                var query = $('#searchC').val();
                load_data(1, query);
            });

            $(document).on('click', '.page-link-go', function() {
                var page = $(this).data('page_number');
                var query = $('#searchC').val();
                load_data(page, query);
            });

            
        });
    </script>

