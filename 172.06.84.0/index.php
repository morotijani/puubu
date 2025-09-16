<?php

    require_once("../connection/conn.php");

    if (!cadminIsLoggedIn()) {
        cadminLoginErrorRedirect();
    }

    include ('includes/header.inc.php');
    include ('includes/top-nav.inc.php');
    include ('includes/left-nav.inc.php');

    //
    $started_election_query = "SELECT * FROM election WHERE session = ? OR session = ?";
    $statement = $conn->prepare($started_election_query);
    $statement->execute([1, 2]);
    $started_election_reult = $statement->fetchAll();
    $started_election_count = $statement->rowCount();

?>

    <!-- Main -->
    <main class="main px-lg-6">

        <!-- Content -->
        <div class="container-lg">
            <!-- Page content -->
            <div class="row align-items-center">
                <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
                    <div class="avatar text-info me-2">
                    <i class="fs-4" data-duoicon="world"></i>
                    </div>
                    Tamale, GH –&nbsp;<span><?= date('h:i A'); ?></span>
                </div>
                <div class="col-12 col-md order-md-0 text-center text-md-start">
                    <h1>Hello, <?= ucwords($fname); ?></h1>
                    <p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for all elections.</p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="my-8" />

            <!-- Stats -->
            <div class="row mb-8">
                <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                    <div class="card bg-body-tertiary border-transparent">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <!-- Heading -->
                                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Elections</h4>

                                    <!-- Text -->
                                    <div class="fs-4 fw-semibold"># <?= $listall_election; ?></div>
                                </div>
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <div class="avatar avatar-lg bg-body text-primary">
                                        <i class="fs-4" data-duoicon="slideshow"></i>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                <div class="card bg-body-tertiary border-transparent">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Heading -->
                                <h4 class="fs-sm fw-normal text-body-secondary mb-1">Contestants</h4>

                                <!-- Text -->
                                <div class="fs-4 fw-semibold"># <?= count_contestants(); ?></div>
                            </div>
                            <div class="col-auto">
                                <!-- Avatar -->
                                <div class="avatar avatar-lg bg-body text-primary">
                                    <i class="fs-4" data-duoicon="user-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-md-0">
                <div class="card bg-body-tertiary border-transparent">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Heading -->
                                <h4 class="fs-sm fw-normal text-body-secondary mb-1">Positions</h4>

                                <!-- Text -->
                                <div class="fs-4 fw-semibold"># <?= count_positions(); ?></div>
                            </div>
                            <div class="col-auto">
                                <!-- Avatar -->
                                <div class="avatar avatar-lg bg-body text-primary">
                                    <i class="fs-4" data-duoicon="menu"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xxl-3">
                <div class="card bg-body-tertiary border-transparent">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Heading -->
                                <h4 class="fs-sm fw-normal text-body-secondary mb-1">Voters</h4>

                                <!-- Text -->
                                <div class="fs-4 fw-semibold"># <?= count_voters(); ?></div>
                            </div>
                            <div class="col-auto">
                                <!-- Avatar -->
                                <div class="avatar avatar-lg bg-body text-primary">
                                    <i class="fs-4" data-duoicon="user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <div class="row">
            <div class="col-12 col-xxl-8">
                <!-- Performance -->
                <!-- <div class="card mb-6">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="fs-6 mb-0">Performance</h3>
                            </div>
                            <div class="col-auto fs-sm me-n3">
                                <span class="material-symbols-outlined text-primary me-1">circle</span>
                                Total
                            </div>
                            <div class="col-auto fs-sm">
                                <span class="material-symbols-outlined text-dark me-1">circle</span>
                                Tracked
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas class="chart-canvas" id="userPerformanceChart"></canvas>
                        </div>
                    </div>
                </div> -->

                <div class="card mb-6">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="fs-6 mb-0">
                                    Current elections <img src="media/election-gif.gif" class="ml-2 img-fluid">
                                </h3>
                            </div>
                            <div class="col-auto my-n3 me-n3">
                                <button class="btn btn-sm btn-link" type="button">+ Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <th class="fs-sm">Election</th>
                                <th class="fs-sm">Positions</th>
                                <th class="fs-sm">Candidates</th>
                                <th class="fs-sm">Voters</th>
                                <th class="fs-sm">Turnout</th>
                                <th class="fs-sm"></th>
                                <th class="fs-sm"></th>
                            </thead>
                            <tbody>
                                <?php if ($started_election_count > 0): ?>
                                    <?php foreach ($started_election_reult as $row):
                                        $electionStatus = '';
                                        $election_report_option = '';

                                        if ($row['session'] == 2) {
                                            $electionStatus = '<span class="badge bg-success">ended</span>';
                                            $election_report_option = '
                                                <span class="badge bg-dark">
                                                    <a href="'.PROOT.'172.06.84.0/report/full_election_report?election='.$row["election_id"].'" class="text-secondary" target="_blank">report</a>
                                                    </span>
                                                <br>
                                                <a href="reports.voted.php?report=' . $row["election_id"] . '" class="badge bg-secondary nav-link" target="_blank">Voted Details</a>
                                                <a href="reports.voter.php?report=' . $row["election_id"] . '" class="badge bg-info nav-link" target="_blank">Voters Details</a>
                                                ';
                                        } else {
                                            $electionStatus = '<span class="badge bg-danger">running ...</span>';
                                            $election_report_option = '<span class="badge bg-dark"><a href="'.PROOT.'172.06.84.0/reports?report=1&election='.$row["election_id"].'" class="text-secondary" target="_blank">report</a></span>';
                                        }
                                        ?>
                                <tr>
                                    <td>
                                        <?= ucwords($row["election_name"]) . ' ' . $electionStatus; ?>
                                        <br>
                                        Org: <?= ucwords($row["election_by"]); ?>
                                    </td>
                                    <td><?= count_positions_on_running_election($row["election_id"]); ?></td>
                                    <td><?= count_contestants_on_runing_election($row["election_id"]); ?></td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><?= count_voters_on_runing_election($row['election_id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><?= count_votes_on_runing_election($row["election_id"]); ?></span>
                                    </td>
                                    <td>
                                        <div class="chart" style="height: 1rem; width: 3rem">
                                            <canvas class="chart-canvas" data-crypto-currency-success-chart></canvas>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <?= $election_report_option; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">No election running...</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Industry news -->
                <div class="card mb-6 mb-xxl-0">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="fs-6 mb-0">General overview</h3>
                            </div>
                            <div class="col-auto my-n3 me-n3">
                                <a class="btn btn-sm btn-link" href="">
                                    Browse all
                                    <span class="material-symbols-outlined">arrow_right_alt</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <!-- List -->
                        <div class="list-group list-group-flush">

                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Add election</h3>
                                        <p class="text-body-secondary">To start a fresh new election, go to the <a href="<?= PROOT; ?>172.06.84.0/election">Add Elections</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Positions & Elections</h3>
                                        <p class="text-body-secondary">To setup the positions under their respective elections, go to the <a href="<?= PROOT; ?>172.06.84.0/positions">Manage Positions & Elections tab</a>.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Add Contestants</h3>
                                        <p class="text-body-secondary">For the adding up of the candidates, head to the <a href="<?= PROOT; ?>172.06.84.0/contestants">Add Contestants</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Manage Contestants</h3>
                                        <p class="text-body-secondary">Go to <a href="<?= PROOT; ?>172.06.84.0/contestants">Manage Contestants</a> tab to setup the contestants.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Voters</h3>
                                        <p class="text-body-secondary">Registrars you wish to add to allow them to vote can be managed at the <a href="<?= PROOT; ?>172.06.84.0/registrar">Voters</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Password</h3>
                                        <p class="text-body-secondary">It is highly recommended to change admin's password at the <a href="<?= PROOT; ?>172.06.84.0/settings.php?cp=1">Change Password</a> tab before conducting an election.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xxl-4">
            <!-- Header -->
            <div class="row align-items-center mb-5">
                <div class="col">
                    <!-- Heading -->
                    <h2 class="fs-5 mb-0">Active vote logs</h2>
                </div>
                <div class="col-auto my-n2">
                    <a class="btn btn-link" href="#!">
                    Browse all
                    <span class="material-symbols-outlined">arrow_right_alt</span>
                    </a>
                </div>
            </div>
            
            <div class="vstack gap-2 mb-7">
                <div class="card">
                    <a
                        class="card-body p-4"
                        data-bs-toggle="collapse"
                        href="#supportTicketThree"
                        role="button"
                        aria-expanded="false"
                        aria-controls="supportTicketThree"
                    >
                    <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-sm fs-lg text-primary"><i data-duoicon="moon-2"></i></div>
                            </div>
                            <div class="col ms-n3">
                                <h5 class="fs-sm fw-normal text-body-secondary mb-1">#10245</h5>
                                <h3 class="fs-base mb-0">Feature request: dark mode toggle</h3>
                            </div>
                            <div class="col-auto">
                                <span class="fs-sm text-body-secondary">Sep 28</span>
                            </div>
                        </div>
                    </a>
                    <div class="collapse" id="supportTicketThree">
                        <div class="card-body border-top line-clamp-2 text-body-secondary py-4 px-0 mx-4">
                            A user has requested a toggle for dark mode in the app settings. They would like a way to switch themes without having to rely on the
                            system’s default settings.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <div class="card">
				<div class="card-header">
					<h3 class="fs-6 mb-0">Recent activity</h3>
				</div>
				<div class="card-body">
					<ul class="activity">
						<?= get_logs($admin_data['admin_id']); ?>
					</ul>
				</div>
			</div>

          </div>
        </div>
      </div>
    


    <div class="modal fade" id="electionModal" tabindex="-1" aria-labelledby="electionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h1 class="modal-title fs-5" id="electionLabel">Start an election</h1>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitElectionSession" method="POST">

                        <?php
                            $query = "SELECT * FROM election WHERE session = ?";
                            $statement = $conn->prepare($query);
                            $statement->execute([0]);
                            $not_started_election_result = $statement->fetchAll();
                            $not_started_election_count = $statement->rowCount();
                            if ($not_started_election_count > 0) {
                        ?>
                            <div class="mb-4">
                                <label class="form-label" for="election-session">Elections</label>
                                <select class="form-control" name="election-session" id="election-session" required="required">
                                    <option>Select Election</option> 
                                    <?php foreach ($not_started_election_result as $row): ?>
                                        <option value="<?= $row["election_id"]; ?>"><?= ucwords($row["election_name"]); ?> / <?= ucwords($row["election_by"]); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Create voting end time session.</label>
                                <input type="datetime-local" name="ctimer" id="ctimer" class="form-control" required>
                            </div>
                        <?php } else { ?>
                            <div class='well'>There aren't any election available to start. You can <a href='election'>add one</a></div>
                        <?php } ?>
                        <button type="submit" class="btn w-100 mt-4 btn-warning">Start!</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="electionModal" tabindex="-1" role="dialog" aria-labelledby="electionLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark border-secondary">

                <div class="modal-header">
                    <h5 class="modal-title" id="electionLabel">Start an election</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        
            </div>
        </div>
    </div>

<?php include ('includes/footer.inc.php'); ?>
