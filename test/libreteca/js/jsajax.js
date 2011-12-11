function newAjax(){
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); // code for IE7+, Firefox, Chrome, Opera, Safari
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function sendVote(vote){
    ajax=nuevoAjax();
    ajax.open("GET", "vote.php?voto="+vote,true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            document.getElementById('test').innerHTML = ajax.responseText
        }else{
            document.getElementById('test').innerHTML = '<b>Loading...</b>';
        }
    }
    ajax.send(null)
}

$(document).ready(function() {
    $("#message").fadeIn("slow");
});

function closeNotice() {
    $("#message").fadeOut("slow");
}

function btn_reset(song){  // Adds a song to playlist
    ajax=newAjax();
    ajax.open("GET", "add.php?song="+song,true);
    parent.playframe.location.reload();
    ajax.send(null)
}
