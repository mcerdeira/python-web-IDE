<?php
$host="localhost:3306";
$user="root";
$pass="123456";
$db="libreteca";
$cn = mysql_connect($host,$user, $pass);
if (!$cn)
  {
  echo "Could not connect: " . mysql_error();
  }
mysql_select_db($db);
?>
