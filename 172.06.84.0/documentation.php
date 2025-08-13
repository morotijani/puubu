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
        <p>It is highly recommended to change <b>admin</b>'s  password at the <a href="settings.php?cp=1">Change Password</a> tab before conducting an election.</p>
    </div>
</div>

<?php 
    include ('includes/main-footer.inc.php');
    include ('includes/footer.inc.php');
?>

<script type="text/javascript">
    feather.replace();
</script>