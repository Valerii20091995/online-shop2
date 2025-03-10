<?php
namespace Controllers;

use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;

class UserController extends BaseController
{
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
        parent::__construct();
    }
    public function getRegistrate()
    {
        require_once '../Views/registration_form.php';
    }


    public function registrate(RegistrateRequest $request)
    {
        $errors = $request->Validate();

// сохранение в БД, если нет ошибок
        if (empty($errors)) {
//            $passwordRepeat = $request->getPassword();
            $password = password_hash($request->getPassword(), PASSWORD_DEFAULT);

            //добавление  новых пользователей
            $user = $this->userModel->addUser($request->getName(), $request->getEmail(), $request->getPassword());
            $user = $this->userModel->getByEmail($request->getEmail());
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
            $result = $this->authService->auth($request->getEmail(), $request->getPassword());
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

    public function editProfile()
    {
        $this->authService->startSession();
        $errors = $this->ValidateProfile($_POST);

        if (empty($errors)) {

            $userId = $_SESSION['userId'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $user = $this->userModel->userVerification($userId);


            if ($user->getName() !== $name) {

                $this->userModel->updateNamedByID($name, $userId);
            }

            if ($user->getEmail() !== $email) {
                $this->userModel->updateEmailByID($email, $userId);
            }
            header("Location: ./profile");
            exit;
        }
        require_once '../Views/edit_profile_form.php';
    }

    private function ValidateProfile(array $data): array
    {
        $errors = [];
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                $errors['name'] = "Имя не может содержать меньше 3 символов";
            }
        }
        if (isset($data['email'])) {
            $email = $data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный email";
            } else {
                $user = $this->userModel->getByEmail($email);
                $userId = $_SESSION['userId'];
                if ($user && $user->getEmail() === $email && $user->getId() !== $userId) {
                    $errors['email'] = "Этот Email уже зарегистрирован!";
                }
            }
        }
        return $errors;
    }
    public function logout()
    {
        $this->authService->logout();
        header("Location: ../login");
    }
}

