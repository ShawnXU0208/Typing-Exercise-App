<!DOCTYPE html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>

	<div class = 'welcome-page'>
		<img src = 'imgs/hero-img.jpeg' />

		<h1>Type Type</h1>
		<h2>A Typing Exercise</h2>

		<div class = 'start-button'>
			<img id = 'down-arrow' src = 'imgs/down-arrow.png' />
			<h3 id = 'start-app'>START</h3>
		</div>
	</div>

	<div class = 'app-page'>
		

		<div id = 'blur-img-container'>
			<div class = 'blur-img-wrapper'>
				<div id = 'blur-img'></div>
			</div>
		</div>

		<div class = 'disabled-message'>
			<h1>Typing App is stopped</h1>
			<h2>To Continue, <span id = 'enable-button'>click me</span></h2>
		</div>

		<div class = 'app-window' id = 'app'>

			<div class = 'status-pane'>

				<span class = 'status-info'>Press 'Enter' for next round</span>

				<div class = 'user-control' id = 'drop-down-trigger'>
					<div class = 'user-icons'>
						<img src = 'imgs/one-user.png' />
						<i class="fa fa-angle-down"></i>
					</div>

					<div class = 'drop-down-menu' id = 'unlogged-menu'>
						<button id = 'register-menu'>register</button>
						<button id = 'login-menu'>log in</button>
					</div>

					<div class = 'drop-down-menu' id = 'logged-menu'>
						<p class = 'user-name'></p>
						<button id = 'logout'>log out</button>
					</div>
				</div>


			</div>

			<div class = 'result-pane'>
				<div class = 'result-item'>
					<div class = 'result-value' id = 'speed'>0</div>
					<img src = 'imgs/icons8-speedometer.png' />
					<div class = 'result-name'>speed</div>
				</div>
				<div class = 'result-item'>
					<div class = 'result-value' id = 'time'>0.0</div>
					<img src = 'imgs/icons8-clock.png' />
					<div class = 'result-name'>time</div>
				</div>
				<div class = 'result-item'>
					<div class = 'result-value' id = 'mistakes'>0</div>
					<img src = 'imgs/icons8-sad.png' />
					<div class = 'result-name'>mistakes</div>
				</div>
			</div>

			<div id = 'typing-frame'>
				<div class = 'typing-pane' id = 'current-typing-pane'>

					<div class = 'characters-pane'>
						<p id = 'text-line'></p> 
					</div>

					
					<div class = 'keyboard-pane'>
						<div class = 'keys-row'>
							<span class = 'key-cell'>Q</span>
							<span class = 'key-cell'>W</span>
							<span class = 'key-cell'>E</span>
							<span class = 'key-cell'>R</span>
							<span class = 'key-cell'>T</span>
							<span class = 'key-cell'>Y</span>
							<span class = 'key-cell'>U</span>
							<span class = 'key-cell'>I</span>
							<span class = 'key-cell'>O</span>
							<span class = 'key-cell'>P</span>
							<span class = 'key-cell'>[</span>
							<span class = 'key-cell'>]</span>
						</div>
						<div class = 'keys-row'>
							<span class = 'key-cell'>A</span>
							<span class = 'key-cell'>S</span>
							<span class = 'key-cell'>D</span>
							<span class = 'key-cell'>F</span>
							<span class = 'key-cell'>G</span>
							<span class = 'key-cell'>H</span>
							<span class = 'key-cell'>J</span>
							<span class = 'key-cell'>K</span>
							<span class = 'key-cell'>L</span>
							<span class = 'key-cell'>;</span>
							<span class = 'key-cell'>'</span>
						</div>
						<div class = 'keys-row'>
							<span class = 'key-cell'>Z</span>
							<span class = 'key-cell'>X</span>
							<span class = 'key-cell'>C</span>
							<span class = 'key-cell'>V</span>
							<span class = 'key-cell'>B</span>
							<span class = 'key-cell'>N</span>
							<span class = 'key-cell'>M</span>
							<span class = 'key-cell'>,</span>
							<span class = 'key-cell'>.</span>
							<span class = 'key-cell'>/</span>
						</div>
					</div>
				</div>
		
			</div>
		</div>

		<div class = 'controll-pane'>
			<button id = 'switch' onclick = 'showModes()'>SWITCH MODE</button>
			<div id = 'mode-buttons'>
				<button class = 'mode-button' id = 'mode-1'>mode 1</button>
				<button class = 'mode-button' id = 'mode-2'>mode 2</button>
				<button class = 'mode-button' id = 'mode-3'>mode 3</button>
				<button class = 'mode-button' id = 'mode-4'>mode 4</button>
			</div>
		
			<button id = 'view-result'>RESULT</button>
		</div>


	</div>

	<div class = 'report-page'></div>
