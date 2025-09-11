<?php 


	require_once ("../connection/conn.php");
	
    $message = "logged out from system";
	add_to_log($message, $admin_id, 'admin');

	unset($_SESSION['crAdmin']);

	redirect(PROOT . '172.06.84.0/signin');
