<?php 

	// PROCESS IMPORTED CSV FILE FROM DATABASE

	require_once("../../connection/conn.php");

	$query = "SELECT * FROM registrars";
	$statement = $conn->prepare($query);
	$statement->execute();
	echo $statement->rowCount();
