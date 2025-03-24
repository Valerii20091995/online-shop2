<?php

namespace Service\Logger;

use Model\Logger;

class LoggerDataBaseService implements LoggerInterface
{
    public function Logs(\Throwable $exception)
    {
        Logger::addLog($exception);
    }

}