</body>

<?php
	include('user_entry.php');
?>

<script src="./random-english-word/random-english-words.js"></script>
<script>

	//globla variables
	let time = 0; // the time has passed since user start to type
	let speed = 0; // the speed of typing - letter per min
	let mistakes = 0; // the number of mistakes that the user has made
	let startTimer = false;
	let totalEntries = 0; // total number of letters that have typed, for calculating speed
	let currentIndex = 0; //indicate which character is the next letter that need to type
	let myTimer; //define the variable of timer

	let mistakeList = {}; //a list to store total mistake number for each character during typing each round
	let respondList = {}; // a list to store average respond time for each character during typing each round
	let occurList = {}; // a list to store total occur times for each character during typing each round
	let seeCharTime;

	const totalChars = 95; //the total number of characters on character pane is 95
	const interval = 100; // 0.1 second
	const nonLetterCodes = [91, 93, 59, 39, 44, 46, 47];

	let keypressEnable = false;
	let isLogged = false;


	//page height variables
	let windowHeight = window.innerHeight;
	const minimumAppHeight = 690;
	const minimumWelcomeHeight = 750;
	let welcomeHeight = minimumWelcomeHeight;
	let appHeight = minimumAppHeight;

	const resetHeights = function(){
		windowHeight = window.innerHeight;

		if(windowHeight > minimumWelcomeHeight){
			welcomeHeight = windowHeight;
			//document.getElementsByClassName('app-window')[0].style.top = welcomeHeight.toString() + 'px';
		}else{
			welcomeHeight = minimumWelcomeHeight;
		}

		if(windowHeight > minimumAppHeight){
			appHeight = windowHeight;
		}else{
			appHeight = minimumAppHeight;
		}

		document.getElementsByClassName('welcome-page')[0].style.height = welcomeHeight.toString() + 'px';
		//document.getElementsByClassName('blur-img-wrapper')[0].style.height = appHeight.toString() + 'px';
		//document.getElementsByClassName('app-window')[0].style.height = appHeight.toString() + 'px';
		document.getElementsByClassName('app-page')[0].style.height = appHeight.toString() + 'px';
		document.getElementsByClassName('log-in')[0].style.height = appHeight.toString() + 'px';
		document.getElementsByClassName('report-page')[0].style.top = appHeight.toString() + 'px';



	};



	const assignID = function(){
		//Assing the keyCode to each key on keyboard
		const keyElems = document.getElementsByClassName('key-cell');
		for(let i = 0; i < keyElems.length; i ++){
			let key = keyElems[i].innerHTML.toLowerCase();
			let id = key.charCodeAt(0);
			document.getElementsByClassName('key-cell')[i].setAttribute('id',id.toString());
		}
	};

	const timerHandler = function(interval){
		//count time up and update speed every interval time
		if(currentIndex < totalChars){
			time += interval/1000;
			document.getElementById('time').innerHTML = time.toFixed(1);

			speed = totalEntries / time * 60;
			document.getElementById('speed').innerHTML = speed.toFixed(0);
		}
	};


	const renderText = function(){
		document.getElementById('text-line').innerHTML = ''; // clear the text from last time
		let generator = new RandomGenerator(totalChars);
		let textToType = generator.randText();

		for(let i = 0; i < textToType.length; i++){
			let currentChar = textToType[i];
			let newChar = document.createElement('span');
			if(i == 0){
				newChar.classList.add('current-char');
			}
			let newText = document.createTextNode(currentChar);
			newChar.appendChild(newText);
			document.getElementById('text-line').appendChild(newChar);
		}
	};


	const createRipple = function(correct, code){
		let circleElem = document.createElement('div');
		circleElem.classList.add('ripple');
		let addedNode;

		if(correct){			
			addedNode = document.getElementById(code.toString()).appendChild(circleElem);
		}else{
			circleElem.classList.add('incorrect-ripple');
			addedNode = document.getElementById(code.toString()).appendChild(circleElem);
		}
		return addedNode;

	};

	const clearRipple = function(rippleElem, parentId){
		let parent = document.getElementById(parentId.toString());
		parent.removeChild(rippleElem);
	};

	const disableKeypress = function(){
		if(keypressEnable){
			if(document.getElementsByClassName('log-in')[0].style.transform == 'scale(0, 0)' || document.getElementsByClassName('log-in')[0].style.display == 'none'){
				return false;
			}
		}
		return true;
	}

	const passData = function(){
		//pass all the data needed to typig_data_processor.php
		$.ajax({
			url: 'typing_data_processor.php',
			type: 'post',
			data: {'userName': userName, 'speed': speed, 'mistakes': mistakes, 'mistakeList': JSON.stringify(mistakeList), 'respondList': JSON.stringify(respondList), 'occurList': JSON.stringify(occurList)}
		});
	}



	const keyStrockHandler = function(e){
		// the handle function, will be invloked each time when key is typed
		if(!disableKeypress()){
			const keyCode = e.which;
			const keyChar = String.fromCharCode(keyCode);

			if(!startTimer){
				startTimer = true;
				myTimer = setInterval(timerHandler, interval, interval); //start timer function
				seeCharTime = new Date().getTime();
			}

			if(keyCode == 32){
				e.preventDefault();
			}

			if(keyCode == 13){
				currentIndex = 0;	
				clearInterval(myTimer); //stop timer couting up

				passData();

				//empty mistake and respond list
				mistakeList = {};
				respondList = {};
				occurList = {};
				//set three result to zero
				time = 0;
				document.getElementById('time').innerHTML = time.toFixed(1);
				mistakes = 0;
				document.getElementById('mistakes').innerHTML = mistakes;
				speed = 0;
				document.getElementById('speed').innerHTML = speed;
				totalEntries = 0;

				renderText(); // display another string of text to type
				startTimer = false;

			}else{
				let charElem = document.getElementById('text-line').getElementsByTagName('span')[currentIndex];
				charElem.classList.remove('current-char');
				//initialise respond time
				if(respondList[charElem.innerHTML] == null){
					respondList[charElem.innerHTML] = 0;
				}
				if(startTimer){
					respondTime = new Date().getTime() - seeCharTime; //get the respond time
					if(respondList[charElem.innerHTML] == 0){
						respondList[charElem.innerHTML] = respondTime;
					}else{
						respondList[charElem.innerHTML] = (respondList[charElem.innerHTML] + respondTime) / 2;
					}
				
					seeCharTime = new Date().getTime();
				}

				//initialise mistake number
				if(mistakeList[charElem.innerHTML] == null){
					mistakeList[charElem.innerHTML] = 0;
				}

				//initialise occur times
				if(occurList[charElem.innerHTML] == null){
					occurList[charElem.innerHTML] = 0;
				}
				occurList[charElem.innerHTML] += 1;

				if(keyChar == charElem.innerHTML){
					//when hitting the correct key
					document.getElementById('text-line').getElementsByTagName('span')[currentIndex].className = 'correct';
					if(keyChar != ' '){
						let rippleElem = createRipple(true, keyCode); //create a ripple click effect
						setTimeout(clearRipple, 1000, rippleElem, keyCode); //clear the ripple element in one second
					}
				}else{
					mistakeList[charElem.innerHTML] += 1; 

					//when hitting the incorrect key
					mistakes += 1; //update mistakes number
					document.getElementById('mistakes').innerHTML = mistakes;

					document.getElementById('text-line').getElementsByTagName('span')[currentIndex].className = 'incorrect';
					if(charElem.innerHTML == ' '){
						document.getElementById('text-line').getElementsByTagName('span')[currentIndex].innerHTML = '!';
					}

					if((keyCode >= 97 && keyCode <= 122) || nonLetterCodes.includes(keyCode)){
						//when incorrect key is typed, check if the key is the one that is displayed on the keyboard of app
						let rippleElem = createRipple(false, keyCode);
						setTimeout(clearRipple, 1000, rippleElem, keyCode); //clear the ripple element in one second
					}
				}
				currentIndex += 1;
				totalEntries += 1;
				document.getElementById('text-line').getElementsByTagName('span')[currentIndex].classList.add('current-char');
			}
			//document.getElementById('type-window').innerHTML = charElem.innerHTML;
		}
	};





	function showModes(){
		document.getElementById('switch').style.left = '380px';
		document.getElementById('switch').setAttribute('onclick', 'javascript: hideModes()');
		document.getElementById('switch').innerHTML = "BACK";

		document.getElementById('mode-buttons').style.left = '-20px';
	}

	function hideModes(){
		document.getElementById('switch').style.left = '0px';
		document.getElementById('switch').setAttribute('onclick', 'javascript: showModes()');
		document.getElementById('switch').innerHTML = "SWITCH MODE";

		document.getElementById('mode-buttons').style.left = '-400px';	
	}

	const scrollTo = function(end, duaration){
		

		const element = document.scrollingElement || document.documentElement; // for cross browsers problems
		const start = element.scrollTop;
		const change = end - start;
		const startDate = (new Date).getTime();

		const easeInOutQuad = function(t, b, c, d){
			// t = current time
		    // b = start value
		    // c = change in value
		    // d = duration
		    t /= d/2;
		    if(t < 1){
		    	return (c/2*t*t + b);
		    }
		    t--;
		    return (-c/2 * (t*(t-2) - 1) + b);

		};

		const animateScroll = function(){
			const currentDate = (new Date()).getTime();
			const currentTime = currentDate - startDate;
			element.scrollTop = parseInt(easeInOutQuad(currentTime, start, change, duaration));
			if(currentTime < duaration){
				requestAnimationFrame(animateScroll);
			}
			else{
				element.scrollTop = end;
			}
		};



		animateScroll();

	};

	const showLogInPage = function(){
		let pageElem = document.getElementsByClassName('log-in')[0];
		//document.getElementsByClassName('log-in')[0].style.display = 'block';
		pageElem.style.transform = 'scale(1.0, 1.0)';
		document.getElementsByClassName('log-in')[0].dataset.display = 'yes';
	};

