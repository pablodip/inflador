<?php

require_once(__DIR__.'/vendor/autoload.php');

use Symfony\Component\Process\Process;

$commands = array(
    'php vendor/bin/phpunit',
    'php vendor/bin/behat'
);

exit(run_commands($commands));

function run_commands($commands) {
    return array_reduce($commands, function ($exitCode, $command) {
        return run_command($command) !== 0 ? 1 : $exitCode;
    }, 0);
}

function run_command($command) {
    $process = new Process($command);
    $process->run(function ($type, $data) {
        if ('out' === $type) {
            echo $data;
        }
    });

    return $process->getExitCode();
}