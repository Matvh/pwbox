
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

function deleteNotification(element){
    element.remove();
}

function openFileDialog() {

    $.FileDialog({
        // MIME type of accepted files, e. g. image/jpeg
        accept: "png, md, pdf, txt, gif",
        cancelButton: "Close",
        dragMessage: "Drop files here",
        dropheight: 400,
        errorMessage: "An error occured while loading file",
        multiple: true,
        okButton: "OK",
         // file reading mode.
        // BinaryString, Text, DataURL, ArrayBuffer
        readAs: "DataURL",
        removeMessage: "Remove&nbsp;file",
        title: "Upload in current folder",

    })
        //Event OK
        .on('files.bs.filedialog', function(ev) {
        var files_list = ev.files;
        console.log(files_list);
        uploadFiles(files_list);
        })

        //Event cancel
        .on('cancel.bs.filedialog', function(ev) {
            console.log("cancel")
        });

    ;
}


function uploadFiles(files_list){

    console.log(files_list);
    var message;

    var formData = new FormData();
    for (var i = 0; i < files_list.length; i++){
        formData.append('files[]', files_list[i]);
    }

    $.ajax({
        url: "/file",
        type: "post",
        contentType: false,
        processData: false,
        data: formData,
        async: false,
        success: function (msg) {
            window.location.replace("/home");
        },
        error: function (msg) {
            window.location.replace("/home");
        },
        cache: false
    });
}