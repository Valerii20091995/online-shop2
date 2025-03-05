<?php

namespace Controllers;
use Service\authService;

class BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new authService;
    }


}