<?php
	
	//include config for database, get connecton to the database $conn
	require('database_config.php');

	//for creating object that contains login error message
	class LoginError{
		public $usernameError = NULL;
		public $passwordError = NULL;
	}

	//for creating object that contains register error message
	class RegisterError extends LoginError{
		public $rePasswordError = NULL;
	}

	function registerValidation($userName, $password, $rePassword, $conn){
		// check input for register
		$errorObj = new RegisterError;

		$query = "SELECT UserID FROM UserEntry WHERE UserName = '$userName'";
		$result = $conn -> query($query);
		//check if username has already existed
		if($result -> num_rows == 1){
			$errorObj -> usernameError = 'user name has been taken!';
		}elseif($password != $rePassword){
			//check if the re-entered password is the same the password that user entered
			$errorObj -> rePasswordError = 'incorrect password!';
		}else{
			//store user informaiton into database when it's correct
			$query = "INSERT INTO UserEntry (UserName, Password) VALUES ('$userName', '$password')";
			$conn -> query($query);
		}

		$conn->close();

		//encode the error message obejct, and send it to client side
		$myJSON = json_encode($errorObj); 
		echo $myJSON;
	}

	function loginValidation($userName, $password, $conn){
		//check input for login
		$errorObj = new LoginError;

		$query = "SELECT UserID FROM UserEntry WHERE UserName = '$userName'";
		$result = $conn -> query($query);
		if($result -> num_rows == 0){
			//check username
			$errorObj -> usernameError = 'incorrect username!';
		}else{
			//check password
			$query = "SELECT Password FROM UserEntry WHERE UserName = '$userName'";
			$result = $conn -> query($query);
			$row = $result -> fetch_assoc();
			if($password != $row['Password']){
				$errorObj -> passwordError = 'incorrect password!';
			}
		}

		$conn->close();

		//encode the error message obejct, and send it to client side
		$myJSON = json_encode($errorObj);
		echo $myJSON;

	}

	$actionType = $_POST['actionType'];
	//check if user is trying to login or register, and execute the corresponding validation function
	if($actionType == 'register'){
		registerValidation($_POST['userName'], $_POST['password'], $_POST['rePassword'], $conn);
	}elseif($actionType == 'login'){
		loginValidation($_POST['userName'], $_POST['password'], $conn);
	}
?>