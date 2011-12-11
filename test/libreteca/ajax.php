<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SlackChat - The OpenSource WebChat</title>
<style type="text/css">
<!--
body {
	background-color: #003366;
}
.Estilo1 {
	color: #FFFFFF;
	font-family: "Courier New", Courier, monospace;
}
-->
<body>
</style></head>
<body>
<script language="javascript">
function nuevoAjax(){
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

function sendRequest(vote){
		ajax=nuevoAjax();
		ajax.open("GET", "vote.php?voto="+vote,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
		    document.getElementById('test').innerHTML = ajax.responseText
		}else{
		    document.getElementById('test').innerHTML = '<b>Cargando...</b>';
		}
}
		ajax.send(null)

}

</script>

<form id="test" onsubmit="return false;">
	<input type="submit" value="+" onClick="sendRequest(0)">
	<input type="submit" value="-" onClick="sendRequest(1)">
</form>


