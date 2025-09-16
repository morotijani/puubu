<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
include ('includes/left-nav.inc.php');

if (isset($_GET['report']) && !empty($_GET['report'])) {
    $election_id = sanitize($_GET['report']);

    $query = "
        SELECT * FROM election 
        WHERE election_id = ? 
        LIMIT 1
    ";
    $statement = $conn->prepare($query);
    $statement->execute([$election_id]);
    $report_result = $statement->fetchAll();
    $count_report = $statement->rowCount();

    foreach ($report_result as $report_row) {
        // code...
    }

    if ($count_report > 0) {

        $position_sql = "
            SELECT * FROM positions 
            INNER JOIN election 
            ON election.election_id = positions.election_id 
            WHERE election.election_id = ? 
            AND election.session != ?
        ";
        $statement = $conn->prepare($position_sql);
        $statement->execute([$election_id, 0]);
        $position_result = $statement->fetchAll();
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
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Voters details reports</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                    <div class="row gx-2">
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-light w-100" href="<?= PROOT; ?>172.06.84.0/reports.voted.php?report=<?= $election_id; ?>"><span class="material-symbols-outlined me-1">add</span> Refresh</a>
                        </div>
                        <div class="col-6 col-sm-auto">
                            <a href="reports?report=1&election=<?= $election_id; ?>" class="btn btn-danger w-100">Go back</a>
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
                                            <div class="text-body-secondary">No customers selected</div>
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
                                            <a class="btn btn-dark px-3" href="reports.voted.php?report=<?= $election_id; ?>">
                                                Voted details
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-dark px-3" href="reports.voter.php?report=<?= $election_id; ?>">
                                                Voter details
                                            </a>
                                        </div>

                                        <div class="col-auto ms-n2">
                                            <a class="btn btn-dark px-3" href="<?= PROOT; ?>172.06.84.0/registrar">
                                                Voters
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <div>

    <?php

            $output = '';
            $voter_query = "
                SELECT * FROM voter_login_details 
                INNER JOIN registrars 
                ON registrars.voter_id = voter_login_details.voter_id 
                WHERE registrars.registrar_election = ?
            ";
            $statement = $conn->prepare($voter_query);
            $statement->execute([$election_id]);
            $result = $statement->fetchAll();
            $i = 1;
            $output .= '
                <div class="table-responsive">
                    <table class="table mb-0" id="voter-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Identity Number</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Login Datetime</th>
                                <th>Logout Datetime</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
            if ($statement->rowCount() > 0) {
                foreach ($result as $row) {
                    $output .= "
                        <tr>
                            <td>{$i}</td>
                            <td class='text-uppercase'>{$row['std_id']}</td>
                            <td class='text-capitalize'>{$row['std_fname']}&nbsp;{$row['std_lname']}</td>
                            <td>{$row['std_email']}</td>
                            <td>".pretty_date($row['voter_login_datetime'])."</td>
                            <td>".pretty_date($row['voter_logout_datetime'])."</td>
                        </tr>
                    ";
                    $i++;
                }
            } else {
                $output .= '
                    <tr>
                        <td colspan="6"> No data found</td>
                    </td>
                ';
            }
            $output .= '
                        </tbody>
                    </table>
                </div>          
            ';
        
    ?>

    <div class="card" id="printIframeDiv">
        <div class="card-body">
            <div class="row align-items-center mb-2">
                <div class="col">
                    <h3 class='fs-6 mb-2'>
                        Voter details <span class="text-danger"><?= ucwords($report_row["election_name"]) ?></span>.
                    </h4>
                </div>
                <div class="col-auto my-n3 me-n3">
                    <a href="javascript:;" name="create_excel" id="create_excel" class="float-right mb-3 ml-1">
                        <span class="material-symbols-outlined me-1">cloud_download</span> Export as excel file
                    </a>
                </div>
            </div>
            <?= $output; ?>
        </div>
    </div>
    
            
<?php 
    } else {
        $_SESSION['error_flash'] = 'Election was not found!';
        echo '<script>window.location = "index";</script>';
    }
} else {
    $_SESSION['error_flash'] = 'There was an error, please try again later.';
    echo '<script>window.location = "index";</script>';
}

include ('includes/footer.inc.php');

?>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript">
    function html_table_to_excel(type) {
        var data = document.getElementById('voter-table');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, {
            bookType: type, 
            bookSST: true, 
            type: 'base64' 
        });

        XLSX.writeFile(file, 'VOTER report on <?= ucwords($report_row["election_name"] . ' ~ ' . $report_row["election_by"]) ?> election.' + type);
    }

    const export_button = document.getElementById('create_excel');

    export_button.addEventListener('click', () =>  {
        html_table_to_excel('xlsx');
    });
</script>