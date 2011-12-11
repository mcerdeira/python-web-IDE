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
<form id="form1" name="form1" method="get">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="slogan." /></a>
    <br>
    <br>
    <br>
<?php
include("func.php");
include("cn.php");
$l_nomb= $_POST['nomb'];
$l_mail = $_POST['mail'];
$l_pass  = $_POST['pass'];
$l_pass2 = $_POST['pass2'];
$l_pais = $_POST['pais'];
$problem = 0;
$qproblem = 0;

if(trim($l_nomb) == ""){
    echo '<p class="Estilo2">Nombre inválido.</p>';
    $problem++;
}else{
    $sql="SELECT * FROM users WHERE nomb=".ssql($l_nomb).
         " OR mail=".ssql($l_mail);
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant!=0){
            echo '<p class="Estilo2">El nombre o el E-mail ya están en uso.</p>';
            $problem++;
        }
    }else{
        echo '<p class="Estilo2">ERROR FATAL</p>';
        $problem++;
    }
    mysql_free_result($rs);
}


if($l_pass == ""){
    echo '<p class="Estilo2">Password vacío.</p>';
    $problem++;
}

if($l_pais == ""){
    echo '<p class="Estilo2">Debes ingresar el país donde habitas.</p>';
    $problem++;
}

if(strlen($l_pass) <= 4){
    echo '<p class="Estilo2">El password debe tener, al menos, 5 caracteres de longitud.</p>';
    $problem++;
}

if($l_pass != $l_pass2){
    echo '<p class="Estilo2">Los passwords no coinciden.</p>';
    $problem++;
}

if(!validateEmail($l_mail)){
    echo '<p class="Estilo2">E-mail inválido.</p>';
    $problem++;
}


if($problem > 0){
    echo '<a href="newaccount.php" class="Estilo1">Back</a>';
}else{
    $l_nomb=strtolower($l_nomb);
    $fake_pwd = str_repeat("*", strlen($l_pass));
    echo '<p class="Estilo1">Name: '.$l_nomb.'</p>';
    echo '<p class="Estilo1">E-Mail: '.$l_mail.'</p>';
    echo '<p class="Estilo1">Password: '.$fake_pwd.'</p>';
    echo '</p>';
    echo '<br><br>';
    //Crear usuario en tabla Users
    $sql = "INSERT INTO users VALUES(";
    $sql = $sql . ssql($l_nomb) . ",";
    $sql = $sql . ssql(md5($l_pass)) . ",";
    $sql = $sql . ssql($l_mail) . ",";
    $sql = $sql . ssql("") . ",";
    $sql = $sql . ssql($l_pais) . "," ;
    $sql = $sql . "-1" . ",";  //Temp User
    $sql = $sql . ssql(encryptData($l_nomb,$l_pass)) . ")"; //Activation hash      
    if(!mysql_query($sql,$cn)){
        $qproblem++;
    }
    if($qproblem > 0){
        echo '<p class="Estilo2">Los sentimos, el usuario no ha sido creado.</p>';
        echo '<p class="Estilo2">Por favor, intentalo más tarde.</p><br>';
    }else{
        print "1";
        if (sendMail($l_mail,$l_nomb,$l_pass)){           
           echo '<p class="Estilo2">El usuario ha sido creado correctamente!</p>';
           echo '<a href="'.mailLink($l_mail).'" class="Estilo1">Recibirás un E-mail con las instrucciones de activación.</a><br>';
        }else{
           echo '<p class="Estilo2">Los sentimos, el usuario no ha sido creado.</p>';
           echo '<p class="Estilo2">Por favor, intentalo más tarde.</p><br>';
        }
    }
}
mysql_close($cn);
?>
</body>
</head>
</html>
