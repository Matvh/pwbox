
function openFolder(folder){
    xhttp.open("POST", "/folder", true);
    xhttp.send(folder);
}
