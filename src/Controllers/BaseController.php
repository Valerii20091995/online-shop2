<?php

namespace Controllers;
use Service\authService;

abstract class BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new authService;
    }


}