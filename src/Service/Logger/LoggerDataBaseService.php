<?php

namespace Service\Logger;

use Model\LoggerError;

class LoggerDataBaseService implements LoggerInterface
{
    private LoggerError $loggerError;
    public function __construct()
    {
        $this->loggerError = new LoggerError();
    }
    public function Errors(\Throwable $exception)
    {
        $this->loggerError->addError($exception);
    }

}