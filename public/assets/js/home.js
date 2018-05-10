
function openFolder(folder){

    var info = {
        'id_folder' : folder
    };

    $.ajax({
        url: "/folder",
        type: "post",
        data: info,
        async: true,
        success: function(msg) {
            window.location.replace("/home");
            console.log(msg);
        },error: function (msg,responseJSON){
            console.log(msg['responseJSON']);
        },
        cache: false
    });

}

