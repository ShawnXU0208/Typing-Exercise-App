	
<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style/view_result_page.css">
</head>



<?php
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	class KeyChar{
		public $letter;
		public $mistakeNum10 = 0;
		public $mistakeNum20 = 0;
		public $respondTime10 = 0;
		public $respondTime20 = 0;
		public $occur10 = 0;
		public $occur20 = 0;
	}


	//include config for database, get connecton to the database $conn
	require('database_config.php');
	$userName = $_POST["userName"];
	//$userName = 'demo1';

	$query = "SELECT UserID FROM UserEntry WHERE UserName = '$userName'";
	$result = $conn -> query($query);


	if($result -> num_rows == 1){
		$speedArray = [];
		$mistakeRateArray = [];
		$averageResponseArray = [];
		$averageRateArray = [];

		$row = $result -> fetch_assoc();
		$userId = $row['UserID'];

		$query = "SELECT Speed, Mistake, Respond FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime";
		$result = $conn -> query($query);
		while($row = $result -> fetch_assoc()){
			 $speedArray[] = $row['Speed'];
			 $mistakeRateArray[] = $row['Mistake']/95;
			 $averageResponseArray[] = $row['Respond'];
		}
		

		$keyCharArray = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
		$keyObjArray = [];
		$timeArray = [];

		$query = "SELECT DISTINCT CreatedTime FROM DetailedData ORDER BY CreatedTime ASC";
		$result = $conn -> query($query);
		while($row = $result -> fetch_assoc()){
			$timeArray[] = $row['CreatedTime'];
		}

		foreach($keyCharArray as $letter){
			$keyObj = new KeyChar;
			$keyObj -> letter = $letter;

			if(count($timeArray) >= 10){
				$middleTime = $timeArray[9];
				$query = "SELECT Mistake, Respond, Occur FROM DetailedData WHERE UserID = '$userId' AND KeyChar = '$letter' AND CreatedTime <= '$middleTime' ORDER BY CreatedTime";
				$result = $conn -> query($query);

				while($row = $result -> fetch_assoc()){
					$keyObj -> mistakeNum10 += $row['Mistake'];
					$keyObj -> respondTime10 += $row['Respond'];
					$keyObj -> occur10 += $row['Occur'];
				}
				if($result -> num_rows >0){
					$keyObj -> respondTime10 = round(($keyObj -> respondTime10) /($result -> num_rows));
				}else{
					$keyObj -> mistakeNum10 = '-';
					$keyObj -> respondTime10 = '-';
					$keyObj -> occur10 = '-';				
				}			
			}else{
				$keyObj -> mistakeNum10 = '-';
				$keyObj -> respondTime10 = '-';
				$keyObj -> occur10 = '-';				
			}

			if(count($timeArray) >= 20){
				$middleTime = $timeArray[19];
				$query = "SELECT Mistake, Respond, Occur FROM DetailedData WHERE UserID = '$userId' AND KeyChar = '$letter' AND CreatedTime <= '$middleTime' ORDER BY CreatedTime";
				$result = $conn -> query($query);

				while($row = $result -> fetch_assoc()){
					$keyObj -> mistakeNum20 += $row['Mistake'];
					$keyObj -> respondTime20 += $row['Respond'];
					$keyObj -> occur20 += $row['Occur'];
				}
				if($result -> num_rows >0){
					$keyObj -> respondTime20 = round(($keyObj -> respondTime20)/($result -> num_rows));
				}else{
					$keyObj -> mistakeNum20 = '-';
					$keyObj -> respondTime20 = '-';
					$keyObj -> occur20 = '-';					
				}			
			}else{
				$keyObj -> mistakeNum20 = '-';
				$keyObj -> respondTime20 = '-';
				$keyObj -> occur20 = '-';				
			}

			$keyObjArray[] = $keyObj;
		}	

	}

?>


