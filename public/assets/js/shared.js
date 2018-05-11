
function openFolder(folder, inici){
    console.log(folder + " " + inici)
    var info = {
        'id_shared_folder' : folder,
        'inici' : inici
    };

    $.ajax({
        url: "/shared",
        type: "post",
        data: info,
        async: true,
        success: function(msg) {
            console.log(msg);
            window.location.replace("/shared");
        },error: function (msg,responseJSON){
            console.log(msg + 'error');
        },
        cache: false
    });

}

function loadShared(){

    window.location.replace("/shared");
}

function deleteNotification(element){
    element.remove();
}

