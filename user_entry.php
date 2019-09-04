

<!DOCTYPE html>

<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/user_entry.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
</head>

<body>
	<div class = 'log-in'>
		<img src = 'imgs/cancel.png' id = 'cancel-login'>
		<img src = 'imgs/users.png' />
		<h1>Type Type member Login</h1>

		<!--The form for user entry-->
		<form id = 'login-form' method = 'post'>
			<input id = 'userName' type = 'text' name = 'userName' placeholder = "Username" required = "required"><br>
			<input id = 'password' type = 'password' name = 'password' placeholder = "Password" required = "required"><br>
			<input id = 're-password' type = 'password' name = 're-password' placeholder = "Re-enter password" required = "required" style = "visibility:hidden"><br>
			<input id = 'login-button' type = 'submit' value = 'Log in'>
			<input id = 'register-button' type = 'submit' value = 'Register'>
		</form>
	</div>

</body>

<script>

	const resetForm = function(){
		//the function to reset the login/register form

		//clear all the fields
		document.getElementById('login-form').reset(); 

		//clear all the error information of each field (remove from its class attributes)
		document.getElementById('userName').classList.remove('empty');
		document.getElementById('userName').classList.remove('invalid');
		document.getElementById('password').classList.remove('empty');
		document.getElementById('password').classList.remove('invalid');
		document.getElementById('re-password').classList.remove('empty');
		document.getElementById('re-password').classList.remove('invalid');		

		//change back all the placeholder text
		document.getElementById('userName').setAttribute('placeholder', 'Username');
		document.getElementById('password').setAttribute('placeholder', 'Password');
		document.getElementById('re-password').setAttribute('placeholder', 'Re-enter password');
	};

	//add an event listener in order to cancel user login/register
	document.getElementById('cancel-login').addEventListener('click', function(){
		resetForm(); //reset the form once exits
		document.getElementById('re-password').style.visibility = 'hidden'; //change back to login page
		let pageElem = document.getElementsByClassName('log-in')[0];
		pageElem.style.transform = 'scale(0, 0)'; //set scale to 0 for a transition animation
	});


	//when user tries to register
	document.getElementById('register-button').addEventListener('click', function(e){
		e.preventDefault(); //prevent submitting the form without validation

		if(document.getElementById('re-password').style.visibility == 'visible'){ //check if is in the register page
			let userNameElem = document.getElementById('userName');

			//check if all the required filed input is empty
			let userName = userNameElem.value;
			if(userName === ''){
				userNameElem.classList.add('empty');
				userNameElem.setAttribute('placeholder', "username can't be empty!");
				return;
			}else{
				userNameElem.classList.remove('empty');	
			}

			let passwordElem = document.getElementById('password');
			let password = passwordElem.value;
			if(password === ''){
				passwordElem.classList.add('empty');
				passwordElem.setAttribute('placeholder', "password can't be empty!");
				return;
			}else{
				userNameElem.classList.remove('empty');	
			}		

			let rePasswordElem = document.getElementById('re-password');
			let rePassword = rePasswordElem.value;
			if(rePassword === ''){
				rePasswordElem.classList.add('empty');
				rePasswordElem.setAttribute('placeholder', "password can't be empty!");
				return;
			}else{
				userNameElem.classList.remove('empty');
			}


			//if each input field is filled, check if they are correct on server side by AJAX
			$.ajax({
				url: 'user_entry_handler.php',
				type: 'post',
				data: {'actionType': 'register', 'userName': userName, 'password': password, 'rePassword': rePassword},
				success: function(response){
					let errorObj = JSON.parse(response); //the JSON obejct that contains error messages received

					if(errorObj.usernameError != null){
						userNameElem.value = '';
						userNameElem.classList.add('invalid');
						userNameElem.setAttribute('placeholder', errorObj.usernameError);
						return;
					}else{
						userNameElem.classList.remove('invalid');
					}

					if(errorObj.rePasswordError != null){
						rePasswordElem.value = '';
						rePasswordElem.classList.add('invalid');
						rePasswordElem.setAttribute('placeholder', errorObj.rePasswordError);
						return;							
					}else{
						rePasswordElem.classList.remove('invalid');
					}

					document.getElementById('login-form').submit();	
				}
			});
		}else{
			//if isn't in the register page, switching to register page by showing the third input(re-enter password)
			resetForm();
			document.getElementById('re-password').style.visibility = 'visible';
		}

	});

	// when user tries to log in
	document.getElementById('login-button').addEventListener('click', function(e){
		e.preventDefault(); //prevent submitting the form without validation

		if(document.getElementById('re-password').style.visibility == 'hidden'){ //check if is in the login page

			//check if all the required filed input is empty
			let userNameElem = document.getElementById('userName');
			let userName = userNameElem.value;
			if(userName === ''){
				userNameElem.classList.add('empty');
				userNameElem.setAttribute('placeholder', "username can't be empty!");
				return;
			}else{
				userNameElem.classList.remove('empty');	
			}

			let passwordElem = document.getElementById('password');
			let password = passwordElem.value;
			if(password === ''){
				passwordElem.classList.add('empty');
				passwordElem.setAttribute('placeholder', "password can't be empty!");
				return;
			}else{
				userNameElem.classList.remove('empty');	
			}


			//if each input field is filled, check if they are correct on server side by AJAX
			$.ajax({
				url: 'user_entry_handler.php',
				type: 'post',
				data: {'actionType': 'login', 'userName': userName, 'password': password},
				success: function(response){
					let errorObj = JSON.parse(response); //the JSON obejct that contains error messages received

					if(errorObj.usernameError != null){
						userNameElem.value = '';
						passwordElem.value = '';
						userNameElem.classList.add('invalid');
						userNameElem.setAttribute('placeholder', errorObj.usernameError);
						return;
					}else{
						userNameElem.classList.remove('invalid');
					}

					if(errorObj.passwordError != null){
						passwordElem.value = '';
						passwordElem.classList.add('invalid');
						passwordElem.setAttribute('placeholder', errorObj.passwordError);
						return;							
					}else{
						passwordElem.classList.remove('invalid');
					}		
					
					document.getElementById('login-form').submit();		
				}
			});
		}else{
			//if isn't in the login page, entering login page by hiding the third input(re-enter password)
			resetForm();
			document.getElementById('re-password').style.visibility = 'hidden';
		}

	});
</script>
</html>
