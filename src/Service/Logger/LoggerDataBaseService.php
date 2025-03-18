<?php

namespace Service\Logger;

use Model\Logger;

class LoggerDataBaseService implements LoggerInterface
{
    private Logger $loggerError;
    public function __construct()
    {
        $this->loggerError = new Logger();
    }
    public function Logs(\Throwable $exception)
    {
        $this->loggerError->addLog($exception);
    }

}