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