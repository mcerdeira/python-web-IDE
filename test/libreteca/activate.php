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
<form id="form1" name="form1" method="post" action="index.php">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>
    <br>
    <br>
    <br>
<?php
include("func.php");
$hashid= $_GET['id'];

if (!activateUser($hashid)){
   echo '<p class="Estilo2">User Activated!</p>';
   echo '<p class="Estilo2">Please login with user and password</p><br>';
   echo '<a href="signin.php">Sign In</a>';
}else{
   echo '<p class="Estilo2">Sorry, your user has not been activated.</p>';
   echo '<p class="Estilo2">Please try again later.</p><br>';
}
?>
</body>
</head>
</html>
