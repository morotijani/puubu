<?php

    // REQUIRE DATABASE CONNECTION
	require_once("connection/conn.php");

    unset($_SESSION['voter_accessed']);
	unset($_SESSION['voter_login_details_id']);

	session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="media/puubu.favicon.png" type="image/x-icon" />
  
    <!-- Libs CSS -->
    <link rel="stylesheet" href="dist/css/libs.bundle.css" />
  
    <!-- Main CSS -->
    <link rel="stylesheet" href="dist/css/index.bundle.css" />
    <link rel="stylesheet" href="dist/css/puubu.css" />
  
    <!-- Title -->
    <title>Election ended • Puubu</title></head>
<body>
	
	<div class="offcanvas-wrap">

    	<!-- hero -->
	    <section class="overflow-hidden py-15 py-xl-20">
	      	<div class="container">
	        	<div class="row align-items-end mb-10 mt-5">
	          		<div class="col-lg-8">
	            		<h1>ooopps... sorry <span class="text-danger">election just ended</span>.</h1>
	            		<p class="lead mb-3"><a href="logout" class="text-secondary">... all right then.</a></p>
	          		</div>
	          		<div class="col-lg-4 text-lg-end">
	          			<img src="media/joyride.svg" class="img-fluid" width="40%" height="40%">
	         		</div>
	        	</div>
	      	</div>
		</section>
	</div>

    <script src="dist/js/vendor.bundle.js"></script>
    <script src="dist/js/index.bundle.js"></script>
</body>
</html>