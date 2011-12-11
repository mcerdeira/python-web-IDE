<?php
// The message
$message = "Line 1\nLine 2\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70);

// Send
//mail('martincerdeira@gmail.com', 'My Subject', $message);

//print "listo";

print md5("123");

// setear mailer en php.ini en sudo nano /etc/php5/apache2/php.ini

?>

