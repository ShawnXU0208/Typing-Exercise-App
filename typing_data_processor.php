<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL);

	$userName = $_POST['userName'];
	$speed = $_POST['speed'];
	$mistakes = $_POST['mistakes'];
	$mistakeList = $_POST['mistakeList'];
	$respondList = $_POST['respondList'];
	$occurList = $_POST['occurList'];

	$respondObj = json_decode($respondList);
	$mistakeObj = json_decode($mistakeList);
	$occurObj = json_decode($occurList);

	$totalResponse = 0;
	foreach($respondObj as $key => $value){
		$totalResponse += $value;
	}

	//include config for database, get connecton to the database $conn
	require('database_config.php');
	$query = "SELECT UserID FROM UserEntry WHERE UserName = '$userName'";
	$result = $conn -> query($query);
	if($result -> num_rows == 1){
		$row = $result -> fetch_assoc();
		$userId = $row['UserID'];

		$query = "INSERT INTO GeneralData (UserID, Speed, Mistake, Respond) VALUES ('$userId', '$speed', '$mistakes', '$totalResponse')";
		$result = $conn -> query($query);

		$query = "SELECT * FROM GeneralData WHERE UserID = '$userId'";
		$result = $conn -> query($query);
		if($result -> num_rows > 20){
			$query = "SELECT CreatedTime FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime ASC LIMIT 1";
			$result = $conn -> query($query);
			$row = $result -> fetch_assoc();
			$firstDateTime = $row['CreatedTime'];

			$query = "DELETE FROM GeneralData WHERE CreatedTime = '$firstDateTime' AND UserID = '$userId'";
			$result = $conn -> query($query);
		}
		
		//IN (SELECT CreatedTime FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime ASC LIMIT 1)

		foreach($respondObj as $key => $value){
			$mistakeValue = $mistakeObj -> $key;
			$occurValue = $occurObj -> $key;
			$query = "INSERT INTO DetailedData (UserID, KeyChar, Mistake, Respond, Occur) VALUES ('$userId', '$key', '$mistakeValue', '$value', '$occurValue')";
			$result = $conn -> query($query);

		}
		$query = "SELECT DISTINCT CreatedTime FROM DetailedData WHERE UserID = '$userId'";
		$result = $conn -> query($query);
		if($result -> num_rows > 20){
			$query = "SELECT DISTINCT CreatedTime FROM DetailedData WHERE UserID = '$userId' ORDER BY CreatedTime ASC LIMIT 1";
			$result = $conn -> query($query);
			$row = $result -> fetch_assoc();
			$firstDateTime = $row['CreatedTime'];

			$query = "DELETE FROM DetailedData WHERE CreatedTime = '$firstDateTime' AND UserID = '$userId'";
			$result = $conn -> query($query);
		}

	}

	$conn->close();
	
?>