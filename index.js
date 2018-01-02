import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import ReactFileReader from 'react-file-reader';



class AppFrame extends React.Component{
	// The most outside component of the typing exercise App
	constructor(){
		super();
		const originalText = this.generateText();

		this.state = {
			passed: [],
			unpassed: originalText,
			currentIndex: 0,
			text: originalText,

			timerAction: '0',
			mistakes: 0,
			typedLetters: 0
		}
	}


	generateText(){
		//generate a text string consists of random characters including space blank

		const possible = 'abcdefghijklmnopqrstuvwxyz ';
		var text = '';

		for (let i = 0; i < 100; i ++){
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}

		const textList = text.split('');
		return textList;
	}



	handleKeyDown = (event) => {
		//process each key that typed by users

		const letterToCheck = this.state.unpassed[0];
		//alert(this.state.unpassed);
		const currentIndex = this.state.currentIndex;
		var newPassed = this.state.passed.slice();
		var newUnpassed = this.state.unpassed.slice();
		const next = this.state.currentIndex + 1;

		var newTimerAction;
		var newMistakesNum = this.state.mistakes;
		var newTypedLetters = this.state.typedLetters;

		if (event.keyCode == '13'){
			// when an "Enter" is typed, reset all the state, restore the App
			const newText = this.generateText();
			this.setState({
				passed: [],
				unpassed: newText,
				currentIndex: 0,
				text: newText,
				timerAction: 'reset',
				mistakes: 0,
				typedLetters: 0
			});
			return;
			
		}

		if (event.key == letterToCheck){
			//when the key typed by users is correct
			newPassed.push({isRight: 'passed-correctly', letter: letterToCheck});
			newUnpassed.shift();
			newTypedLetters += 1;

		}else{
			//when the key typed by users isn't correct
			newPassed.push({isRight: 'passed-incorrectly', letter: letterToCheck});
			newUnpassed.shift();
			newMistakesNum = this.state.mistakes + 1;
			newTypedLetters += 1;
		}

		// change the action of the timer, depending on which key has typed and how many characters has passed
		if (newPassed.length == 1){
			newTimerAction = 'start'; // timer starts counting
		}else if (newUnpassed.length == 0){
			newTimerAction = 'stop'; // timer stops counting
		}else{
			newTimerAction = 'keepGoing';  // timer keep counting
		}

		//change the state. render them agian
		this.setState({
			passed: newPassed, 
			unpassed: newUnpassed,
			currentIndex: next,
			timerAction: newTimerAction,
			mistakes: newMistakesNum,
			typedLetters: newTypedLetters
		});
	}

	render(){
		return(
			<div className = 'app-frame'>
				{/* render the three evaluations for usrs(speed, time, mistakes) */}
				<ResutlsPane timerAction = {this.state.timerAction} mistakes = {this.state.mistakes} typedLetters = {this.state.typedLetters} />

				{/* render the string of text that needed users to follow on window */}
				<TextToType 
					passed = {this.state.passed}
					unpassed = {this.state.unpassed}
					currentIndex = {this.state.currentIndex}
					text = {this.state.text}
					handleKeyDown = {this.handleKeyDown}
				/>

				{/* render a keyboard at the bottom of the App */}
				<Keyboard />
			</div>
		);
	}
}


/*******************************************************************************************************
*********************************Evaulations of typing (on the top of the App)****************************
********************************************************************************************************/
class ResutlsPane extends React.Component{
	// The component that located at the top of the App
	// It consists of three evaluations of users' typing (speed, time and mistakes)

	constructor(props){
		super();
		//current time: the time used so far
		//star flag: ture if the timer is able to start
		//stop flag: true if the timer is able to stop
		//reset flag: true if the timer is able to reset
		this.state = {currentTime: 0, startFlag: true, stopFlag: true, resetFlag: false};
	}

	startTimer = () => {
		//function to start the timer
		this.setState({currentTime: 0, startFlag: false, resetFlag: true})
		this.timer = setInterval(() => this.tick(), 100);
	}

	stopTimer = () => {
		//function to stop the timer
		this.setState({stopFlag: false});
		clearInterval(this.timer);
	}

	resetTimer = () => {
		//function to reset the timer
		this.setState({currentTime: 0, startFlag: true, stopFlag: true, resetFlag: false});
		clearInterval(this.timer);
	}

	tick(){
		//tick function for counting
		this.setState({currentTime: this.state.currentTime + 1});
	}

	render(){
		return(
			<div className = 'result-pane'>
				{/* render user's speed of typing (number of letters per minute)*/}
				<Speed typedLetters = {this.props.typedLetters} timeInterval = {this.state.currentTime} />

			    {/* render the time pass since the user start typing (in seconds) */}
				<Timer 
					currentTime = {this.state.currentTime}
					startFlag = {this.state.startFlag}
					stopFlag = {this.state.stopFlag}
					resetFlag = {this.state.resetFlag}
					timerAction = {this.props.timerAction}
					startTimer = {this.startTimer}
					stopTimer = {this.stopTimer}
					resetTimer = {this.resetTimer}
					tick = {this.tick}
				/>

				{/* render the number of incorrect keys typed by users */}
				<Mistakes mistakesNum = {this.props.mistakes} />
			</div>
		);
	}

}

