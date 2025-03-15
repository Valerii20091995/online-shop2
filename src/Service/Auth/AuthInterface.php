<?php

namespace Service\Auth;

use DTO\AuthenticationDTO;
use Model\User;

interface AuthInterface
{
    public function check(): bool;
    public function getCurrentUser(): ?User;
    public function auth(AuthenticationDTO $data): bool;
    public function logout();
}