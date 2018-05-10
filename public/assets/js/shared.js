
function openFolder(folder){

    var info = {
        'id_folder' : folder
    };

    $.ajax({
        url: "/enterSharedFolder",
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

function deleteNotification(element){
    element.remove();
}

