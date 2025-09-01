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

// OUTPUT ERRORS
$message = '';

$sel_election = '';
$position_name = ((isset($_POST['position_name'])?$_POST["position_name"]:''));
$sel_election = ((isset($_POST['sel_election']) && !empty($_POST['sel_election']))?sanitize($_POST['sel_election']):'');

// GET ELECTION
$electionQuery = $conn->query("SELECT * FROM election WHERE election.session = 0 ORDER BY election.election_name ASC")->fetchAll();$statement->fetchAll();

// DELETE A POSITION NAME FROM DATABASE
if (isset($_GET['deleteposition']) && !empty($_GET['deleteposition'])) {
    $delete_id = sanitize((int)$_GET['deleteposition']);

    $findPosition = $conn->query("SELECT * FROM positions INNER JOIN election ON election.eid = positions.election_id WHERE position_id = '".$delete_id."' AND election.session = 0")->fetchAll();
    if ($findPosition > 0) {
        if ($conn->query("DELETE FROM positions WHERE position_id = '".$delete_id."'")) {
            $_SESSION['flash_success'] = 'Position Name Has Been Successfully Deleted';
            echo "<script>window.location = 'positions';</script>";
        }
    } else {
        $_SESSION['flash_error'] = 'Position was not found to be deleted!';
        echo '<script>window.location = "positions";</script>';
    }
}

// EDIT POSITION
if (isset($_GET['editposition'])) {
    $edit_id = sanitize((int)$_GET['editposition']);

    $findPosition = $conn->query("SELECT * FROM positions INNER JOIN election ON election.eid = positions.election_id WHERE position_id = '".$edit_id."' AND election.session = 0")->rowCount();
    if ($findPosition > 0) {
        foreach ($conn->query("SELECT * FROM positions WHERE position_id = '".$edit_id."'")->fetchAll() as $_row) {
          $position_name = ((isset($_row['position_name']) ? $_row['position_name'] : $_POST["position_name"]));
          $sel_election = ((isset($_POST['sel_election']) && $_POST['sel_election'] != '') ? sanitize($_POST['sel_election']) : $_row['election_id']);
        }
    } else {
        $_SESSION['flash_error'] = 'Position was not found to be edited!';
        echo '<script>window.location = "positions";</script>';
    }
}

// LIST POSITIONS
$positionsList = '';
foreach ($conn->query("SELECT * FROM positions INNER JOIN election WHERE positions.election_id = election.eid ORDER BY position_id DESC")->fetchAll() as $row) {
    $editOption = '';
    $deleteOption = '';
    if ($row["session"] == 0) {
        $editOption = '
            <a href="?editposition=' . $row["position_id"] . '" class="btn btn-secondary btn-sm">
                <span class="material-symbols-outlined me-1">stylus_note</span> Edit
            </a>
        ';
        $deleteOption = '
            <a href="?deleteposition='.$row["position_id"].'&election='.$row["election_id"].'" class="btn btn-sm btn-warning">
                <span class="material-symbols-outlined me-1">delete</span> Delete
            </a>&nbsp; 
        ';
    } else if ($row["session"] == 1) {
        $editOption = ' <span class="badge bg-danger-subtle text-warning">running ...</span>';
    } else if ($row["session"] == 2) {
        $editOption = ' <span class="badge bg-danger-subtle text-danger">ended</span>';
    }
    $positionsList .= '
        <tr class="text-center">
            <td>
                ' .$editOption. '
            </td>
            <td><strong class="fw-semibold">' . ucwords($row["position_name"]) . '</strong></td>
            <td>'.ucwords($row["election_name"]).' ~ '.ucwords($row["election_by"]).'</td>
            <td>
                ' .$deleteOption. '
            </td>
        </tr>';
  }


