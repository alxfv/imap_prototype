<?php

require_once 'KMImap.php';
require_once 'Mail.php';

require_once 'helper.php';

$yaml = file_get_contents('parameters.yml');
$prms = yaml_parse($yaml);

$defaultHost = $prms['hosts'][0];
$defaultUser = $defaultHost['users'][0];

$username = !empty($_GET['username']) ? $_GET['username'] : $defaultUser['name'];
$password = !empty($_GET['password']) ? base64_decode($_GET['password']) : $defaultUser['password'];
$host =     !empty($_GET['host'])     ? $_GET['host'] : $defaultHost['host'];
$port =     !empty($_GET['port'])     ? $_GET['port'] : $defaultHost['port'];

$imap = new KMImap($host, $port, true, 'UTF-8');

ob_start();
$imap->setDebug();

$mail = new Mail($imap);

try {
    $mail->login($username, $password);
    $mailboxes = $mail->mailboxes();
    $messages = $mail->messages();

    $time = $mail->getTime();
    $logs = $mail->getLogs();
    $totalTime = $mail->getTotalTime();

    require_once 'templates/main.php';

} catch (\Exception $e) {
    ob_end_clean();
    echo $e->getMessage();
}