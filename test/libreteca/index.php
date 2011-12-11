<?php
include("func.php");

session_start(); // Start Session (always)

if($_GET['kill']){ // Kill session on SignOut
    session_destroy();
    $_SESSION[access] = False;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="js/jquery.min.js"></script>
<script src="js/jsajax.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="art/favicon.ico" />
<title>LibreTeca!</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />

<style type="text/css">
#background {
  position: fixed;
  top: 5;
  left: 0;
  width: 100%;
  height: 100%;
}

#content {
  position: relative;
  z-index: 1;
}
</style>
<div id="content">
<?php
$query = $_GET['filtro']; //El criterio de busqueda (lo saco del array de GET en vez de $_POST)
$usuario = $_POST['usr'];
$clave = $_POST['pwd'];

// Ver si la clave y usuario están bien
if ($_SESSION[access] == false and $usuario!=""){
    $var = validateUser($usuario,$clave);
    if ($var!="-1"){
        $_SESSION[access] = True;
        $_SESSION[user] = $usuario;
        $_SESSION[nomb] = $var["nomb"];
    }else{
        $_SESSION[access] = False;
        $_SESSION[user] = "-1";
        $_SESSION[nomb] = "-1";
        // Se igualan en -1 para mostrar cartel de Wrong password con JavaScript
    }
}

If($query==""){ // Uso la variable en vez del mtodo para validar
?>

<?php
if($_SESSION[user] == "-1"){ //Mostrar mensaje de Wrong password
    $_SESSION[user] = "";  //Para que no muestre el cartel de Wrong password todo el tiempo
    $_SESSION[nomb] = "";
    echo '<div id="message" style="display: none;">';
         echo '<span>Lo sentimos, usuario o password incorrecto.</span>';
         echo '<a href="#" class="close-notify" onclick="closeNotice()">X</a>';
    echo '</div>';
}
?>

<div id="seekhosting-banner-right-1-1">
<a href="about.php" title="Enterate de qu&eacute; es libreteca">Acerca de</a> &nbsp;&nbsp; <a href="random.php" title="B&uacute;squeda Aleatoria">Aleatorio</a> &nbsp;&nbsp;</a>

<?php
if($_SESSION[access]){    
    echo '<a href="settings.php" title="Mi cuenta">'.$_SESSION[nomb].'</a>';
    
    echo '&nbsp;&nbsp;';
    echo '<a href="index.php?kill=1">Salir</a>';
}else{
  echo '<a href="signin.php" title="Ingres&aacute a tu cuenta">Ingresar</a>';
}
?>

</div>

<body>
<p>&nbsp;</p>
<p align="center"><img src="art/logo.png" width="378" height="94" border="0" usemap="#lala" title="All the music, everywhere." /></p>
<form id="form1" name="form1" method="get">
  <label>
    <div align="center">
      <div align="center">
        <input name="filtro" type="text" id="filtro" size="80" />
        <input type="submit" name="button" id="button" value="Buscar"/>
        </div>
    </label>
</form>
<div align="center"></div>

<p>&nbsp; </p>

<?php
}else{
?>

</head>
<style>
body {background-image: url(art/banner.jpg);
background-repeat: no-repeat;
}
</style>
<body>
<form id="form1" name="form1" method="get">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>

<?php
}
?>

<?php
If($query!=""){  // Uso la variable en vez del mtodo para validar
    include("cn.php");
    echo '<input name="filtro" type="text" id="filtro" value="'.$query.'" size="60"/>';
    echo '<input type="submit" name="button" id="button" value="Buscar"/>';
    echo '</p>';
    echo '</form>';
    echo '<p class="Estilo1">Resultados para: "'.$query.'"</p>';
    echo '<br>';
    $sql = "SELECT m.id,m.name,m.artist,g.name AS gname,m.cover,u.nomb
            FROM libros AS m
            LEFT JOIN generos AS g ON g.genre=m.genre
            LEFT JOIN users AS u ON u.mail=m.usrid
            WHERE m.name LIKE '%$query%'
            OR m.artist LIKE '%$query%'"; // Agregar tags como filtro
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant==0){
            echo '<p><font color="#000000" face="Courier New">No se han encontrado resultados.</font></p>';
            echo '<br>';
        }else{
            while($fila=mysql_fetch_array($rs)){
                $strhit = $fila["name"]." - ".$fila["artist"];
                echo '<a href="file://'.$fila["name"].'"><font color="blue" face="Arial" size="3">'.$strhit.'</font></a>';
                echo '<br>';
                echo '<span class="Estilo4">En la biblioteca de '.$fila["nomb"].'</span>';
                echo '<br>';
                if ($fila["cover"] != ""){
                    echo '<br>';
                    echo '<br>';
                    echo '<img src="'.$fila["cover"].'"width="90" height="90" title="Album Cover"/>';
                }
                $wiki = "http:////es.wikipedia.org//w//index.php?title=Special%3ASearch&redirs=0&search=".$fila["name"];
                echo '<br>';
                echo '<a href="'.$wiki.'"><font color="#blue" face="Arial" size="2">More Info</font></a>';
                echo '<HR><br>';
            }
        }
    }else{
       echo $sql;
       echo '<p><font color="#000000" face="Courier New">FATAL ERROR</font></p>';
       echo '<br>';
    }
    mysql_free_result($rs);
    mysql_close($cn);
}
?>
</div>
</head>
</body>
</html>
