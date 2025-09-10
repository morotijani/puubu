<?php 


	require_once ("../connection/conn.php");

	unset($_SESSION['crAdmin']);

	redirect(PROOT . '172.06.84.0/signin');
