<?php
	include_once 'processes/db_connection.php';
	//This is for selecting states basing on the selection of the Country
	if(isset($_POST['countyCode'])) {
		$countyCode = $_POST['countyCode'];
		$sub_county_select = "SELECT * FROM sub_county WHERE countyCode = '$countyCode' ORDER BY subCounty";
		$result_sub_county_select = $conn->prepare($sub_county_select);
		$result_sub_county_select->execute();

		?>
			<select name="sub_county" class="form-control">
				<option value="" selected hidden>Select State</option>
				<?php
				while ($row = $result_sub_county_select->fetch()) {
					?>
							<option value="<?php echo $row['id'];?>"><?php echo $row['subCounty'];?></option>
						<?php
				}
					
				?>
			</select>
		<?php
	}



?>