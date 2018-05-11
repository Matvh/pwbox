
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
        accept: "*",
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
        title: "Upload in current folder"
    });
}

// handle files choice when done
on('files.bs.filedialog', function(ev) {
    var files_list = ev.files;
    // DO SOMETHING
});


// handle dialog cancelling
on('cancel.bs.filedialog', function(ev) {
    // DO SOMETHING
});
