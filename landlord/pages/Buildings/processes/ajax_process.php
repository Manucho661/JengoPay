<?php
	//Database Connection
	include_once 'db_connection.php';

	//Select Constituency Name basing on the County Selected
	if($_POST['county_id']) {
		$select_constituency = "SELECT * FROM constituencies WHERE county_id = " . $_POST['county_id'];
		$result_const = $conn->prepare($select_constituency);
		$result_const->execute();
		if ($result_const->rowCount() > 0) {
			echo '<option value = "" required>-- Select Constituency --</option>';
			while($row_one = $result_const->fetch()) {
				echo '<option value='.$row_one['id']. ' ' .$row_one['name'].'>'.$row_one['name'].'</option>';
			}
		} else {
			echo '<option>No Constituency Found</option>';
		}
		//Select the Ward basing on the Constituency Selected
	} else if (isset($_POST['constituency_id'])) {
		$select_ward = "SELECT * FROM ward WHERE constituency_id = ".$_POST['constituency_id'];
		$result_ward = $conn->prepare($select_ward);
		$result_ward->execute();
		if ($result_ward->rowCount() > 0) {
			echo '<option value = "" required>-- Select Ward --</option>';
			while($row_one = $result_ward->fetch()) {
				echo '<option value='.$row_one['id']. ' ' .$row_one['name'].'>'.$row_one['name'].'</option>';
			}
		} else {
			echo '<option>No Constituency Found</option>';
		}
	}
?>