<body>
	<div class = 'report-result'>
		<h1>Typing Result</h1>
		<p>username: <?php echo $userName; ?></p>
		<hr>

		<hr>

		<div class = 'general-result'>
			<div class = 'result-sub'>
				<div class = 'result-title'>Typing Speed</div>
				<div class = 'result-value'>
					<?php 
					if(count($speedArray) > 0){
						echo round(array_sum($speedArray)/count($speedArray)); 
					}else{
						echo 'no record';
					}
					?>	
					<span>LPM</span>				
				</div>
				<div class = 'view-recent' id = 'speed-chart'>
					<span>view detail</span>
					<span><i class="fa fa-arrow-circle-right"></i></span>
				</div>
			</div>

			<div class = 'result-sub'>
				<div class = 'result-title'>Mistake Rate</div>
				<div class = 'result-value'>
					<?php 
					if(count($mistakeRateArray) > 0){
						echo round(array_sum($mistakeRateArray)/count($mistakeRateArray)*100, 2); 
					}else{
						echo 'no record';
					}
					?>	
					<span>%</span>
				</div>
				<div class = 'view-recent' id = 'mistake-chart'>
					<span>view detail</span>
					<span><i class="fa fa-arrow-circle-right"></i></span>
				</div>
			</div>

			<div class = 'result-sub'>
				<div class = 'result-title'>Average Response</div>
				<div class = 'result-value'>
					<?php 
					if(count($averageResponseArray) > 0){
						echo round(array_sum($averageResponseArray)/count($averageResponseArray)/95);
					}else{
						echo 'no record';
					}
					?>	
					<span>ms</span>
				</div> 
				<div class = 'view-recent' id = 'response-chart'>
					<span>view detail</span>
					<span><i class="fa fa-arrow-circle-right"></i></span>
				</div>
			</div>

			<div class = 'result-sub'>
				<div class = 'result-title'>Your Rate</div>
				<div class = 'result-value'>
					<?php 
					if(count($speedArray) > 0){
						$wpm = round(array_sum($speedArray)/count($speedArray)/5); 
						if($wpm < 40){
							echo "slow";
						}elseif($wpm > 100){
							echo "fast";
						}else{
							echo "average";
						}
					}else{
						echo 'no record';
					}
					?>	
				</div>
			</div>

		</div>

		<div class = 'result-detail'>
	
			<table id = 'detail-table'>
				<tr class = 'filter-pane'>
					<th style = "width: 10%;">letter</th>
					<th style = "width: 15%;">mistake number<br>last 10 rounds</th>
					<th style = "width: 15%;">respond time(ms)<br>last 10 rounds</th>
					<th style = "width: 15%;">occur times<br>last 10 rounds</th>
					<th style = "width: 15%;">mistake number<br>last 20 times</th>
					<th style = "width: 15%;">respond time(ms)<br>last 20 times</th>
					<th style = "width: 15%;">occur times<br>last 20 times</th>
				</tr>

			</table>
		</div>

	</div>

	<div class = 'blur-background'></div>
	<div class = 'chart-page'>
		<canvas id = 'chart'></canvas>
		<button id = 'chart-close'>CLOSE</button>
	</div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>
{
	let renderTable = function(){
		let keyObjArray = <?php echo json_encode($keyObjArray); ?>;
		//keyObjArray = JSON.parse(keyObjArray[0]);
	
		let tableElem = document.getElementById('detail-table');
		for(let i = 0; i < keyObjArray.length; i++){
			let currentObj = keyObjArray[i];
			let rowElem = document.createElement("tr");

			let td1 = document.createElement("td");
			let node1 = document.createTextNode(currentObj.letter);
			td1.appendChild(node1);
			rowElem.appendChild(td1);

			let td2 = document.createElement("td");
			let node2 = document.createTextNode(currentObj.mistakeNum10);
			td2.appendChild(node2);
			rowElem.appendChild(td2);

			let td3 = document.createElement("td");
			let node3 = document.createTextNode(currentObj.respondTime10);
			td3.appendChild(node3);
			rowElem.appendChild(td3);

			let td4 = document.createElement("td");
			let node4 = document.createTextNode(currentObj.occur10);
			td4.appendChild(node4);
			rowElem.appendChild(td4);

			let td5 = document.createElement("td");
			let node5 = document.createTextNode(currentObj.mistakeNum20);
			td5.appendChild(node5);
			rowElem.appendChild(td5);

			let td6 = document.createElement("td");
			let node6 = document.createTextNode(currentObj.respondTime20);
			td6.appendChild(node6);
			rowElem.appendChild(td6);

			let td7 = document.createElement("td");
			let node7 = document.createTextNode(currentObj.occur20);
			td7.appendChild(node7);
			rowElem.appendChild(td7);

			tableElem.appendChild(rowElem);

		}
	}

	const sortTable = function(n){
		let table = document.getElementById('detail-table');
		let finishSwitching = false;
		let direction = 'asc';
		let switchCount = 0;

		while(!finishSwitching){
			let tableRows = table.rows;
			let needSwitch = false;
			let switchingRow = 0;
			for(let i = 1; i < (tableRows.length - 1); i++){
				switchingRow = i;
				let firstValue = tableRows[i].getElementsByTagName('td')[n].innerHTML;
				if (firstValue == '-'){
					firstValue = -1;
				}
				let secondValue = tableRows[i+1].getElementsByTagName('td')[n].innerHTML;
				if (secondValue == '-'){
					secondValue = -1;
				}				
				if(n > 0){
					firstValue = Number(firstValue);
					secondValue = Number(secondValue);
				}

				if(direction == 'asc'){
					if(firstValue > secondValue){
						needSwitch = true;
						break;
					}
				}else{
					if(firstValue < secondValue){
						needSwitch = true;
						break;
					}					
				}
			}

			if(needSwitch){
				tableRows[switchingRow].parentNode.insertBefore(tableRows[switchingRow+1], tableRows[switchingRow]);
				console.log(tableRows[switchingRow].getElementsByTagName('td')[0]);
				switchCount ++;
			}else if(switchCount == 0 && direction == 'asc'){
				direction = 'desc';
			}else{
				finishSwitching = true;
			}
		}
	}; 

	let tableHeaderRow = document.getElementById('detail-table').rows[0];
	for(let i = 0; i < 6; i++){
		tableHeaderRow.getElementsByTagName('th')[i].addEventListener('click', function(){
			sortTable(i);
		});
	}

	renderTable();

	document.getElementById('chart-close').addEventListener('click', function(){
		document.getElementsByClassName('chart-page')[0].style.transform = 'translate(-50%, -50%) scale(0)';
		setTimeout(function(){
			document.getElementsByClassName('chart-page')[0].style.visibility = 'hidden';
			document.getElementsByClassName('blur-background')[0].style.display = 'none';
		}, 1000);

	});

	document.getElementById('speed-chart').addEventListener('click', function(){
		<?php
			$generalSpeedArray = [];
			$query = "SELECT Speed FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime ASC";
			$result = $conn -> query($query);
			while($row = $result -> fetch_assoc()){
				$generalSpeedArray[] = $row['Speed'];
			}
		?>

		document.getElementsByClassName('chart-page')[0].style.transform = 'translate(-50%, -50%) scale(1)';
		document.getElementsByClassName('chart-page')[0].style.visibility = 'visible';
		document.getElementsByClassName('blur-background')[0].style.display = 'block';

		let generalSpeedArray = <?php echo json_encode($generalSpeedArray);?>;

		let ctx = document.getElementById('chart').getContext('2d');
		let chart =  new Chart(ctx, {
			type: 'line',
			data: {
				labels: ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
				//labels: ["January", "February", "March", "April", "May", "June", "July"],
				datasets: [{
					 borderColor: "#3e95cd",
					 data: generalSpeedArray
				}]
			},
			options: {
				title: {
					display: true,
					text: 'Typing speed for the last 20 rounds(letters per minute)',
					fontSize: 20
				},
				legend: {
					display: false
				},
				animation: false,
				responsive: true,
				maintainAspectRatio: false

			}
		});
	});


	document.getElementById('mistake-chart').addEventListener('click', function(){
		<?php
			$generalMistakeArray = [];
			$query = "SELECT Mistake FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime ASC";
			$result = $conn -> query($query);
			while($row = $result -> fetch_assoc()){
				$generalMistakeArray[] = $row['Mistake'];
			}
		?>

		document.getElementsByClassName('chart-page')[0].style.transform = 'translate(-50%, -50%) scale(1)';
		document.getElementsByClassName('chart-page')[0].style.visibility = 'visible';
		document.getElementsByClassName('blur-background')[0].style.display = 'block';

		let generalMistakeArray = <?php echo json_encode($generalMistakeArray);?>;
		generalMistakeArray = generalMistakeArray.map(function(a){
			return (a/95) * 100;
		});

		let ctx = document.getElementById('chart').getContext('2d');
		let chart =  new Chart(ctx, {
			type: 'line',
			data: {
				labels: ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
				//labels: ["January", "February", "March", "April", "May", "June", "July"],
				datasets: [{
					 borderColor: "#3e95cd",
					 data: generalMistakeArray
				}]
			},
			options: {
				title: {
					display: true,
					text: 'Typing mistake for the last 20 rounds(%)',
					fontSize: 20
				},
				legend: {
					display: false
				},
				animation: false,
				responsive: true,
				maintainAspectRatio: false

			}
		});
	});


	document.getElementById('response-chart').addEventListener('click', function(){
		<?php
			$generalRespondArray = [];
			$query = "SELECT Respond FROM GeneralData WHERE UserID = '$userId' ORDER BY CreatedTime ASC";
			$result = $conn -> query($query);
			while($row = $result -> fetch_assoc()){
				$generalRespondArray[] = $row['Respond'];
			}
		?>

		document.getElementsByClassName('chart-page')[0].style.transform = 'translate(-50%, -50%) scale(1)';
		document.getElementsByClassName('chart-page')[0].style.visibility = 'visible';
		document.getElementsByClassName('blur-background')[0].style.display = 'block';

		let generalRespondArray = <?php echo json_encode($generalRespondArray);?>;

		let ctx = document.getElementById('chart').getContext('2d');
		let chart =  new Chart(ctx, {
			type: 'line',
			data: {
				labels: ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
				//labels: ["January", "February", "March", "April", "May", "June", "July"],
				datasets: [{
					 borderColor: "#3e95cd",
					 data: generalRespondArray
				}]
			},
			options: {
				title: {
					display: true,
					text: 'Typing respond for the last 20 rounds(millisecond)',
					fontSize: 20
				},
				legend: {
					display: false
				},
				animation: false,
				responsive: true,
				maintainAspectRatio: false

			}
		});
	});

}
</script>
</html>