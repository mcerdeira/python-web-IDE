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
<form id="form1" name="form1" method="get">
  <p align="left">
    <a href="index.php"><img src="art/logo.png" width="171" height="39" border="0" usemap="#lala" title="All the music, everywhere." /></a>
    <br>
    <br>

<?php
/*
Need to add
file_uploads = On
max_file_uploads=20
in php.ini file.
*/

include("cn.php");
include("func.php");
$base_path = "uploads/";
$i = 0;

foreach($_FILES["uploadedfile"]["name"] as $file){
    if (trim($file) != "") {
        $target_path = $base_path.basename($file);
        if(move_uploaded_file($_FILES["uploadedfile"]["tmp_name"][$i], $target_path)) {
            //Saber si es un archivo de audio
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            if(finfo_file($finfo, getcwd()."//uploads//".$file) == "audio/mpeg"){
               echo '<span class="Estilo2">The file "'.basename($file).'" has been uploaded.</span><br>';
            }else{
               unlink(getcwd()."//uploads//".$file);
               echo '<span class="Estilo2">ERROR! The file "'.basename($file).'" is not an mp3 file.</span><br>';
            }
            finfo_close($finfo);
        } else{
            echo '<span class="Estilo2">There was an error uploading the files, please try again!</span><br>';
        }
    }
    $i++;
}
?>
</head>
</body>
</html>
