<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
// include ('includes/left-nav.inc.php');

if (isset($_GET['report']) && !empty($_GET['report'])) {
    $election_id = sanitize((int)$_GET['report']);

    $query = "
        SELECT * FROM election 
        WHERE eid = ? 
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
            ON election.eid = positions.election_id 
            WHERE election.eid = ? 
            AND election.session != ?
        ";
        $statement = $conn->prepare($position_sql);
        $statement->execute([$election_id, 0]);
        $position_result = $statement->fetchAll();
?>

<main role="main" class="col-md-12 col-lg-12 px-4" style="background-color: rgb(70, 60, 54);">
<style type="text/css">
    .dropdown-menu.show {
        padding: 0!important;
        background-color: #5f554d;
    }
</style>
    <ul class="nav justify-content-end p-3">
        <li class="nav-item">
             <a href="reports.voted.php?report=<?= $election_id; ?>" class="text-secondary nav-link active">Voted Details</a>
        </li>
        <li class="nav-item">
            <a href="reports.voter.php?report=<?= $election_id; ?>" class="text-secondary nav-link">Voter Details</a>
        </li>
        <li class="nav-item">
            <a href="registrar" class="text-secondary nav-link">Voters</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">other</a>
            <div class="dropdown-menu">
                <a href="reports.voter.php?report=<?= $election_id; ?>" class="text-secondary nav-link">Refresh!</a>
                <a href="reports?report=1&election=<?= $election_id; ?>" class="nav-link text-secondary">Go back!</a>
            </div>
        </li>
    </ul>

    <?php

            $output = '';
            $voter_query = "
                SELECT * FROM voter_login_details 
                INNER JOIN registrars 
                ON registrars.id = voter_login_details.voter_id 
                WHERE registrars.election_type = ?
            ";
            $statement = $conn->prepare($voter_query);
            $statement->execute([$election_id]);
            $result = $statement->fetchAll();
            $i = 1;
            $output .= '
                <div class="table-responsive">
                    <table class="table table-nowrap table-centered table-sm table-hover mb-0" style="color: #aab8c5;" id="voter-table">
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
    <div class="card mt-4" style='background-color: #37404a;' id="printIframeDiv">
        <div class="card-body">
            <a href="javascript:;" name="create_excel" id="create_excel" class="float-right mb-3 ml-1">
                Export as excel file <span data-feather="download-cloud" class="ml-1"></span>
            </a>

            <h4 class='header-title mb-3 text-left' style='color:rgb(170, 184, 197);'>Voter details <span class="text-danger"><?= ucwords($report_row["election_name"]) ?></span>.</h4>
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

include ('includes/main-footer.inc.php');
include ('includes/footer.inc.php');

?>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript">
    feather.replace();
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