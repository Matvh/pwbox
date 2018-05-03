var PASS_REG = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,12}$/;


var CORRECT = 'rgb(104, 134, 121)';
var INCORRECT = 'rgb(224, 155, 93)'

var user = false;
var pass = false;

function checkError(e, t) {

	switch (t){
		case 'text':
			if(e.value == 'a'){
                e.style.setProperty('background-color', INCORRECT, 'important');
                user = false;
                blockSubmit();
            }
			else{
                e.style.setProperty('background-color', CORRECT, 'important');
                user = true;
                if(pass) allowSubmit();
			}
			break;

		case 'pass':

			if(!PASS_REG.test(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                pass = false;
                blockSubmit();
            }
            else{
                e.style.setProperty('background-color', CORRECT, 'important');
                pass = true;
                if(user) allowSubmit();
            }
            break;
	}

}

function allowSubmit(){}

function blockSubmit(){}

function preventDefault(){
    return pass && user;
}
