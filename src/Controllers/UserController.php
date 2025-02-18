<?php
class UserController
{
    public function getRegistrate()
    {
        require_once '../Views/registration_form.php';
    }

    private function ValidateRegistration(array $data): array
    {
        $errors = [];

// объявление и валидация данных
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                $errors['name'] = "Имя не может содержать меньше 3 символов";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data['mail'])) {
            $email = $data['mail'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный email";
            } else {
                require_once '../Model/User.php';
                $userModel = new User();
                $user = $userModel->getByEmail($email);

                if ($user !== false) {
                    $errors['email'] = "Этот Email уже зарегестрирован!";
                }
            }
        } else {
            $errors['email'] = "Email должен быть заполнен";
        }
// проверка совпадения паролей
        if (isset($data['psw'])) {
            $password = $data['psw'];
            if (strlen($password) < 3) {
                $errors['psw'] = "Пароль не может содержать меньше 3 символов";
            }
            $passwordRepeat = $data["psw-repeat"];
            if ($password !== $passwordRepeat) {
                $errors['psw-repeat'] = "Пароли не совпадают!";
            }
        } else {
            $errors['psw'] = "Пароль должен быть заполнен!";
        }
        return $errors;
    }

    public function registrate()
    {
        $errors = $this->ValidateRegistration($_POST);

// сохранение в БД, если нет ошибок
        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['mail'];
            $password = $_POST['psw'];
            $passwordRepeat = $_POST['psw-repeat'];
            $password = password_hash($password, PASSWORD_DEFAULT);

            //добавление  новых пользователей
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->addUser($name, $email, $password);
            $user = $userModel->getByEmail($email);
        }
        require_once '../Views/registration_form.php';
    }

    // классицирование логина
    public function getLogin()
    {
        require_once '../Views/login_form.php';
    }

    public function login()
    {
        $errors = $this->ValidateLogin($_POST);
        if (empty($errors)) {
            $email = $_POST['username'];
            $password = $_POST['password'];
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->getByEmail($email);

            if ($user === false) {
                $errors['username'] = "Логин или пароль указаны неверно!";
            } else {
                $passwordDB = $user['password'];

                if (password_verify($password, $passwordDB)) {

                    //вход через сессии
                    session_start();
                    $_SESSION['userId'] = $user['id'];
                    header("Location: /catalog");
                } else {
                    $errors['username'] = "Логин или пароль указаны неверно!";
                }
            }
        }
        require_once '../Views/login_form.php';
    }

    private function ValidateLogin(array $date): array
    {
        $errors = [];
        // проверка наличия переменных
        if (!isset($date['username'])) {
            $errors['username'] = "Поле Username обязательно для заполнения!";
        }
        if (!isset($date['password'])) {
            $errors['password'] = "Поле Password обязательно для заполнения!";
        }
        return $errors;
    }

    // Выдача профиля
    public function profile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->userVerification($userId);
            require_once '../Views/profile_form.php';
        } else {
            header("Location: /login");
        }

    }

    // Изменения профиля
    public function getEditProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->userVerification($userId);
        } else {
            header("Location: /login");
            exit;
        }
        require_once '../Views/edit_profile_form.php';
    }

    public function editProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $errors = $this->ValidateProfile($_POST);

        if (empty($errors)) {

            $userId = $_SESSION['userId'];
            $name = $_POST['name'];
            $email = $_POST['email'];

            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->userVerification($userId);


            if ($user['name'] !== $name) {

                $userModel->updateNamedByID($name, $userId);
            }

            if ($user['email'] !== $email) {
                $userModel->updateEmailByID($email, $userId);
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
                require_once '../Model/User.php';
                $userModel = new User();
                $user = $userModel->getByEmail($email);
                $userId = $_SESSION['userId'];
                if ($user && $user['email'] === $email && $user['id'] !== $userId) {
                    $errors['email'] = "Этот Email уже зарегистрирован!";
                }
            }
        }
        return $errors;
    }
}

