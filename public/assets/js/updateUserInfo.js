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

    var formData = new FormData();
    var image = document.getElementById("picture").files[0];
    formData.append('picture', image);

    console.log(image);

    $.ajax({
        url: "/profile",
        type: "post",
        contentType: false,
        processData: false,
        data: formData,
        async: false,
        success: function (msg) {

        },
        cache: false
    });
}
