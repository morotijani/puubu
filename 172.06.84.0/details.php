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


 

?>





    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="text-white" style="font-size: 18px;">Dashboard</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="index" class="btn btn-sm btn-outline-warning">Go Back</a>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <a href="settings" class="btn btn-sm btn-link float-right mb-3">
                Update <span data-feather="edit" class="ml-1"></span>
            </a>
            <h4 class="header-title mt-2" style="color: rgb(170, 184, 197);">Your Profile</h4>
            <hr>
            <div class="container">
                <?= get_admin_profile(); ?>
            </div>
        </div>

    </div>
<?php include ('includes/main-footer.inc.php');?>

<!-- FOOTER -->
<script type="text/javascript" src="media/files/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="media/files/popper-1.14.6.min.js"></script>
<script type="text/javascript" src="media/files/bootstrap.min.js"></script>
<script type="text/javascript" src="media/files/feather.min.js"></script>
<script>
  feather.replace()
</script>
<script>
  $(document).ready(function() {
    $("#temporary").fadeOut(5000);
});
</script>
</body>
</html>
