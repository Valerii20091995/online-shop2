<?php

namespace Request;

abstract class Request
{
    public function __construct(protected array $data)
    {
    }

}