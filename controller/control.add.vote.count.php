<?php 

require_once("../connection/conn.php");
$msg = '';

// REDIRECT VOTER IF NOT LOGGEDIN
if (!isset($_SESSION["voter_accessed"])) {
	redirect(PROOT . 'signin');
	exit();
} else {

	if ($started_election > 0) {
        $electionStarted = ucwords($sub_row['election_name']) . ' Elections';
        $electionBy = ucwords($sub_row['election_by']);
    }

	// CHECK IF LOGGED IN VOTER REALLY EXIST INOUR DATABASE
	$voterId = sanitize($_SESSION['voter_accessed']);
	$voterQuery = "
	    SELECT * FROM registrars 
	    INNER JOIN election 
	    ON election.election_id = registrars.registrar_election 
	    WHERE registrars.voter_id = ? 
	    AND election.election_id = registrars.registrar_election 
	    AND election.session = ?
	    LIMIT 1
	";
	$statement = $conn->prepare($voterQuery);
	$statement->execute([$voterId, 1]);
	$voter_count = $statement->rowCount();
	$voter_result = $statement->fetchAll();

	// IF LOGGED IN VOTER DO NOT EXIST REDIRECT TO LOGIN PAGE
	if ($voter_count < 1) {
		redirect(PROOT . 'signin');
		exit();
	} else {
		foreach ($voter_result as $voter_row) {}
	}

		// SUBMITED VOTES
	if (isset($_POST['submitVotes'])) {
		$election = $_POST['name-of-election'];

		// SELECT FROM "votehasdone" TO CHECK IF VOTER HAS ALREADY VOTED
		$checkVoterhasdone = "
			SELECT * FROM voterhasdone 
			WHERE voter_id = ? 
			AND election_id = ?
		";
		$statement = $conn->prepare($checkVoterhasdone);
		$statement->execute([$voterId, $election]);
		$result_checkVoterhasdone = $statement->fetchAll();
		$count_checkVoterhasdone = $statement->rowCount();

			//  CHECK IF VOTER HAS ALREADY VOTED
		if ($count_checkVoterhasdone > 0) {
			$msg = "<span class='text-danger text-shadow'>Ooops... It seems you've already voted!</span>";
		} else {
			$i = 0;
			$numberOfPositions = $_POST['number-of-positions'];

			for (; $i < $numberOfPositions; $i++) {

				$nameOfPositions[$i] = $_POST['name-of-positions'.$i];

				$q = "
					SELECT * FROM positions 
					WHERE position_id = ?
				";
				$statement = $conn->prepare($q);
				$statement->execute([$nameOfPositions[$i]]);
				$position_result = $statement->fetchAll();

				// IF THERE IS AN EMPTY VOTES
				if (empty(isset($_POST['contestant'.$i])) && empty(isset($_POST['onecont'.$i]))) {
					foreach ($position_result as $key_row) {
					    
					    // UPDATE POSITION VOTES
					    $skipped_votes = $key_row['position_skipped_votes'] + 1;
						$skipped_votes_sql = "
						    UPDATE positions 
						    SET position_skipped_votes = ? 
						    WHERE position_id = ?
						";
						$statement = $conn->prepare($skipped_votes_sql);
						$skipped_votes_result = $statement->execute([
						    $skipped_votes, $key_row['position_id']
						 ]);
						 
						 if ($skipped_votes_result) {
						     $msg .= '
        						<li class="mt-1">
									<a href="javascript:;" class="card bg-white card-hover-border text-danger">
										<div class="card-body py-4">
											<div class="row align-items-center g-2 g-md-4 text-center text-md-start">
												<div class="col-md-9">
													<p class="fs-lg mb-0">You did not vote for the position</p>
													<ul class="list-inline list-inline-separated text-muted">
														<li class="list-inline-item">'.$electionStarted.'</li>
														<li class="list-inline-item">'.$electionBy.'</li>
													</ul>
													</div>
													<div class="col-md-3 text-lg-end">
													<span>'. ucwords($key_row['position_name']) .'</span>
												</div>
											</div>
										</div>
									</a>
        						</li> ';
						 }
					}
				} else {
					$votedPerson[$i] = ((isset($_POST['contestant'.$i]) != '')?$_POST['contestant'.$i] : '');
					$votedPersonYN[$i] = ((isset($_POST['onecont'.$i]) != '')?$_POST['onecont'.$i] : '');
					list($exOptionYN, $exOptionID) = explode(',', $votedPersonYN[$i].',,,,');
					if ($exOptionYN == 'no' || $exOptionYN == 'yes') {
						$findContestant = $exOptionID;
					} else {
						$findContestant = $votedPerson[$i];
					}

					// CHECK IF VOTE TO CONTESTANT ALREDY EXIST
					$queryVotecount = "
						SELECT * FROM vote_counts 
						WHERE contestant_id = ?
						AND position_id = ? 
						AND election_id = ?
					";
					$statement = $conn->prepare($queryVotecount);
					$statement->execute([
						$findContestant,
						$nameOfPositions[$i],
						$election
					]);
					$result = $statement->fetchAll();
					$c = $statement->rowCount();
					$addnewVote = '';
					if ($c > 0) {
						foreach ($result as $row) {
							$newVote = $row['results'] + 1;
							$newVote_no = $row['results_no'] + 1;
							if ($votedPersonYN[$i]) {
								$newVote = (($exOptionYN == 'yes')?$newVote:$row['results']);
							}
							$dataUpdate = array(
								':results' => $newVote,
								':results_no' => (($exOptionYN == 'no')?$newVote_no:$row['results_no']),
								':contestant_id' => (($exOptionYN == 'no' || $exOptionYN == 'yes')?$exOptionID:$votedPerson[$i]),
								':position_id' => $nameOfPositions[$i],
								':election_id' => $election
							);

							// IF CONTESTANT ALREDY EXIST THEN UPDATE "result" BY ADDING +1
							$query_newVote = "
								UPDATE vote_counts 
								SET results = :results, results_no = :results_no 
								WHERE contestant_id = :contestant_id 
								AND position_id = :position_id 
								AND election_id = :election_id
							";
							$statement = $conn->prepare($query_newVote);
							$addnewVote = $statement->execute($dataUpdate);
							
						 	// UPDATE USER VOTE STATUS
							$sql = "
								UPDATE registrars 
								SET status = :status 
								WHERE voter_id = :id
							";
							$statement = $conn->prepare($sql);
							$statement->execute(
								[
									':status' => '1',
									':id' => $voterId
								]
							);
						}
					}

					//DISPLAY ERROR IF SQL WAS NOT ABLE TO PASS THROUGH
					if (!isset($addnewVote)) {
						$msg = "There was an error... go back and retry.<br>";	
						$msg = "<a href='..\signin'>retry<a>";	
					} else {
						// $q = "
						// 	SELECT * FROM positions 
						// 	WHERE position_id = ?
						// ";
						// $statement = $conn->prepare($q);
						// $statement->execute([$nameOfPositions[$i]]);
						foreach ($position_result as $sub_row) {
							$votedforQ = "
								INSERT INTO voted_for (for_id, voter_id, election_id, position_id, candidate_id, voted_location, voted_ip)
								VALUES (:for_id, :voter_id, :election_id, :position_id, :candidate_id, :voted_location, :voted_ip)
							";
							$statement = $conn->prepare($votedforQ);
							$votedfor_result = $statement->execute(
								array(
									':for_id' 			=> guidv4(),
									':voter_id' 		=> $voterId,
									':election_id' 		=> $election,
									':position_id' 		=> $nameOfPositions[$i],
									':candidate_id' 	=> (($exOptionYN == 'no' || $exOptionYN == 'yes') ? $exOptionID : $votedPerson[$i]),
									':voted_location' 	=> $details->city . ', ' . $details->country,
									':voted_ip' 		=> $details->ip
								)
							);
							if ($votedfor_result) {

								// DISPLAY MESSAGE ON VOTED POSITIONS
								$msg .= '
									<li class="mt-1">
										<a href="javascript:;" class="card bg-white card-hover-border">
											<div class="card-body py-4">
												<div class="row align-items-center g-2 g-md-4 text-center text-md-start">
													<div class="col-md-9">
														<p class="fs-lg mb-0">Voted for the position</p>
														<ul class="list-inline list-inline-separated text-muted">
															<li class="list-inline-item">'.$electionStarted.'</li>
															<li class="list-inline-item">'.$electionBy.'</li>
														</ul>
													</div>
													<div class="col-md-3 text-lg-end">
														<span>'. ucwords($sub_row['position_name']) .'</span>
													</div>
												</div>
											</div>
										</a>
									</li>
								';
							}
						}
					}
				}

			}
			// INSERT VOTER ID TO "voterhasdone" IN ORDER TO PREVENT MULTIPLE VOTES.
			$voterHasDone = "
				INSERT INTO voterhasdone (vhd_id, voter_id, election_id) 
				VALUES (?, ?, ?)
			";
			$statement = $conn->prepare($voterHasDone);
			$result_voterHasDone = $statement->execute([guidv4(), $voterId, $election]);

			if ($result_voterHasDone) {
				$aftervoteQ = "
				    SELECT * FROM voted_for
                    INNER JOIN cont_details
                    ON cont_details.contestant_id = voted_for.candidate_id
                    INNER JOIN positions
                    ON positions.position_id = voted_for.position_id
                    WHERE voted_for.voter_id = ?
                    AND voted_for.election_id = ?
				";
				$statement = $conn->prepare($aftervoteQ);
	            $statement->execute([$voterId, $election]);
	            $aftervote_result = $statement->fetchAll();
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$body = '
				 	<html>
					<head>
						<style>
							body { font-family: Arial, sans-serif; color: #333; }
							.header { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
							.position { color: #0056b3; font-weight: bold; }
							.candidate { margin-left: 10px; }
							.footer { margin-top: 20px; font-size: 14px; color: #666; }
						</style>
					</head>
					<body>
						<p class="header">Dear ' . ucwords($voter_row["std_fname"]) . ',</p>

						<p>Thank you for casting your vote in the <strong>' . $electionStarted . '</strong> election held on <strong>' . date("F j, Y") . '</strong>.</p>

						<p>Your choices have been recorded successfully. Here\'s a summary of the candidates you voted for:</p>

						<ul style="list-style-type: none; padding-left: 0;">
					';
				
				
				$positions_shown = [];
				foreach ($aftervote_result as $aftervote_row) {
					$position = $aftervote_row['position_name'];
					// Prevent duplicate display for same position
					if (!in_array($position, $positions_shown)) {
						$candidate_name = ucwords($aftervote_row['cont_fname'] . ' ' . $aftervote_row['cont_lname']);
						// $body .= '<span style="color: blue; font-weight: bolder;">' . ucwords($position) . '</span> ~ ' . $candidate_name . '<br>';
						// $positions_shown[] = $position;

						$body .= '<li><span class="position">' . ucwords($position) . ':</span><span class="candidate">' . $candidate_name . '</span></li>';

					}
				}
				$body .= '
						</ul>

						<p>We appreciate your participation in shaping the future of our community. The election results will be announced shortly after voting closes.</p>

						<p class="footer">
							If you have any questions or concerns, feel free to contact the Electoral Committee.<br>
							<em>This is an automated message. Please do not reply directly to this email.</em>
						</p>

						<p>Warm regards,<br>
						The Electoral Committee</p>
					</body>
					</html>
				';

                $to   = $voter_row["std_email"];
				$subject = 'Done Voting.';
				try {
					send_email($to, $subject, $body);

					$log_message = "voter ['" . ucwords($voter_row["std_fname"] . ' ' . $voter_row["std_lname"]) . "'], has completed voting!";
            		add_to_log($log_message, $voterId, 'admin');
				} catch (Exception $e) {
                    $displayErrors = "Please check you internet connection or contact Puubu.";
    			}
			}
		}
	} else {
			// IF VOTES WERE NOT SUBITTED, REDIRECT TO INDEX PAGE
		redirect(PROOT . "index");
	}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?= PROOT; ?>media/puubu.favicon.png" type="image/x-icon" />

	<!-- Libs CSS -->
	<link rel="stylesheet" href="<?= PROOT; ?>dist/css/libs.bundle.css" />

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?= PROOT; ?>dist/css/index.bundle.css" />

	<!-- Title -->
	<title>Voted for • Puubu</title></head>
<body>


	<!-- navbar -->
	<nav class="navbar navbar-expand-lg navbar-sticky">
		<div class="container">
			<a href="votingon" class="navbar-brand">
				<img src="<?= PROOT; ?>media/puubu.logo.png" alt="Logo">
			</a>
			<ul class="navbar-nav navbar-nav-secondary order-lg-3">
				<li class="nav-item">
					<a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="javascript:;">
						<a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="logout">
							<span class="bi bi-box-arrow-down-left"></span>
						</a>
					</a>
				</li>
			</ul>
		</div>
	</nav>

	<section class="py-15 py-xl-20 bg-light">
		<div class="container">
			<div class="row align-items-end mb-5">
				<div class="col-lg-8">
					<h2 class="fw-light"><span class="fw-bold"><?= ucwords($voter_row['std_fname']); ?></span> your vote is completed!</h2>
				</div>
			</div>

			<div class="row justify-content-between">
				<div class="col">
					<ul class="list-unstyled">
						<?= $msg; ?>
					</ul>
				</div>
			</div>

			<div class="row mt-3">
				<div class="col-12">
					<a href="<?= PROOT; ?>thankyou" class="btn btn-warning">Procceed >>.
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- footer -->
	<footer class="py-15 py-xl-20 border-top">
		<div class="container">
			<div class="row g-2 g-lg-6 mb-8">
				<div class="col-lg-6">
					<h4>Puubu Inc. <br>Ghana</h4>
					<p class="small">Copyrights &copy; 2021</p>
				</div>
				<div class="col-lg-6 text-lg-end">
					<span class="h5">+233 24 044 5410</span>
				</div>
			</div>

		</div>
	</footer>


	<!-- javascript -->
	<script src="<?= PROOT; ?>dist/js/vendor.bundle.js"></script>
	<script src="<?= PROOT; ?>dist/js/index.bundle.js"></script>
</body>
</html>