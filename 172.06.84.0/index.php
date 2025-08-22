<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
include ('includes/left-nav.inc.php');

include ('includes/main-topbar.inc.php');

?>



    
    
          

    <!-- Main -->
    <main class="main px-lg-6">
                  <?= $flash; ?>

      <!-- Content -->
      <div class="container-lg">
        <!-- Page content -->
        <div class="row align-items-center">
          <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
            <div class="avatar text-info me-2">
              <i class="fs-4" data-duoicon="world"></i>
            </div>
            San Francisco, CA –&nbsp;<span>8:00 PM</span>
          </div>
          <div class="col-12 col-md order-md-0 text-center text-md-start">
            <h1>Hello, John</h1>
            <p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this week.</p>
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
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Earned</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">$1,250</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="credit-card"></i>
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
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Hours logged</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">35.5 hrs</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="clock"></i>
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
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Avg. time</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">2:55 hrs</div>
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
          <div class="col-12 col-md-6 col-xxl-3">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Weekly growth</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">14.5%</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="discount"></i>
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
            <div class="card mb-6">
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
            </div>

            <!-- Projects -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Active projects</h3>
                  </div>
                  <div class="col-auto my-n3 me-n3">
                    <a class="btn btn-sm btn-link" href="./projects/projects.html">
                      Browse all
                      <span class="material-symbols-outlined">arrow_right_alt</span>
                    </a>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead>
                    <th class="fs-sm">Title</th>
                    <th class="fs-sm">Status</th>
                    <th class="fs-sm">Author</th>
                    <th class="fs-sm">Team</th>
                  </thead>
                  <tbody>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-1.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Filters AI</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 10, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Michael Johnson">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-2.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Design landing page</div>
                            <div class="fs-sm text-body-secondary">Created on Mar 05, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          Emily Thompson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="book-3"></i>
                          </div>
                          <div class="ms-4">
                            <div>Update documentation</div>
                            <div class="fs-sm text-body-secondary">Created on Jan 22, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">In Testing</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-3.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Update Touche</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 14, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          Jessica Miller
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="box"></i>
                          </div>
                          <div class="ms-4">
                            <div>Add Transactions</div>
                            <div class="fs-sm text-body-secondary">Created on Apr 25, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-light text-body-secondary">Backlog</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          Olivia Davis
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-12 col-xxl-4">
            <!-- Goals -->
            <div class="card mb-6">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Goals</h3>
                  </div>
                  <div class="col-auto my-n3 me-n3">
                    <button class="btn btn-sm btn-link" type="button">+ Add</a>
                  </div>
                </div>
              </div>
              <div class="card-body py-3">
                <div class="list-group list-group-flush">
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Increase monthly revenue"
                            aria-valuenow="75"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="75%"
                            style="--bs-progress-circle-value: 75"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Increase monthly revenue</h6>
                        <span class="fs-sm text-body-secondary">$10,000</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Mar 15</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-secondary"
                            role="progressbar"
                            aria-label="Launch new feature"
                            aria-valuenow="50"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="50%"
                            style="--bs-progress-circle-value: 50"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Launch new feature</h6>
                        <span class="fs-sm text-body-secondary">Dark mode</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Oct 01</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-danger"
                            role="progressbar"
                            aria-label="Grow user base"
                            aria-valuenow="45"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="45%"
                            style="--bs-progress-circle-value: 45"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Grow user base</h6>
                        <span class="fs-sm text-body-secondary">75%</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Dec 12</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Improve customer satisfaction"
                            aria-valuenow="60"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="60%"
                            style="--bs-progress-circle-value: 60"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Improve customer satisfaction</h6>
                        <span class="fs-sm text-body-secondary">85%</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Dec 15</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-success"
                            role="progressbar"
                            aria-label="Reduce response time"
                            aria-valuenow="100"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="100%"
                            style="--bs-progress-circle-value: 100"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Reduce response time</h6>
                        <span class="fs-sm text-body-secondary">1hr</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Jan 01</span>
                      </div>
                    </div>
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
                  <li data-icon="thumb_up">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1hr ago</span></h6>
                      <p class="mb-0">Liked a post by @john_doe</p>
                    </div>
                  </li>
                  <li data-icon="chat_bubble">
                    <div>
                      <h6 class="fs-base mb-1">Jessica Miller <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Commented on a photo</p>
                    </div>
                  </li>
                  <li data-icon="share">
                    <div>
                      <h6 class="fs-base mb-1">Emily Thompson <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Shared an article: "Top 10 Travel Destinations"</p>
                    </div>
                  </li>
                  <li data-icon="person_add">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1 day ago</span></h6>
                      <p class="mb-0">Started following @jane_smith</p>
                    </div>
                  </li>
                  <li data-icon="account_circle">
                    <div>
                      <h6 class="fs-base mb-1">Olivia Davis <span class="fs-sm fw-normal text-body-secondary ms-1">2 days ago</span></h6>
                      <p class="mb-0">Updated profile picture</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    





<div class="card mt-4" style='background-color: #37404a;'>
    <div class="card-body">  
        <h4 class='header-title mb-3 text-left' style='color:rgb(170, 184, 197);'>General Overview</h4><hr>
        <p>To start a fresh new election, go to the <a href="election" class="text-secondary">Add Elections</a> tab.</p>
        <p>To setup the <u>positions</u> under their respective elections, go to the <a href="positions" class="text-secondary">Manage Positions & Elections</a> tab.</p>
        <p>For the adding up of the <u>candidates</u>, head to the <a href="contestants" class="text-secondary">Add Contestants</a> tab. </p>
        <p>Go to <a href="contestants" class="text-secondary">Manage Contestants</a> tab to setup the contestants.</p>
        <p>Registrars you wish to add to allow them to vote can be managed at the <a href="registrar" class="text-secondary">Voters</a> tab.</p>
        <p>It is highly recommended to change <b>admin</b>'s  password at the <a href="settings.php?cp=1" class="text-secondary">Change Password</a> tab before conducting an election.</p>
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

            <form id="submitElectionSession" method="POST">
                <div class="modal-body">

                    <?php
                        $query = "SELECT * FROM election WHERE session = ?";
                        $statement = $conn->prepare($query);
                        $statement->execute([0]);
                        $not_started_election_result = $statement->fetchAll();
                        $not_started_election_count = $statement->rowCount();
                        if ($not_started_election_count > 0) {
                    ?>

                        <label class="control-label" for="election-session">Elections</label>
                        <select class="form-control form-control-sm form-control-dark" name="election-session" id="election-session" required="required">
                            <option>Select Election</option> 
                            <?php foreach ($not_started_election_result as $row): ?>
                                <option value="<?= $row["eid"]; ?>"><?= ucwords($row["election_name"]); ?> / <?= ucwords($row["election_by"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                        <div class="form-group">
                            <label class="control-label">Create voting end time session.</label>
                            <input type="datetime-local" name="ctimer" id="ctimer" class="form-control form-control-sm form-control-dark" required>
                        </div>
                    <?php } else { ?>
                        <div class='well'>There aren't any election available to start. You can <a href='election'>add one</a></div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-info">Start!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
    include ('includes/main-footer.inc.php');
    include ('includes/footer.inc.php');
?>

<script type="text/javascript">
    feather.replace();
</script>