class Speed extends React.Component{
	// The component to render user's typing speed (letters per minute)
	render(){
		var letterPerMin = this.props.typedLetters / this.props.timeInterval * 600;

		if (!letterPerMin){
			letterPerMin = 0;
		}

		return(
			<div className = 'pane-item'>
				<p className = 'pane-content'>{letterPerMin.toFixed(0)}</p>
				<br />
				<p className = 'pane-icon'><i class="fa fa-keyboard-o" aria-hidden="true"></i></p>
				<p className = 'pane-content'>speed</p>
			</div>
		);
	}
}

class Timer extends React.Component{
	// the component to render the time pass since the user start typing (in seconds)
	render(){
		if(this.props.timerAction == 'start' && this.props.startFlag){
			this.props.startTimer();
		}else if(this.props.timerAction == 'stop' && this.props.stopFlag){
			this.props.stopTimer();
		}else if(this.props.timerAction == 'reset' && this.props.resetFlag){
			this.props.resetTimer();
		}

		var outputTime = this.props.currentTime / 10;
		outputTime = outputTime.toFixed(1);

		return(
			<div className = 'pane-item'>
				<p className = 'pane-content'>{outputTime}</p>
				<br />
				<p className = 'pane-icon'><i class="fa fa-clock-o" aria-hidden="true"></i></p>
				<p className = 'pane-content'>time</p>
			</div>
		);
	}
}


class Mistakes extends React.Component{
	// the component to render the number of incorrect keys typed by users
	render(){
		return(
			<div className = 'pane-item'>
				<p className = 'pane-content'>{this.props.mistakesNum}</p>
				<br />
				<p className = 'pane-icon'><i class="fa fa-frown-o" aria-hidden="true"></i></p>
				<p className = 'pane-content'>mistakes</p>
			</div>
		);
	}
}


/*******************************************************************************************************
*****************A string of random text for users to type (on the middle of the App)*******************
********************************************************************************************************/

class TextToType extends React.Component{

	renderPassed = (element) => {
		const isRight = element.isRight;
		const letter = element.letter;
		return(
			<PassedLetter class = {isRight} value = {letter} />
		);
	}

	render(){	
		const passedToRender = this.props.passed.map(this.renderPassed);
		return(
			<div className = 'text-to-type' tabIndex = '0' onKeyDown = {this.props.handleKeyDown}>
				{passedToRender}
				<UnpassedText textList = {this.props.unpassed} />
			</div>
		);
	}
}

class PassedLetter extends React.Component{
	//The component to render a series of letter that has been typed by users
	render(){
		const value = this.props.value;
		
		const className = this.props.class;

		if (value == ' ' && className == 'passed-incorrectly'){
			return(
				<span className = {className}>!</span>
			);	
		}

		return(
			<span className = {className}>{value}</span>
		);
	
	}
}

class UnpassedText extends React.Component{
	//The component to render a series of letter that has not been typed by users
	render(){
		const currentLetter = this.props.textList[0];
		const newTextList = this.props.textList.slice();
		newTextList.shift();
		const unpassedText = newTextList.join('');

		return(
			<span>
			<span class = 'current-focus'>{currentLetter}</span>
			<span class = 'unpassed-text'>{unpassedText}</span>
			</span>
		);
	}
}


/*******************************************************************************************************
*********************************Keyboard (on the bottom of the App)************************************
********************************************************************************************************/

class Keyboard extends React.Component{
	//The whole keyboard
	render(){
		return(
			<div className = 'keyboard-container'>

				<div className = 'keyboard-row'>
					<KeyboardItem singleKey = 'Q' />
					<KeyboardItem singleKey = 'W' />
					<KeyboardItem singleKey = 'E' />
					<KeyboardItem singleKey = 'R' />
					<KeyboardItem singleKey = 'T' />
					<KeyboardItem singleKey = 'Y' />
					<KeyboardItem singleKey = 'U' />
					<KeyboardItem singleKey = 'I' />
					<KeyboardItem singleKey = 'O' />
					<KeyboardItem singleKey = 'P' />
				</div>

				<div className = 'keyboard-row'>
					<KeyboardItem singleKey = 'A' />
					<KeyboardItem singleKey = 'S' />
					<KeyboardItem singleKey = 'D' />
					<KeyboardItem singleKey = 'F' />
					<KeyboardItem singleKey = 'G' />
					<KeyboardItem singleKey = 'H' />
					<KeyboardItem singleKey = 'J' />
					<KeyboardItem singleKey = 'K' />
					<KeyboardItem singleKey = 'L' />
				</div>

				<div className = 'keyboard-row'>
					<KeyboardItem singleKey = 'Z' />
					<KeyboardItem singleKey = 'X' />
					<KeyboardItem singleKey = 'C' />
					<KeyboardItem singleKey = 'V' />
					<KeyboardItem singleKey = 'B' />
					<KeyboardItem singleKey = 'N' />
					<KeyboardItem singleKey = 'M' />
				</div>

			</div>
		);
	}
}

class KeyboardItem extends React.Component{
	// each key on the keyboard
	render(){
		return(
			<span className = 'key'>
				{this.props.singleKey}
			</span>
		);
	}

}


/*********************************************************************/
ReactDOM.render(<AppFrame />, document.getElementById('root'));