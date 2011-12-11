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
</head>
<body>
<form id="form1" name="form1" method="post" action="forgot.php">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>
    <br>
    <br>
    <span class="Estilo2"><br>
  </span>
<?php
include("func.php");
$usuario = $_POST['usr'];
If($usuario==""){ // Uso la variable en vez del mtodo para validar
?>
  <p align="left"><span class="Estilo2">Ingresá tu E-mail</span><span class="Estilo2"></span><br>
    <input name="usr" type="text" id="usr" size="30">
    <input type="submit" name="button" id="button" value="Send">
    <br>
    <br>

<?php
}else{
  
if (forgotpassword($usuario)){
    echo '<a href="'.mailLink($usuario).'" class="Estilo1">An E-Mail with instructions has been sent.</a><br>';
}else{
    echo '<p class="Estilo2">El E-mail ingresado no existe en nuestra base de datos.</p>';
    echo '<a href="mailto:libreteca@speedy.com.ar" class="Estilo1">Contactanos</a>';
    echo ' o ';
    echo '<a href="newaccount.php" class="Estilo1">crea una nueva cuenta.</a>';
}
}
?>
</form>
</body>
</html>
