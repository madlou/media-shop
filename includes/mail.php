<?php

function sendMail($to, $subject, $body, $from = 'na'){
	date_default_timezone_set('UTC');
    $log  = date(DATE_ATOM) . PHP_EOL;
    $log .= 'From: ' . $from . PHP_EOL;
    $log .= 'To: ' . $to . PHP_EOL;
    $log .= 'Subject: ' . $subject . PHP_EOL;
    $log .= 'Body: ' . PHP_EOL . $body . PHP_EOL;
    $log .= "*****************************************" . PHP_EOL;
    file_put_contents('mail_log.txt', $log, FILE_APPEND);
    $headers = '';
    if($from != 'na'){
        $headers = sprintf("From: %s\r\n", $from);
    }
    mail($to, $subject, $body, $headers);
	return true;
}

?>