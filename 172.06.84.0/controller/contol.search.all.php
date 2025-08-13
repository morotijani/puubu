<?php 

	// DATABASE CONNECTION
	require_once("../../connection/conn.php");

	$limit = 4;
	$page = 1;

	if ($_POST['page'] > 1) {
		$start = (($_POST['page'] - 1) * $limit);
		$page = $_POST['page'];
	} else {
		$start = 0;
	}

	$query = "SELECT * FROM registrars ";
	if ($_POST['query'] != '') {
		$query .= 'WHERE std_fname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR std_lname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR std_id LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
	}
	$query .= 'ORDER BY id ASC ';

	$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

	$statement = $conn->prepare($query);
	$statement->execute();
	$total_data = $statement->rowCount();

	$statement = $conn->prepare($filter_query);
	$statement->execute();
	$result = $statement->fetchAll();

	$output = ' 
		<label>Total Recods - '.$total_data.'</label>
		<table class="table-striped table table-bordered">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>STDID</th>
			</tr>
	';

	if ($total_data > 0) {
		
		foreach ($result as $row) {
			$output .= '
				<tr>
					<td>'.$row["id"].'</td>
					<td>'.$row["std_fname"].'</td>
					<td>'.$row["std_id"].'</td>
				</tr>
			';
		}

	} else {
		$output .= '
			<tr colspan="3" align="center" class="text-warning">No data found</tr>
		';
	}
	$output .= '
		</table>
		<br>';

	if ($total_data > 0) {
		$output .= '<div align="center">
			<ul class="pagination">
		';
		$total_links = ceil($total_data / $limit);

		$previous_link = '';
		$next_link = '';
		$page_link = '';

		if ($total_links > 4) {
			if ($page < 5) {
				for ($count = 1; $count <= 5; $count++) {
					$page_array[] = $count;
				}
				$page_array[] = '...';
				$page_array[] = $total_links;
			} else {
				$end_limit = $total_links - 5;
				if ($page > $end_limit) {
					$page_array[] = 1;
					$page_array[] = '...';

					for ($count = $end_limit; $count <= $total_links; $count++) {
						$page_array[] = $count;
					}
				} else {
					$page_array[] = 1;
					$page_array[] = '...';
					for ($count = $page - 1; $count <= $page + 1; $count++) {
						$page_array[] = $count;
					}
					$page_array[] = '...';
					$page_array[] = $total_links;
				}
			}
		} else {
			for ($count = 1; $count <= $total_links; $count++) {
				$page_array[] = $count;
			}
		}

		for ($count = 0; $count < count($page_array); $count++) {
			if ($page == $page_array[$count]) {
				$page_link .= '
					<li class="page-item active">
						<a class="page-link" href="javascript:;">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
					</li>
				';

				$previous_id = $page_array[$count] - 1;
				if ($previous_id > 0) {
					$previous_link = '
						<li class="page-item">
							<a class="page-link page-link-go" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
						</li>
					';
				} else {
					$previous_link = '
						<li class="page-item disabled">
							<a class="page-link page-link-go" href="javascript:;">Previous</a>
						</li>
					';
				}

				$next_id = $page_array[$count] + 1;
				if ($next_id >= $total_links) {
					$next_link = '
						<li class="page-item disabled">
							<a class="page-link page-link-go" href="javascript:;">Next</a>
						</li>
					';
				} else {
					$next_link = '
						<li class="page-item">
							<a class="page-link page-link-go" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
						</li>
					';
				}

			} else {
				
				if ($page_array[$count] == '...') {
					$page_link .= '
						<li class="page-item disabled">
							<a class="page-link" href="javascript:;">...</a>
						</li>
					';
				} else {
					$page_link .= '
						<li class="page-item">
							<a class="page-link page-link-go" href="javascript:(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
						</li>
					';
				}
			}

		}

		$output .= $previous_link. $page_link . $next_link;
	}
	echo $output;




 ?>