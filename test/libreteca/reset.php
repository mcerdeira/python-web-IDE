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
<?php
include("func.php");
$uid = $_GET['uid'];
$id = $_GET['id'];
$password1 = $_POST['pass'];
$password2 = $_POST['pass2'];
$email = $_POST['mail'];
echo '<form id="form1" name="form1" method="post" action="reset.php">';
?>
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>
    <br>
    <br>
    <span class="Estilo2"><br>
  </span>
<?php
If(($uid=="" or $id=="") and $email==""){ // Uso la variable en vez del mtodo para validar
?>
  <br>
  <p class="Estilo2">La información no parece ser correcta.</p>
  <p class="Estilo2">Por favor, revisa el link que te trajo aquí.</p>
<?php
}else{
    If(($uid=="" or $id=="") and $email!=""){ // Uso la variable en vez del mtodo para validar
        $problem = 0;
        $qproblem = 0;
        if($password1 == ""){
            echo '<p class="Estilo2">Password vacío.</p>';
            $problem++;
        }
        if(strlen($password1) <= 4){
            echo '<p class="Estilo2">El password debe tener, al menos, 5 caracteres de longitud.</p>';
            $problem++;
        }
        if($password1 != $password2){
            echo '<p class="Estilo2">Los passwords no coinciden.</p>';
            $problem++;
        }
        if($problem == 0){
            if(changPassword($email,$password1)){
                echo '<p class="Estilo2">El password fue cambiado!</p>';
                echo '<a href="signin.php" class="Estilo1">Ingresa con tus nuevos datos.</a><br>';
            }else{
                echo '<p class="Estilo2">Lo sentimos, tu password no ha sido modificado.</p>';
                echo '<p class="Estilo2">Por favor, intentalo más tarde.</p><br>';
            }
        }
    }else{
        if (resetpassword($uid,$id)){
            echo '<p>Nuevo Password
                  <input type="password" name="pass" id="pass" size="25">
                  </p>
                  <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="password" name="pass2" id="pass2" size="25">
                  </p';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="button" id="button" value="Send">';
            echo '<input type="hidden" name="mail" id="mail" value="'.$uid.'" />';

        }else{
            echo '<p class="Estilo2">La información no parece ser correcta.</p>';
            echo '<p class="Estilo2">Por favor, revisa el link que te trajo aquí.</p>';
        }
    }
}
?>

</form>
</body>
</html>
