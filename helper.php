<?php

$start_time = 0;

function startTimer()
{
    global $start_time;

    $mic_time = microtime();
    $mic_time = explode(" ", $mic_time);
    $mic_time = $mic_time[1] + $mic_time[0];
    $start_time = $mic_time;
}

function finishTimer()
{
    global $start_time;

    $mic_time = microtime();
    $mic_time = explode(" ",$mic_time);
    $mic_time = $mic_time[1] + $mic_time[0];
    $endtime = $mic_time;
    $total_execution_time = ($endtime - $start_time);

    return $total_execution_time;
}

function printTime($time, $command = false)
{
    if ($command) {
        echo 'S: '.PHP_EOL.'S: ';
    }
    echo '<p><span class="icon-time"></span> <strong>'.$time.'</strong> sec.</p>' . PHP_EOL;
}

function printLogs($logs, $num = 1)
{
    $data = explode(PHP_EOL, $logs);

    $s = [];
    $i = -1;
    foreach ($data as $line) {
        if (substr($line, 0, 3) === 'C: ') {
            $i++;
            $s[$i]['request'] = substr($line, 3);
        }
        elseif (substr($line, 0, 3) === 'S: ') {
            $s[$i]['response'][] = substr($line, 3);
        }
    }

    foreach ($s as &$line) {
        $line['response'] = implode("<br>\r\n", $line['response']);
    }

    require 'templates/logs.php';
}
