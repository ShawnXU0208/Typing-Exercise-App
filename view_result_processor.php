<?php
	//include config for database, get connecton to the database $conn
	require('database_config.php');
	$userName = $_POST["userName"];

	$query = "SELECT UserID FROM UserEntry WHERE UserName = '$userName'";
	$result = $conn -> query($query);
	if($result -> num_rows == 1){
		$speedArray = new array();
		$mistakeRateArray = new array();
		$averageResponseArray = new array();
		$averageRateArray = new array();

		$row = $result -> fetch_assoc();
		$userId = $row['UserID'];

		$query = "SELECT Speed, Mistake, Respond FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime";
		$result = $conn -> query($query);
		while($row = $result -> fetch_assoc()){
			 $speedArray[] = $row['Speed'];
			 $mistakeRateArray[] = $row['Mistake'];
			 $averageResponseArray[] = $row['Respond'];
		}
	}

?>


			$query = "SELECT * FROM DetailedData WHERE UserID = '$userId' AND KeyChar = '$key'";
			$result = $conn -> query($query);
			if($result -> num_rows > 20){
				$query = "SELECT CreatedTime FROM DetailedData WHERE UserID = '$userId' AND KeyChar = '$key' ORDER BY CreatedTime ASC LIMIT 1";
				$result = $conn -> query($query);
				$row = $result -> fetch_assoc();
				$firstDateTime = $row['CreatedTime'];

				$query = "DELETE FROM DetailedData WHERE CreatedTime = '$firstDateTime' AND UserID = '$userId' AND KeyChar = '$key'";
				$result = $conn -> query($query);
			}