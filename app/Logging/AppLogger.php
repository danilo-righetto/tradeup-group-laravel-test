<?php

namespace App\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLogger
{
    private array $logSequence = [];
    private string $channel = 'tradeup-group-laravel-test';
    private array $level = [
        'info' => 'INFO',
        'debug' => 'DEBUG',
        'warning' => 'WARNING',
        'error' => 'ERROR',
        'critical' => 'CRITICAL',
        'notice' => 'NOTICE',
        'alert' => 'ALERT',
        'emergency' => 'EMERGENCY',
    ];

    private static int $sequence = 0;
    private array $steps = [];

    private array $stepDefinition = [
        'name' => '',
        'datetime' => '',
        'trace' => 0,
        'data' => []
    ];

    function logInfo(string $message, array $context = []): AppLogger
    {
        $context['steps'] = $this->steps;

        $this->logSequence[] = ['level' => $this->level['info'], 'message' => $message, 'context' => $context];
        return $this;
    }

    function logWarning(string $message, $context = [])
    {
        $context['steps'] = $this->steps;
        $this->logSequence[] = ['level' => $this->level['warning'], 'message' => $message, 'context' => $context];
    }

    function logError(string $message, array $context = []): AppLogger
    {
        $context['steps'] = $this->steps;
        $this->logSequence[] = ['level' => $this->level['error'], 'message' => $message, 'context' => $context];
        return $this;
    }

    function logCritical(string $message, $context = [])
    {
        $context['steps'] = $this->steps;
        $this->logSequence[] = ['level' => $this->level['critical'], 'message' => $message, 'context' => $context];
        return $this;
    }

    function logEmergency(string $message, $context = [])
    {
        $context['steps'] = $this->steps;
        $this->logSequence[] = ['level' => $this->level['emergency'], 'message' => $message, 'context' => $context];
    }

    function steps(string $name, array $payload = []): void
    {
        $this->stepDefinition['name'] = $name;
        $this->stepDefinition['trace'] = self::$sequence;
        $this->stepDefinition['datetime'] = date('Y-m-d H:i:s');
        $this->stepDefinition['data'] = $payload;
        $this->steps[self::$sequence++] = $this->stepDefinition;
    }

    function showLogs()
    {
        $logger = new Logger($this->channel);
        $handler = new StreamHandler('php://stdout', Logger::DEBUG);
        $formatter = new CustomJsonFormatter();
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        foreach ($this->logSequence as $log) {
            switch ($log['level']) {
                case $this->level['info']:
                    $logger->info($log['message'], $log['context']);
                    break;
                case $this->level['warning']:
                    $logger->warning($log['message'], $log['context']);
                    break;
                case $this->level['error']:
                    $logger->error($log['message'], $log['context']);
                    break;
                case $this->level['debug']:
                    $logger->debug($log['message'], $log['context']);
                    break;
                case $this->level['alert']:
                    $logger->alert($log['message'], $log['context']);
                    break;
                case $this->level['notice']:
                    $logger->notice($log['message'], $log['context']);
                    break;
                case $this->level['emergency']:
                    $logger->emergency($log['message'], $log['context']);
                    break;
                case $this->level['critical']:
                    $logger->critical($log['message'], $log['context']);
                    break;
                default:
                    $logger->debug($log['message'], $log['context']);
            }
        }

        $this->logSequence = [];
        $this->steps = [];
        self::$sequence = 0;
    }
}
