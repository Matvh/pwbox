var PASS_REG = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,12}$/;
var MAIL_REG = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var DATE_REG = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
var DATE_REG_PICKER = /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/;

var CORRECT = 'rgb(225, 242, 230)';
var INCORRECT = 'rgb(244, 231, 219)';

var name;
var password;
var repassword;
var birthday;
var email;
var username;

window.onload = function(){
    document.getElementById('submitbutton').disabled=true;

    name = false;
    password = false;
    repassword = false;
    birthday = false;
    email = false;
    username = false;
}


function checkError(e, t) {

    switch (t){
        case 'text':
            if(e.value == "" || e.value == "undefined" || e.value == null){
                e.style.setProperty('background-color', INCORRECT, 'important');
                this[e.name] = false;
                blockSubmit();
            }
            else{
                e.style.setProperty('background-color', CORRECT, 'important');
                this[e.name] = true;
                if(username && name && password && repassword && birthday && email ) allowSubmit();
            }
            break;

        case 'mail':

            if(!MAIL_REG.test(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                email = false;
                blockSubmit();
            }
            else{
                e.style.setProperty('background-color', CORRECT, 'important');
                email = true;
                if(username && name && password && repassword && birthday && email) allowSubmit();
            }
            break;

        case 'date':

            console.log(e.value);

            if(!isValidDate(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                birthday = false;
                blockSubmit();
            }
            else{
                e.style.setProperty('background-color', CORRECT, 'important');
                birthday = true;
                if(username && name && password && repassword && birthday && email) allowSubmit();
            }
            break;

        case 'pass':

            if(!PASS_REG.test(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                this[e.name] = false;
                blockSubmit();
            }
            else{
                if(e.name == "repassword" && e.value != document.getElementById("password").value){
                    e.style.setProperty('background-color', INCORRECT, 'important');
                    repassword = false;
                    blockSubmit();
                }
                else{
                    e.style.setProperty('background-color', CORRECT, 'important');
                    this[e.name] = true;
                    if(username && name && password && repassword && birthday && email) allowSubmit();
                }

            }
            break;
    }

}

function turnGreen(e) {
    e.style.setProperty('background-color', CORRECT, 'important');
}

function isValidDate(s) {

    // Match the date format through regular expression
    if(s.match(DATE_REG)|| s.match(DATE_REG_PICKER)) {

        //Test which seperator is used '/' or '-'
        var opera1 = s.split('/');
        var opera2 = s.split('-');
        var lopera1 = opera1.length;
        var lopera2 = opera2.length;

        // Extract the string into month, date and year
        if (lopera1>1){
            var pdate = s.split('/');
        }
        else if (lopera2>1) {
            var pdate = s.split('-');
        }

        var dd = parseInt(pdate[0]);
        var mm  = parseInt(pdate[1]);
        var yy = parseInt(pdate[2]);

        if(s.match(DATE_REG_PICKER)){
            dd = parseInt(pdate[2]);
            mm  = parseInt(pdate[1]);
            yy = parseInt(pdate[0]);
        }
        // Create list of days of a month [assume there is no leap year by default]
        var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];

        if (mm==1 || mm>2) {
            return dd <= ListofDays[mm - 1];
        }
        else if (mm==2) {
            var lyear = false;

            if ( (!(yy % 4) && yy % 100) || !(yy % 400)) {
                lyear = true;
            }

            if ((lyear==false) && (dd>=29)) {
                return false;
            }
            else if ((lyear==true) && (dd>29)) {
                return false;
            }

            else return true;
        }
    }
    else {
        return false;
    }
}

function allowSubmit(){
    document.getElementById('submitbutton').disabled=false;
}

function blockSubmit(){
    document.getElementById('submitbutton').disabled=true;
}

function preventDefault(){
    return name && password && repassword && birthday && email;
}