/*******************************************************************************************************************************************/
/****************************************************Event handler function*****************************************************************/
/*******************************************************************************************************************************************/

	document.getElementById('start-app').addEventListener('click', function(){

		document.getElementsByClassName('log-in')[0].style.display = 'block'; //make login page visible
		//move the whole web page, to hide welcome page
		document.getElementsByTagName('body')[0].style.top = '-' + welcomeHeight.toString() + 'px';
		setTimeout(function(){
			// render the app page after 1 second for better user experience.
			document.getElementsByClassName('app-page')[0].style.display = 'block';
		}, 1000);

		keypressEnable = true;
	});


	document.getElementById('view-result').addEventListener('click', function(){
		if(isLogged){
			//show user's typing data if some one has logged in
			$('.report-page').load('view_result_page.php', {userName: userName}); //load 'view_result_page'

			document.getElementsByClassName('report-page')[0].style.display = 'block';
			scrollTo(appHeight, 1000);//scroll to result page

			setTimeout(function(){
				//hide app window and show a message instead once the scroll animation has done
				document.getElementsByClassName('app-window')[0].style.display = 'none';
				document.getElementsByClassName('controll-pane')[0].style.display = 'none';
				document.getElementsByClassName('disabled-message')[0].style.display = 'block';
			}, 1100);

			keypressEnable = false;
		}else{
			//show login page if no one has logged in
			showLogInPage();
		}
	});

	document.getElementById('enable-button').addEventListener('click', function(){
		//go back to app window, and hide result page

		document.getElementsByClassName('app-window')[0].style.display = 'block';
		document.getElementsByClassName('controll-pane')[0].style.display = 'block';
		scrollTo(0, 1000); //scroll back
		setTimeout(function(){
			//hide the report page once the scroll animation has done
			document.getElementsByClassName('report-page')[0].style.display = 'none'; 
		}, 1100);
		document.getElementsByClassName('disabled-message')[0].style.display = 'none';

		keypressEnable = true;
	});

	/**************************************User Status dropdown menu on status pane *************/
	document.getElementById('drop-down-trigger').addEventListener('mouseover', function(){
		if(isLogged){
			//show logged dropdown menu
			document.getElementById('logged-menu').style.display = 'block';
		}else{
			//show unlogged dropdown menu
			document.getElementById('unlogged-menu').style.display = 'block';
		}
	});

	document.getElementById('drop-down-trigger').addEventListener('mouseout', function(){
		if(isLogged){
			document.getElementById('logged-menu').style.display = 'none';
		}else{
			document.getElementById('unlogged-menu').style.display = 'none';
		}
	});

	document.getElementById('register-menu').addEventListener('click', function(){
		showLogInPage();
		document.getElementById('re-password').style.visibility = 'visible';
	});

	document.getElementById('login-menu').addEventListener('click', function(){
		showLogInPage();
		document.getElementById('re-password').style.visibility = 'hidden';
	});	

	document.getElementById('logout').addEventListener('click', function(){
		//logout
		location.reload(true);
	});

	/******************************************************************************************/

	//bind keystrock handler to keypress event
	document.addEventListener("keypress", keyStrockHandler); 


	window.addEventListener("resize", function(){
		let width = window.innerWidth;
		if(width < 1280){
			const element = document.scrollingElement || document.documentElement; // for cross browsers problems
			let scrollTo = Math.ceil((1280 - width) / 2);
			element.scrollLeft = scrollTo;

		}

		resetHeights();
	});


