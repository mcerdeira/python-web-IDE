<?php
// Constants
define("QTE",chr(34));

// ***********************************
// Functions
// ***********************************

function ssql($v){
    $valo="'".$v."'";
    return $valo;
}

function resetpassword($email,$id){ // Checks data for password change
    include("cn.php");
    $sql="SELECT pass FROM Users
    WHERE mail=".ssql($email).
    " AND nive<>-1"; //Not temp Users
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant==0){
            $retVal = False;
        }else{
            $f=mysql_fetch_array($rs);
            $pwd = $f["pass"];
            $cid = encryptData($email,$pwd);
            if ($cid == $id){
                $retVal = True;
            }else{
                $retVal = False;
            }
        }
        mysql_free_result($rs);
    }else{
        $retVal = False;
    }
    mysql_close($cn);
    return $retVal;
}

function forgotpassword($email){ // Checks if a user and password exists
    include("cn.php");
    $sql="SELECT * FROM Users
    WHERE mail=".ssql($email).
    " AND nive<>-1"; //Not temp Users
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant==0){
            $retVal = False;
        }else{
             $f=mysql_fetch_array($rs);
             $pwd = $f["pass"];
             $body = "Hi there. If you want to reset your password, follow this link: \n
             http://www.musiccloud.com.ar/reset.php?uid=$email&id=".encryptData($email,$pwd). "\n
             If you don't ask for a password reset, please ignore this message. \n
             Enjoy!
             Music Cloud, all the music, everywhere.";
             sendCustomMail($email,"Your Music Cloud Password",$body);
             $retVal = True;
        }
        mysql_free_result($rs);
    }
    mysql_close($cn);
    return $retVal;
}

function validateEmail($email){ // Validates email address, regex way
    if (is_string($email) && !empty($email) && eregi("^[a-z0-9_-]+[a-z0-9_.-]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$", $email))
    {
        return True;
    }else{
        return False;
    }
}

function changPassword($email,$pwd) { // Change a user password
    include("cn.php");
    $sql = "UPDATE Users SET pass=". ssql(md5($pwd)). "
    WHERE mail=".ssql($email);
    if(mysql_query($sql,$cn)){
        mysql_close($cn);
        $retVal = True;
    }else{
        $retVal = False;
    }    
    mysql_close($cn);
    return $retVal;    
}

function mailLink($mail){   // Generates a custom mail link, based on host
    $mail = strtolower($mail);
    $b1 = strchr($mail, "@");
    $b2 = strchr($b1, ".");
    $host = str_replace($b2 ,"",$b1);
    $host = str_replace("@","",$host);
    switch($host){
      case "gmail":
          return "http://www.gmail.com";
      case "hotmail":
          return "http://www.hotmail.com";
      case "yahoo":
          return "http://www.yahoo.com";
      case "aol":
          return "http://webmail.aol.com";
      default:
          return "index.php";
    }
}

function validateUser($usuario,$password){ // Checks if a user and password exists
    include("cn.php");
    $sql="SELECT * FROM users
    WHERE mail=".ssql($usuario).
    " AND pass=".ssql(md5($password)).
    " AND nive<>-1"; //Not temp Users
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant==0){
            $f["id"] = "-1";
            $retVal = $f["id"];
        }else{
            $f=mysql_fetch_array($rs);
            $retVal = $f; // Devolver datos del usuario
        }
        mysql_free_result($rs);
    }else{
        $f["id"] = "-1";
        $retVal = $f["id"];        
    }
    mysql_close($cn);
    return $retVal;
}

function activateUser($id){ // Activate a user if hash is correct
    include("cn.php");
    $sql="SELECT * FROM users WHERE nive=-1". //ONLY temp Users
         " AND ahas=".ssql($id);
    if($rs=mysql_query($sql,$cn)){
        $cant=mysql_num_rows($rs);
        if($cant==0){
            mysql_free_result($rs);
            $retVal = False;
        }else{
            $f=mysql_fetch_array($rs);
            $mail = $f["mail"];
            mysql_free_result($rs);
            $sql = "UPDATE users SET nive=0".
                   " WHERE mail=".ssql($mail);
            mysql_query($sql,$cn);
            $retVal = True;
        }
    }
    mysql_close($cn);
    return $retVal;
}

function encryptData($name,$pwd){ // Generates activation hash id
    return md5("123".$name."pepe".$pwd."567");
}

function sendMail($destination,$name,$pwd){  // Sends activation email
 require_once "Mail.php";

 $from = "Music Cloud <musiccloud@speedy.com.ar>";
 $to = "<$destination>";
 $subject = "Your Music Cloud Account is Ready!";
 $body = "Hi there, $name, and welcome to Music Cloud!!! \n

 With this account, you cant edit music information and upload mp3 files to our database.
 
 In order to activate your new account, please follow this link: \n

 http://www.libreteca.com.ar/activate.php?id=".encryptData($name,$pwd)." \n

 Enjoy!
 
 Music Cloud, all the music, everywhere.

 ";

 $host = "mail.speedy.com.ar";
 $username = "musiccloud@speedy.com.ar";
 $password = "4882104";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
   
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
     
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
      return False;
  } else {
      return True;
  }
}

function sendCustomMail($destination,$subject,$body){  // Sends customs emails
 require_once "Mail.php";

 $from = "Music Cloud <musiccloud@speedy.com.ar>";
 $to = "<$destination>";
 $host = "mail.speedy.com.ar";
 $username = "musiccloud@speedy.com.ar";
 $password = "4882104";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
      return False;      
  } else {
      return True;
  }
}
?>
