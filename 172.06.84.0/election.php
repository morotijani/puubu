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

    $election_name = ((isset($_POST['election_name'])?$_POST["election_name"]:''));
    $election_by = ((isset($_POST['election_by'])?$_POST["election_by"]:''));

    // DELETE AN ELECTION NAME FROM DATABASE
    if (isset($_GET['delete_election']) && !empty($_GET['delete_election'])) {
        $delete_id = (int)$_GET['delete_election'];

        $find_election_and_check_status = $conn->query("SELECT * FROM election WHERE eid = '".$delete_id."' AND session = 0")->rowCount();
        if ($find_election_and_check_status > 0) {

            $deleteQuery = "DELETE FROM election WHERE eid = ?";
            $statement = $conn->prepare($deleteQuery);
            $delete_election_result = $statement->execute([$delete_id]);

            if (isset($delete_election_result)) {
                $_SESSION['flash_success'] = 'Election Name Has Been Successfully <span class="bg-danger">Deleted</span>';
                echo "<script>window.location = 'election'</script>";
            }
        } else {
            $_SESSION['flash_success'] = 'The selected election is either do not exist or has already been <span class="bg-danger">Activated</span>';
            echo "<script>window.location = 'election'</script>";

        }

    }


    // SELECT ELECTION NAME FROM DATABASE FOR EDIT PROCESS USING THE ID IN GET FORM
    if (isset($_GET['edit_election'])) {

        $edit_election_id = sanitize((int)$_GET['edit_election']);

        $find_election_and_check_status = $conn->query("SELECT * FROM election WHERE eid = '".$edit_election_id."' AND session = 0")->rowCount();
        if ($find_election_and_check_status > 0) {

            $query = "SELECT * FROM election WHERE eid = ? AND session = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$edit_election_id, 0]);
            $result = $statement->fetchAll();
            foreach ($result as $_row) {
                $election_name = ((isset($_row['election_name'])? $_row['election_name'] : $_POST["election_name"]));
                $election_by = ((isset($_row['election_by'])? $_row['election_by'] : $_POST["election_by"]));
            }
        } else {
            $_SESSION['flash_success'] = 'The selected election is either do not exist or has already been <span class="bg-danger">Activated</span>';
            echo "<script>window.location = 'election'</script>";
        }
    }


    // LIST * INPUTED ELECTIONS
    $get_election_query = "SELECT * FROM election ORDER BY eid DESC";
    $statement = $conn->prepare($get_election_query);
    $statement->execute();
    $get_election_count = $statement->rowCount();
    $get_election_result = $statement->fetchAll();
    $listElection = '';
    if ($get_election_count > 0) {
        foreach ($get_election_result as $get_election_row) {
            if ($get_election_row['session'] == 1) {
                $option1 = "<span class='badge badge-success' title='Election is on going.'>running ...</span>";
                $option2 = "
                    <a href='reports?report=1&election=" . $get_election_row["eid"] . "' class='btn btn-sm btn-primary' title='View Runing Election Details' target='_blank'>
                        <span class='material-symbols-outlined me-1'>visibility</span> View
                    </a>
                ";
            } else if ($get_election_row['session'] == 2) {
                $option1 = "<span class='badge bg-danger-subtle text-danger'>ended</span>";
                $option2 = "
                    <a href='report/full_election_report?election=" . $get_election_row["eid"] . "' class='btn btn-sm btn-secondary' title='View Ended Election Details'>
                        <span class='material-symbols-outlined me-1'>visibility</span> View
                    </a>
                    ";
            } else {
                $option1 = '
                    <a href="election.php?edit_election='.$get_election_row["eid"].'" class="btn btn-sm btn-warning" title="Edit Election">
                        <span class="material-symbols-outlined me-1">stylus_note</span> Edit
                    </a>
                ';
                $option2 = '
                    <a href="javascript:;" class="btn btn-sm btn-danger delete-election" title="Delete Election" id="'.$get_election_row["eid"].'">
                        <span class="material-symbols-outlined me-1">delete</span> Delete
                    </a>
                ';
            }
            $listElection .= '
                <tr>
                    <td>
                        ' . $option1 . '
                    </td>
                    <td><strong class="fw-semibold">' . ucwords($get_election_row["election_name"]) . '</strong></td>
                    <td> ' . ucwords($get_election_row["election_by"]) . '</td>
                    <td>
                        ' . $option2 . '
                    </td>
                </tr>
            ';
        }
    } else {
            $listElection .= '
                <tr class="text-center">
                    <td colspan="4">No data found!</td>
                </tr>
            ';
    }



    // INSERT IN POSITION TO DATABASE
    if (isset($_POST['addelection'])) {
        if (empty($_POST['election_name']) || empty($_POST['election_name'])) {
            $message = '<div class="text-danger" id="temporary">Empty Fields are Required</div>';
        } else {

            $query = "SELECT * FROM election WHERE election_name = '". $_POST['election_name']."' AND election_by = '".$_POST["election_by"]."'";
            if (isset($_GET['edit_election']) && !empty($_GET['edit_election'])) {
                $query = "SELECT * FROM election WHERE election_name = '".$_POST['election_name']."' AND eid != '".(int)$_GET['edit_election']."'";
            }
            $statement = $conn->prepare($query);
            $statement->execute();

            if($statement->rowCount() > 0) {
                $message = '<div class="text-danger" id="temporary">This Election Name Already Exists</div>';
            } else {
                if ($message == '') {

                    if (isset($_GET['edit_election']) && !empty($_GET['edit_election'])) {
                        $update = "
                            UPDATE election 
                            SET election_name = '".$_POST['election_name']."', election_by = '".$_POST['election_by']."' 
                            WHERE eid = '".(int)$_GET['edit_election']."'";
                        $statement = $conn->prepare($update);
                        $resultu = $statement->execute();

                        if (isset($resultu)) {
                            $_SESSION['flash_success'] = 'Election Successfully Updated';
                            echo "<script>window.location = 'election'</script>";
                        }
                    } else {
                        $query = "INSERT INTO election (election_name, election_by) VALUES ('".$_POST['election_name']."', '".$_POST['election_by']."')";
                        $statement = $conn->prepare($query);
                        $result = $statement->execute();
                        if (isset($result)) {
                            $_SESSION['flash_success'] = 'Election Successfully <span class="bg-danger">Added</span></div>';
                            echo "<script>window.location = 'election'</script>";
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
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Elections</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Elections</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Elections</h1>
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

            
        <!-- Billing -->
        <section class="card bg-body-tertiary border-transparent card-line mb-5" id="billing">
            <div class="card-body">
                <form class="" action="election.php?<?= ((isset($_GET['edit_election']))?'edit_election='.$edit_election_id:'addnewposition=1'); ?>" method="post">
                    <div class="row align-items-center mb-4">
                        <div class="col">
                            <h2 class="fs-5 mb-1">Add election</h2>
                            <p class="text-body-secondary mb-0">Billing information is securely stored with our payment processor and is not accessible to us.</p>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-dark" type="submit" name="addelection"><?= ((isset($_GET["edit_election"]))? '- Edit' : '+ Add'); ?></button>
                            <?php if(isset($_GET['edit_election'])): ?>&nbsp;
                                <a href="election" class="btn btn-sm btn-secondary btn-lg">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card border-transparent">
                        <div class="card-body">
                            <span><?= $message; ?></span>
                            <div class="row gx-3">
                                <div class="col-6 col-md">
                                    <div class="mb-4 mb-lg-0">
                                        <label class="form-label" for="election_name">Election name</label>
                                        <input class="form-control" name="election_name" id="election_name" type="text" placeholder="SRC" value="<?= $election_name; ?>"
                                        />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4 mb-lg-0">
                                        <label class="form-label" for="election_by">Organizers</label>
                                        <input class="form-control" name="election_by" id="election_by" type="text" placeholder="University of Ghana" value="<?= $election_by; ?>" />
                                    </div>
                                </div>
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
                                <h3 class="fs-6 mb-0">Watchlist</h3>
                            </div>
                            <div class="col-auto my-n3 me-n3">
                                <button class="btn btn-sm btn-link" type="button">+ Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <th class="fs-sm">Status</th>
                                <th class="fs-sm">Name</th>
                                <th class="fs-sm">Organizer</th>
                                <th class="fs-sm" colspan="2"></th>
                            </thead>
                            <tbody>
                                <?= $listElection; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    
    <?php include ('includes/footer.inc.php'); ?>

    <script>
        $(document).ready(function() {
            $("#temporary").fadeOut(5000);

            // DELETE ELECTION TEMPORARY
            $(document).on('click', '.delete-election', function() {
                var election_id = $(this).attr('id');
                if (confirm("Election will be deleted TEMPORARY")) {
                    window.location = '<?= PROOT ?>172.06.84.0/election?delete_election=' + election_id + '';
                }
            });
        });
    </script>
