var PASS_REG = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,12}$/;
var MAIL_REG = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

var CORRECT = 'rgb(225, 242, 230)';
var INCORRECT = 'rgb(244, 231, 219)';

var name = false;
var password = false;
var repassword = false;
var birthday = false;
var email = false;
var username = false;

window.onload = function(){
    document.getElementById('submitbutton').disabled=true;
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
            console.log(name + password + repassword + birthday + email + username)
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
    return true;
    //var bits = s.split('/');
    //var d = new Date(bits[2], bits[1] - 1, bits[0]);
    //return d && (d.getMonth() + 1) == bits[1];
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