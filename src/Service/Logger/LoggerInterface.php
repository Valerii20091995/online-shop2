<?php

namespace Service\Logger;

interface LoggerInterface
{
    public function Errors(\Throwable $exception);

}