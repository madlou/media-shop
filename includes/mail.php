<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
//sendMail("test@gmail.com", "test mail", "test body", "test@test.co.uk");

function sendMail($to, $subject, $body, $from = 'na'){
    $log = '';
    $log .= str_replace(',', ';', $from) . ',';
    $log .= str_replace(',', ';', $to) . ',';
    $log .= str_replace(',', ';', $subject) . ',';
    $log .= str_replace(',', ';', $body) . PHP_EOL;
    file_put_contents('mail_log.txt', $log, FILE_APPEND);
    $headers = '';
    if($from != 'na'){
        $headers = sprintf("From: %s\r\n", $from);
    }
    mail($to, $subject, $body, $headers);
}

?>