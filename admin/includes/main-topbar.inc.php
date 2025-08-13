    <!-- MAIN BODY TOPBAR -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="text-white" style="font-size: 18px;">Dashboard</h4>
        <div class="text-center">
            <!-- <span class="text-muted" id="countDT"></span> -->
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#electionModal">OPTION</button>
                <!-- <a href="?eclear=1" class="btn-sm btn btn-danger">Clear Ended Election</a> -->
                <a href="index" class="btn-sm btn btn-secondary">Refresh</a>
            </div>
           <!--  <?php if ($started_election > 0) :?>
            <?php if ($sub_row['session'] == 2): ?>
                <a target="_tab" href="report/afterVotingReport" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="calendar"></span>
                    Generate Report
                </a>
            <?php endif; ?>
            <?php endif; ?> -->
        </div>
    </div>

    <style type="text/css">
        .widget-icon {
            color: #536de6;
            /*font-size: 20px;*/
            background-color: rgba(83,109,230,.25);
            height: 40px;
            width: 40px;
            text-align: center;
            line-height: 40px;
            border-radius: 3px;
            display: inline-block;
        }

        .widget-flat {
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
            background-color: #37404a;
            background-clip: border-box;
        }

        .widget-flat h3 {
            color: rgb(170, 184, 197);
        }
    </style>


    <div class="row">
        <div class="col-xl-12 col-lg-12">
<!-- 
            <div class="row">
                <div class="col-lg-3">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="widget-icon">
                                <span data-feather="users"></span></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Contestants">Contestants</h5>
                            <h3 class="mt-3 mb-3"># <?= count_contestants(); ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success mr-2"><i class="mdi mdi-arrow-up-bold"></i> <?= count_contestants() / 100; ?>%</span>
                                <span class="text-nowrap"><a href="contestants" class=" text-secondary">got to contestants</a></span>  
                            </p>
                        </div>
                    </div>
                </div> 

                <div class="col-lg-3">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="widget-icon bg-success-lighten text-success">
                                <span data-feather="shopping-cart"></span></i></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Voters">Voters</h5>
                            <h3 class="mt-3 mb-3">#<?= count_voters(); ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger mr-2"><i class="mdi mdi-arrow-down-bold"></i> <?= count_voters() / 100; ?>%</span>
                                <span class="text-nowrap"><a href="?v4=1">Voted for?</a></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                     <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="widget-icon bg-success-lighten text-success">
                                <span data-feather="dollar-sign"></span></i></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Positions">Positions</h5>
                            <h3 class="mt-3 mb-3">#<?= count_positions(); ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger mr-2"><i class="mdi mdi-arrow-down-bold"></i> <?= count_positions() / 100; ?>%</span>
                                <span class="text-nowrap"><a href="positions" class=" text-secondary">got to positions</a></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="widget-icon">
                                <span data-feather="activity"></span></i></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Votes Counts">Votes Counts</h5>
                            <h3 class="mt-3 mb-3">+ <?= count_votes(); ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success mr-2"><i class="mdi mdi-arrow-up-bold"></i> <?= count_votes() / 100; ?>%</span>
                                 <span class="text-nowrap">Actual votes</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div> -->

            <div class="card mt-4" style='background-color: #37404a;'>
                <div class="card-body">
                    <h4 class='header-title mb-3 text-left' style='color:rgb(170, 184, 197);'>Current elections <img src="media/election-gif.gif" class="ml-2 img-fluid"></h4><hr>

                    <?php 

                $started_election_query = "SELECT * FROM election WHERE session = ? OR session = ?";
                $statement = $conn->prepare($started_election_query);
                $statement->execute([1, 2]);
                $started_election_reult = $statement->fetchAll();
                $started_election_count = $statement->rowCount();
                echo '
                    <table class="table table-sm table-borderless text-secondary table-hover table-striped">
                ';
                if ($started_election_count > 0) {
                    echo '
                        <thead>
                            <tr>
                                <th>Election Name</th>
                                <th>Election Organizers</th>
                                <th>Positions</th>
                                <th>Candidates</th>
                                <th>Vote Turnout</th>
                                <th>Voters</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                    ';
                    foreach ($started_election_reult as $row) {
                        $electionStatus = '';
                        $election_report_option = '';

                        if ($row['session'] == 2) {
                            $electionStatus = '<span class="badge badge-success">ended</span>';
                            $election_report_option = '
                                <span class="badge badge-dark">
                                    <a href="'.PROOT.'172.06.84.0/report/full_election_report?election='.$row["eid"].'" class="text-secondary" target="_blank">report</a>
                                    </span>
                                <br>
                                <a href="reports.voted.php?report=' . $row["eid"] . '" class="badge text-secondary nav-link" target="_blank">Voted Details</a>
                                <a href="reports.voter.php?report=' . $row["eid"] . '" class="badge text-secondary nav-link" target="_blank">Voters Details</a>
                                ';
                        } else {
                            $electionStatus = '<span class="badge badge-danger">running ...</span>';
                            $election_report_option = '<span class="badge badge-dark"><a href="'.PROOT.'172.06.84.0/reports?report=1&election='.$row["eid"].'" class="text-secondary" target="_blank">report</a></span>';
                        }
                        echo '
                            <tr>
                                <td>' . ucwords($row["election_name"]) . ' ' . $electionStatus. '</td>
                                <td>'.ucwords($row["election_by"]).'</td>
                                <td>'.count_positions_on_running_election($row["eid"]).'</td>
                                <td>'.count_contestants_on_runing_election($row["eid"]).'</td>
                                <td>'.count_votes_on_runing_election($row["eid"]).'</td>
                                <td>'.count_voters_on_runing_election($row['eid']).'</td>
                                <td>'.$election_report_option.'</td>
                            </tr>
                        ';
                    }
                    echo '</tbody>';
                } else {
                    echo '
                        <tr>
                            <td colspan="7">No election running...</td>
                        </tr>
                    ';
                }
                echo '</table>';
            ?>
                </div>

            </div>
        </div>
    </div>