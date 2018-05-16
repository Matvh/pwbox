var PASS_REG = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,12}$/;
var MAIL_REG = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var DATE_REG = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
var DATE_REG_PICKER = /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/;

var CORRECT = 'rgb(225, 242, 230)';
var INCORRECT = 'rgb(244, 231, 219)';

var password;
var repassword;
var email;

window.onload = function(){
    document.getElementById('submitbutton').disabled=true;
}


function checkError(e, t) {

    switch (t){
        case 'mail':

            if(!MAIL_REG.test(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                email = false;
                blockSubmit();
            }
            else{
                e.style.setProperty('background-color', CORRECT, 'important');
                email = true;
                if((password && repassword) || email ) allowSubmit();
            }
            break;



        case 'pass':

            if(!PASS_REG.test(e.value)){
                e.style.setProperty('background-color', INCORRECT, 'important');
                if(e.name == "pass_re") repassword = false;
                else password = false;
                blockSubmit();
            }
            else{
                if(e.name == "pass_re" && e.value != document.getElementById("password").value){
                    e.style.setProperty('background-color', INCORRECT, 'important');
                    repassword = false;
                    blockSubmit();
                }
                else{
                    e.style.setProperty('background-color', CORRECT, 'important');
                    if(e.name == "pass_re") repassword = true;
                    else password = true;
                    if((password && repassword) || email ) allowSubmit();
                }

            }
            break;
    }

}

function allowSubmit(){
    document.getElementById('submitbutton').disabled=false;
}

function blockSubmit(){
    document.getElementById('submitbutton').disabled=true;
}

function preventDefault(){
    return email || (password && repassword);
}

function updateInfo() {

    var info = {
        'email' : document.getElementById("email").value,
        'password': document.getElementById("pass").value,
        'pass_re' :document.getElementById("pass_re").value
    };

    var message;

    $.ajax({
        url: "/profile",
        type: "post",
        data: info,
        async: true,
        success: function(msg) {
            console.log(msg);
            //console.log(msg['email']);
            //console.log(msg['password']);
            if (typeof msg['email'] !== 'undefined'){
                console.log(msg['email']);
                message = msg['email'] + '<br>';
            }

            if (typeof msg['password'] !== 'undefined') {
                console.log(msg['password']);
                message = message + msg['password'];
            }

            document.getElementById("message").innerHTML = "";
            document.getElementById("message").innerHTML = message;

        },error: function (msg,responseJSON){
            console.log(msg['responseJSON']);
            console.log(msg);

            if (typeof msg['responseJSON']['email'] !== 'undefined'){
                console.log(msg['responseJSON']['email']);
                message = msg['responseJSON']['email'] + '<br>';
            }
            if (typeof msg['responseJSON']['password'] !== 'undefined'){
                console.log(msg['responseJSON']['password']);
                message = message + msg['responseJSON']['password'];
            }
            document.getElementById("message").innerHTML = "";
            document.getElementById("message").innerHTML = message;
        },
        cache: false
    });
}


function updatePhoto(){

    var message;

    var formData = new FormData();
    var image = document.getElementById("picture").files[0];
    formData.append('picture', image);

    $.ajax({
        url: "/profile",
        type: "post",
        contentType: false,
        processData: false,
        data: formData,
        async: false,
        success: function (msg) {

            message = msg['photo'];
            document.getElementById("message").innerHTML = "";
            document.getElementById("message").innerHTML = message;
        },
        error: function (msg) {
            if (typeof msg['responseJSON']['photo'] !== 'undefined'){
            message = msg['responseJSON']['photo'];
            document.getElementById("message").innerHTML = "";
            document.getElementById("message").innerHTML = message;
            }
        },
        cache: false
    });
}
