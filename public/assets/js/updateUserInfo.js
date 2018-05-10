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
            message = msg['responseJSON']['photo'];
            document.getElementById("message").innerHTML = "";
            document.getElementById("message").innerHTML = message;
        },
        cache: false
    });
}
