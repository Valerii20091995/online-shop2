<?php
namespace Controllers;

use DTO\AuthenticationDTO;
use Model\User;
use Request\EditProfileRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getRegistrate()
    {
        require_once '../Views/registration_form.php';
    }

    public function registrate(RegistrateRequest $request)
    {
        $errors = $request->Validate();
        if (empty($errors)) {
//            $passwordRepeat = $request->getPassword();
            $password = password_hash($request->getPassword(), PASSWORD_DEFAULT);
            //добавление  новых пользователей
            $user = User::addUser($request->getName(), $request->getEmail(), $request->getPassword());
            $user = User::getByEmail($request->getEmail());
        }
        require_once '../Views/registration_form.php';
    }

    // классицирование логина
    public function getLogin()
    {
        require_once '../Views/login_form.php';
    }
    public function login(LoginRequest $request)
    {
        $errors = $request->Validate();
        if (empty($errors)) {
            $dto = new AuthenticationDTO($request->getEmail(), $request->getPassword());
            $result = $this->authService->auth($dto);
            if ($result === true) {
                header("Location: /catalog");
                exit();
            } else {
                $errors['autorization'] = "Логин или пароль указаны неверно!";
            }
        }
        require_once '../Views/login_form.php';
    }



    // Выдача профиля
    public function profile()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            require_once '../Views/profile_form.php';
        } else {
            header("Location: /login");
        }

    }

    // Изменения профиля
    public function getEditProfile()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
        } else {
            header("Location: /login");
            exit;
        }
        require_once '../Views/edit_profile_form.php';
    }

    public function editProfile(EditProfileRequest $request)
    {
        $this->authService->startSession();
        $errors = $request->Validate();
        if (empty($errors)) {
            $userId = $this->authService->getCurrentUser();
            $user = User::userVerification($userId->getId());
            if ($user->getName() !== $request->getName()) {
                User::updateNamedByID($request->getName(), $userId->getId());
            }
            if ($user->getEmail() !== $request->getEmail()) {
                User::updateEmailByID($request->getEmail(), $user->getId());
            }
            header("Location: ./profile");
            exit;
        }
        require_once '../Views/edit_profile_form.php';
    }


    public function logout()
    {
        $this->authService->logout();
        header("Location: ../login");
    }
}

