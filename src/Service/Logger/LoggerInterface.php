<?php

namespace Service\Logger;

interface LoggerInterface
{
    public function Logs(\Throwable $exception);

}