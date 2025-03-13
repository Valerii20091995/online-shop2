<?php

namespace Service;

use DTO\AuthenticationDTO;
use Model\User;

class authService
{
    public User $userModel;
    public function __construct()
    {
        $this->userModel = new User;
    }

    public function check(): bool
    {
        $this->startSession();
        return isset($_SESSION['userId']);
    }
    public function getCurrentUser(): ?User
    {
        $this->startSession();

        if ($this->check()) {
            $userId = $_SESSION['userId'];
            return $this->userModel->userVerification($userId);
        } else {
            return null;
        }
    }
    public function auth(AuthenticationDTO $data): bool
    {
        $user = $this->userModel->getByEmail($data->getEmail());
        if ($user === null) {
            return false;
        } else {
            $passwordDB = $user->getPassword();
            if (password_verify($data->getPassword(), $passwordDB)) {
                //вход через сессии
                $this->startSession();
                $_SESSION['userId'] = $user->getId();
                return true;
            } else {
                return false;
            }
        }
    }
    public function logout()
    {
        $this->startSession();
        session_destroy();
    }

    public function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();

        }
    }

}