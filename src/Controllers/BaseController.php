<?php

namespace Controllers;
use Service\Auth\authInterface;
use Service\Auth\authSessionService;

class BaseController
{
    protected authInterface $authService;

    public function __construct()
    {
        $this->authService = new authSessionService;
    }


}