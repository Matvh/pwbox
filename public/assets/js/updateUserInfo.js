function updateInfo() {

    //TODO check if white space

    $.post(
        'profile',
        {
            email: document.getElementById("email").value,
            password: document.getElementById("pass").value,
            picture: document.getElementById("picture").value
        },
        function (result) {
            if (result === "success"){
                console.log("success");
                //TODO el redirect en el callback del js o en el php?
            }else{
                console.log("error");
            }
        }
    );
}

function updatePhoto(){

    /*$.post(
        'profile',
        {
            picture: document.getElementById("picture").value
        },
        function (result) {
            if (result === "success"){
                console.log("success");
                //TODO el redirect en el callback del js o en el php?
            }else{
                console.log("error");
            }
        }
    );*/

    var formData = new FormData($("picForm")[0]);

    $.ajax({
        url: "profile",
        type: "POST",
        data: formData,
        async: false,
        success: function (msg) {
            alert(msg)
        },
        cache: false,
        contentType: false,
        processData: false
    });
}
