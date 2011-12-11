<?php
include("func.php");

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="art/favicon.ico" />
<title>Music Cloud!</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<style>
body {background-image: url(art/banner.jpg);
background-repeat: no-repeat;
}
</style>
<body>
<form id="form1" name="form1" method="post" action="prev.php">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>
  <br>
  <br>
  <br>
  <span class="Estilo2">
  <p>Completa los siguientes datos.</p>
  <p>
  </p>
  <table width="232" cellspacing="1" cellpadding="3" border="0" bgcolor="#E73E8A">
<tr>
   <td width="224"><font color="#FFFFFF" face="arial, verdana, helvetica">
<b>Registro</b>
   </font></td>
</tr>
<tr>
   <td height="57" bgcolor="#FFFFFF"><p><span class="Estilo1"></span>       
        Nombre &nbsp;&nbsp;&nbsp;&nbsp;
            <input name="nomb" type="text" id="nomb" size="25">
    </p>
      <p>País &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="text" name="pais" id="pais" size="25">
    </p>
    <p>E-mail &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="text" name="mail" id="mail" size="25">
</p>
    <p>Password
      <input type="password" name="pass" id="pass" size="25">
</p>
    <p> Confirmar Password &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
      <input type="password" name="pass2" id="pass2" size="25">
</p>
    <p>
      &nbsp;&nbsp;<input type="submit" name="button" id="button" value="Send">
    </p></td>
</tr>
</table>

</head>
</body>
</html>
