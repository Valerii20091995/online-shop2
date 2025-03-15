<?php

namespace Controllers;
use Service\Auth\AuthInterface;
use Service\Auth\authSessionService;

class BaseController
{
    protected AuthInterface $authService;

    public function __construct()
    {
        $this->authService = new authSessionService;
    }


}