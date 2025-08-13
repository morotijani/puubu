<?php 

require_once ("../connection/conn.php");


	if (isset($_POST['theElection_type'])) {

		// $query = "SELECT * FROM election_type WHERE session = '1' LIMIT 1";
		// $statement = $conn->prepare($query);
		// $statement->execute();
		// $electionNameResult = $statement->fetchAll();

		if ($started_election > 0) {

		    $electionId = $_POST['theElection_type'];
		    $query = "SELECT * FROM positions WHERE election_id = ".$electionId."";
		    $statement = $conn->prepare($query);
		    $execute = $statement->execute();
		    $election_result = $statement->fetchAll();
		    $row_count = $statement->rowCount();

			if (!isset($execute)) {
			    echo "<p>There was an error. Please try again</p>";
			    echo "<a class='btn btn-info' href='../votestart'>Okay ...</a>";
			} else {

				if ($row_count > 0) {
	    
				    foreach ($election_result as $eachPosition) {
				      $positions[] = $eachPosition['position_id']; 
				    }

				    echo "<form action='controller/control.add.vote.count.php' method='POST'>";
				    for ($num = 0; $num < $row_count; $num++) {

					    $sql = "SELECT * FROM cont_details WHERE cont_position = '".$positions[$num]."' AND election_name = '".$electionId."' AND del_cont = 'no'";
					    $statement = $conn->prepare($sql);
					    $statement->execute();
					    $result_ = $statement->fetchAll();
					    $countRow = $statement->rowCount();
					    if ($countRow < 1) {

					        $sqlP = "SELECT * FROM positions WHERE position_id = '".$positions[$num]."'";
					        $statement = $conn->prepare($sqlP);
					        $statement->execute();
					        $resuP = $statement->fetchAll();
					        foreach ($resuP as $row_p) {
					          	echo "<span class='btn btn-secondary'>".ucwords($row_p['position_name'])."</span><hr>";
					          	echo "<p class='lead' style='border-left: 8px solid red; padding: 10px;'>There are no contestants for this position</p><br><br><br><br>";
						       	echo "<input type='hidden' name='name-of-positions".$num."'>";
								echo '<input type="hidden" name="contestant'.$num.'">';
								echo '<input type="hidden" name="onecont'.$num.'">';
					        }

					    } else {

					        $sql1 = "SELECT * FROM positions WHERE position_id = '".$positions[$num]."'";
					        $statement = $conn->prepare($sql1);
					        $statement->execute();
					        $resu = $statement->fetchAll();
					        foreach ($resu as $key) {
						        echo '
						        <div class="container">
						            <span class="btn btn-secondary">'.ucwords($key['position_name']).'</span>
						        </div><hr>';
						        echo "<input type='hidden' name='name-of-positions".$num."' value='".$positions[$num]."'>";
					        }
				        	echo '<section id="votestart" class="container-fluid">
				        				<div class="swiper-container">
				            				<div class="swiper-wrapper">';
				        						foreach ($result_ as $row) {

				        							$sql8 = "SELECT COUNT(*) count_pc FROM cont_details WHERE cont_position = :cont_position AND del_cont = :del_cont";
				        							$statement = $conn->prepare($sql8);
				        							$statement->execute(
				        								[
				        									':cont_position' => $row['cont_position'],
				        									':del_cont' => 'no'
				        								]
				        							);
				        							$sql8_count = $statement->rowCount();
				        							$sql8_result = $statement->fetchAll();


								          			echo '	<div class="swiper-slide">
											          			<div class="card card-profile" style="border-radius: 12px; margin-bottom: 2rem !important;">
											                    	<div class="card-cover"></div>
												                    <div class="card-avatar border-white">
												                      	<a href="javascript:;">
												                        	<img src="./media/uploadedprofile/'.$row["cont_profile"].'" alt="'.ucwords(strtolower($row['cont_fname'] .' '. $row['cont_lname'])).'">
												                      	</a>
												                    </div>
												                    <div class="card-body">
												                      	<h4 class="card-title font-weight-lighter">'.ucwords($row['cont_fname'] .' '. $row['cont_lname']).'</h4>
												                      	<h6 class="card-category">#'.strtoupper($row["cont_indentification"]).'</h6>
												                      	<p class="card-description">
												                      	';
												                      	foreach ($sql8_result as $row8) {
												                      		if ($row8['count_pc'] > 1) {
													                      		echo '<div class="radio-toolbar">
																			    	<input type="radio" id="'.$row["cont_id"].'" name="contestant'.$num.'" value="'.$row["cont_id"].'" class="btn btn-outline-success">
																			    	<label for="'.$row["cont_id"].'">Vote Now!</label>
																			    </div>';
													                      	} else {
													                      		echo '<input type="radio" name="onecont'.$num.'" id="'.$row["cont_id"].'" value="yes,'.$row["cont_id"].'"> <label for="'.$row["cont_id"].'" class="text-info">Yep &nbsp;';
													                      		echo '<input type="radio" name="onecont'.$num.'" id="'.$row["cont_id"].'" value="no,'.$row["cont_id"].'"> <label for="'.$row["cont_id"].'" class="text-danger">Nah';
													                      	}
												                      	}
																			
																		    echo '
												                      	</p>
												                    </div>
												                </div>
											               </div>';
								        }
								        echo '			</div>';
								        foreach ($sql8_result as $row8) {
												if ($row8['count_pc'] > 1) {
													echo '
														<div class="swiper-pagination" style="margin-top: 7.5rem;"></div>
								        				<div class="swiper-button-next"></div>
    													<div class="swiper-button-prev"></div>
													';
												} else {
													echo '';
												}
											}
								        				
								        			echo '</div>
								        		</section>';
						}
					}
					echo "	<input type='hidden' name='number-of-positions' value='".$row_count."'>
							<input type='hidden' name='name-of-election' value='".$electionId."'>
							<div align='center'>
								<button type='submit' name='submitVotes' id='submitVotes' class='btn btn-outline-dark no-radius btn-lg'><i data-feather='thumbs-up'></i> Send All Votes</button>
							</div>
						</form>";

				} else {
					echo "<div class='card'><h4 class='card-header text-center font-weight-lighter'>Oops... No Position Under This Election</h4></div>";
				}
			}
		} else {
		  	echo "<div class='card shadow' style='border-left: 6px solid #cddc39; padding: 10px;'>
		  			<p class='mt-2'>Admin Has Not Yet Started An Election, Please have Patience. you can <a href='logout.php' class='badge badge-info'>logout</a> here</p></div><br>";
		}
	} else {
	  $_SESSION['echo'] = "No Election Avaliable";
	  header("Location: ../votestart.php");
	}

?>

  <script type="text/javascript" src="dist/js/feather.min.js"></script>

  <script type="text/javascript">
    feather.replace();
  </script>

<script type="text/javascript">

      var swiper = new Swiper('.swiper-container', {
      	spaceBetween: 30,
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        coverflowEffect: {
          rotate: 30,
          stretch: 0,
          depth: 500,
          modifier: 1,
          slideShadows: true,
        },
        pagination: {
          el: '.swiper-pagination',
          dynamicBullets: true,
        },
        navigation: {
	        nextEl: '.swiper-button-next',
	        prevEl: '.swiper-button-prev',
	      },
      });

</script>