// INSERT IN POSITION TO DATABASE
if (isset($_POST['addposition'])) {
    if (empty($_POST['position_name']) || empty($_POST['sel_election'])) {
        $message = '<div class="alert alert-danger" id="temporary">Empty Fields are Required</div>';
    } else {

        $query = "SELECT * FROM positions WHERE position_name = '".$_POST['position_name']."' AND election_id = '".$_POST['sel_election']."'";
        if (isset($_GET['editposition']) && !empty($_GET['editposition'])) {
            $query = "SELECT * FROM positions WHERE position_id = '".$_GET['editposition']."' AND position_id != '".(int)$_GET['editposition']."'";
        }
        $statement = $conn->prepare($query);
        $statement->execute();

        if($statement->rowCount() > 0) {
            $message = '<div class="alert alert-danger" id="temporary">This Position Name Already Exists</div>';
        } else {
            if ($message == '') {

                if (isset($_GET['editposition']) && !empty($_GET['editposition'])) {
                    $update = "UPDATE positions SET position_name = '".$_POST['position_name']."', election_id = '".$_POST['sel_election']."' WHERE position_id = '".(int)$_GET['editposition']."'";
                    $statement = $conn->prepare($update);
                    $resultu = $statement->execute();

                    if (isset($resultu)) {
                        $_SESSION['flash_success'] = 'Position Name Successfully Updated';
                        echo "<script>window.location = 'positions';</script>";
                    }
                } else {
                    $query = "INSERT INTO positions (position_name, election_id) VALUES ('".$_POST['position_name']."', '".$_POST['sel_election']."')";
                    $statement = $conn->prepare($query);
                    $result = $statement->execute();
                    if (isset($result)) {
                        $_SESSION['flash_success'] = 'Position Name Successfully Added';
                        echo "<script>window.location = 'positions';</script>";
                    }
                }
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
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Positions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Positions</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Positions</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                <a class="btn btn-secondary d-block" href="../customers/customer-new.html">
                    <span class="material-symbols-outlined me-1">export_notes</span> Export
                </a>
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
                                        <div class="text-body-secondary">No customers selected</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <div>

        <section class="card bg-body-tertiary border-transparent card-line mb-5" id="billing">
            <div class="card-body">
                <form action="positions?<?= ((isset($_GET['editposition']))?'editposition='.$edit_id:'addnewposition=1'); ?>" method="post">
                    <div class="row align-items-center mb-4">
                        <div class="col">
                            <h2 class="fs-5 mb-1">Add position</h2>
                            <p class="text-body-secondary mb-0">Billing information is securely stored with our payment processor and is not accessible to us.</p>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-dark" type="submit" name="addposition"><?= ((isset($_GET["editposition"]))? 'Edit' : 'Add'); ?></button>
                            <?php if(isset($_GET['editposition'])): ?>
                                <a href="positions" class="btn btn-danger btn-sm">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card border-transparent">
                        <div class="card-body">
                            <span><?= $message; ?></span>
                            <div class="mb-4">
                                <select class="form-control" name="sel_election" id="sel_election">
                                    <option value=""<?=(($sel_election == '')?' selected':'');?>>Select Election Name</option>
                                    <?php foreach ($electionQuery as $election_row): ?>
                                        <option value="<?=$election_row['eid'];?>"<?= (($sel_election == $election_row['eid']) ? ' selected' : ''); ?>><?= ucwords($election_row['election_name']); ?> ~ <?= ucwords($election_row['election_by']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4 mb-lg-0">
                                <label class="form-label" for="election_by">Organizers</label>
                                <input type="text" name="position_name" value="<?= $position_name; ?>" placeholder="<?= ((isset($_GET["editposition"]))?'Edit':'Add'); ?> Position Name" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>


        <div class="row">
            <div class="col-12 ">
                <div class="card mb-6">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="fs-6 mb-0">List</h3>
                            </div>
                            <div class="col-auto my-n3 me-n3">
                                <button class="btn btn-sm btn-link" type="button">+ Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <th class="fs-sm"></th>
                                <th class="fs-sm">Position</th>
                                <th class="fs-sm">Election</th>
                                <th class="fs-sm"></th>
                            </thead>
                            <tbody>
                                <?= $positionsList; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    <?php include ('includes/footer.inc.php'); ?>
