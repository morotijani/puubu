<?php 

// SEARCH FOR CONTESTANTS

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

$query = "
	SELECT * FROM cont_details 
	INNER JOIN positions 
	ON positions.position_id = cont_details.cont_position 
	LEFT JOIN election 
	ON election.eid = cont_details.election_name 
	";
if ($_POST['query'] != '') {
	$query .= 'WHERE cont_details.cont_indentification LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR cont_details.cont_fname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR cont_details.cont_lname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR cont_details.cont_gender LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR positions.position_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" OR election.election_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" AND cont_details.del_cont = "no" ';
} else {
	$query .= 'WHERE cont_details.del_cont = "no" ORDER BY cont_details.cont_fname ASC ';
}
//$query .= 'WHERE cont_details.del_cont = "no" ORDER BY cont_details.cont_fname ASC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $conn->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $conn->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();

$output = ' 
	<h4 class="mt-2">List - '.$total_data.'</h4>
	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead>
				<tr class="text-center">
					<th>#</th>
		            <th>Contestant</th>
		            <th>ID / Ballot Number</th>
		            <th>Gender</th>
		            <th>Position</th>
		            <th>Election</th>
		            <th>Option</th>
				</tr>
    		</thead>
    		<tbody>
';

if ($total_data > 0) {
	$i = 1;
	foreach ($result as $row) {
		$option = '';
		$optionStatus = '';
		if ($row["session"] == 0) {
			$option = '
				<a href="contestants.php?deletecontestant='.$row["cont_id"].'&del='.(($row["del_cont"] == 'yes')?'no':'yes').'" class="btn btn-danger btn-sm">
					<span class="material-symbols-outlined me-1">delete</span> Delete
				</a>&nbsp;
				<a href="contestants.php?editcontestant='.$row["cont_id"].'" class="btn btn-sm btn-success">
					<span class="material-symbols-outlined me-1">stylus_note</span> Edit
				</a>
			';
		} else if ($row["session"] == 1) {
			$optionStatus = '<span class="badge bg-warning-subtle text-warning">running ...</span>';
		} else if ($row["session"] == 2) {
			$optionStatus = '<span class="badge bg-danger-subtle text-danger">ended</span>';
		}
		$output .= '
			<tr class="text-center">
                <td>' . $i . '</td>
				<td>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                          	<img class="avatar-img" src="../media/uploadedprofile/'.$row["cont_profile"].'" alt="'.ucwords($row["cont_fname"]).'" />
                        </div>
						<div class="ms-4">
							<div>'.ucwords($row["cont_fname"].' '.$row["cont_lname"]).'</div>
							<div class="fs-sm text-body-secondary">
								<span class="">' . $optionStatus . '</span>
							</div>
						</div>
					</div>
				</td>
                <td>'.$row["cont_indentification"] . '</td>
                <td>'.ucwords($row["cont_gender"]).'</td>
                <td>'.ucwords($row["position_name"]).'</td>
                <td>'.ucwords($row["election_name"]).' ~<span class="text-muted"> '.ucwords($row["election_by"]).'</span></td>
                <td>
                    ' . $option . '
                </td>
			</tr>
		';
		$i++;
	}

} else {
	$output .= '
		<tr class="text-warning">
			<td colspan="8">No data found!</td>
		</tr>
	';
}
$output .= '
    		</tbody>
		</table>
	</div>
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
					<a class="page-link" href="javascript:;">'.$page_array[$count].' <span class="d-none">(current)</span></a>
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
