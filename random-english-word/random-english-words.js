


function RandomGenerator(length){
	//the recevied length value, in order to generate the text that has a defined length
	this.length = length;

	//AJAX method, for fetching the word.txt file that contains all english words, the file is on server(under same domain)
	this.fetch_words = function(){
		var wordsURL = './random-english-word/words.txt'; //file path
		var request = new XMLHttpRequest();
		//var wordsArray;
		request.open('GET', wordsURL, false);
		request.send();
		var wordsArray = request.responseText.split('\n');  //change words text to an words array
		return wordsArray;	
	}

	this.wordsArray = this.fetch_words(); //call the method

	this.randNum = function(){
		// a method to generate a random integer between 0 and the words array length - 1
		var wordsNum = this.wordsArray.length;
		var randIndex = Math.floor(Math.random() * wordsNum);
		return randIndex;
	};

	this.rand1letter = function(){
		//generate a word with 1 letter
		var words = ['a', 'i', 'o'];
		var randIndex = Math.floor(Math.random() * words.length);
		return words[randIndex];
	}

	this.rand2letters = function(){
		//generate a word with 2 letters
		var words = ['an', 'as', 'it', 'at', 'to', 'in', 'is', 'on', 'am', 'be', 'no', 'do', 'me', 'of', 'go', 'we', 'if', 'so', 'he', 'by', 'my', 'or', 'up', 'us', 'ax', 'ox'];
		var randIndex = Math.floor(Math.random() * words.length);
		return words[randIndex];
	}

	this.randText = function(){
		// the method to generate a text with given length
		var text = '';

		while(text.length < this.length){
	
			//keep generating random word till reaches the given length
			var newWord = this.wordsArray[this.randNum()];
			if((newWord.length + text.length) > this.length){
				var neededLength = this.length - text.length;

				if(neededLength == 1){
					//when there is a word with 1 letter needed
					text += this.rand1letter();
				}else if(neededLength == 2){
					//when there is a word with 2 letters needed
					text += this.rand2letters();
				}else{
					//when there is a word with more than 2 letters needed
					var index = this.randNum();
					var stop = false;
					while(!stop){
						// start to loop through the array to find a suitable word
						console.log(neededLength);
						if(this.wordsArray[index].length == neededLength){
							text += this.wordsArray[index];
							stop = true;
						}else if(this.wordsArray[index].length < neededLength){
							text += this.wordsArray[index];
							text += ' ';
							stop = true;
						}

						index += 1;
					}
				}
			}else{
				text += newWord;
				text += ' ';
			}
		}

		return text;
	};

}