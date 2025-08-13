<?php 

	// LOGOUT VOTER
	
	// REQUIRE DATABASE CONNECTION
	require_once("connection/conn.php");

	$query = "
        UPDATE voter_login_details
        SET voter_login_details_status =  ?
        WHERE voter_login_details_id = ?
    ";
    $statement = $conn->prepare($query);
    $result = $statement->execute([1, $_SESSION['voter_login_details_id']]);

    if (isset($result)) {
		unset($_SESSION['voter_accessed']);
		unset($_SESSION['voter_login_details_id']);

		session_destroy();

		header("Location: index");
    }


 ?>
<script>
    // window.top.close();

</script>