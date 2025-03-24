<?php

namespace Service\Auth;

use DTO\AuthenticationDTO;
use Model\User;

class authCookieService implements AuthInterface
{
    public User $userModel;
    public function __construct()
    {
        $this->userModel = new User;
    }

    public function check(): bool
    {
        return isset($_COOKIE['userId']);
    }
    public function getCurrentUser(): ?User
    {
        if ($this->check()) {
            $userId = $_COOKIE['userId'];
            return User::userVerification($userId);
        } else {
            return null;
        }
    }
    public function auth(AuthenticationDTO $data): bool
    {
        $user = User::getByEmail($data->getEmail());
        if ($user === null) {
            return false;
        } else {
            $passwordDB = $user->getPassword();
            if (password_verify($data->getPassword(), $passwordDB)) {
                //вход через сессии

                setcookie('userId', $user->getId());
                return true;
            } else {
                return false;
            }
        }
    }
    public function logout()
    {
       setcookie('userId', '', time() - 3600, '/');
       unset($_COOKIE['userId']);
    }

}