/*******************************************************************************************************************************************/
/*******************************************************************************************************************************************/
/*******************************************************************************************************************************************/

	{
		const elems = document.getElementsByClassName('mode-button');
		for (let i = 0; i < elems.length; i++){
			elems[i].addEventListener('click', function(){
				const element =  document.getElementById('current-typing-pane');
				const cloneElem = element.cloneNode(true); //copy the keyboard element
				cloneElem.setAttribute('id', 'next-typing-pane');
				const newKeyboard = document.getElementById('typing-frame').appendChild(cloneElem);

				element.style.transform = 'scale(0.7)';
				element.style.opacity = '0';
				
				setTimeout(function(){
					document.getElementById('typing-frame').removeChild(element);
					setTimeout(function(){
						cloneElem.setAttribute('id', 'current-typing-pane');
					}, 600);
				}, 600);

			});
		}
	}

	

/*

	window.addEventListener("resize", function(){
		let width = window.innerWidth;
		if(width < 1280){
			const element = document.scrollingElement || document.documentElement; // for cross browsers problems
			let scrollTo = Math.ceil((1280 - width) / 2);
			element.scrollLeft = scrollTo;

		}
	});


	let windowHeight = window.innerHeight;
	const minimumAppHeight = 690;
	const minimumWelcomeHeight = 750;
	let welcomeHeight = minimumWelcomeHeight;

	document.getElementsByClassName('welcome-page')[0].style.height = window.innerHeight.toString() + 'px';
	if(windowHeight >= 750){
		document.getElementsByClassName('app-window')[0].style.top = window.innerHeight.toString() + 'px';
	}

	//document.getElementById('blur-img-container').style.height = window.innerHeight.toString() + 'px';
	if(window.innerHeight > minimumAppHeight){
		document.getElementsByClassName('blur-img-wrapper')[0].style.height = window.innerHeight.toString() + 'px';
		document.getElementsByClassName('app-window')[0].style.height = window.innerHeight.toString() + 'px';
		//document.getElementsByClassName('app-window')[0].style.top = '-' + window.innerHeight.toString() + 'px';
	}else{
		document.getElementsByClassName('app-window')[0].style.height = minimumAppHeight.toString() + 'px';
	}

*/





	//var text = document.getElementById('text-line').innerHTML;


	//page initial
	resetHeights();
	renderText();
	assignID();
	document.getElementsByClassName('app-page')[0].style.display = 'none';
	document.getElementsByClassName('log-in')[0].style.display = 'none';
	document.getElementsByClassName('report-page')[0].style.display = 'none';

	
	let userName = <?php echo json_encode($_POST["userName"]); ?>;
	if(userName != null){
		isLogged = true;

		document.getElementsByClassName('log-in')[0].style.display = 'none';
		document.getElementsByTagName('body')[0].style.top = '-' + welcomeHeight.toString() + 'px';

		document.getElementsByClassName('app-page')[0].style.display = 'block';

		document.getElementsByClassName('user-name')[0].innerHTML = userName;
		keypressEnable = true;
	}

	const tutorial1 = function(){

		document.getElementsByClassName('characters-pane')[0].style.zIndex = '2';
	}

	const showTutorial = function(){
		tutorial1();
	};

	//showTutorial();


</script>


</html>