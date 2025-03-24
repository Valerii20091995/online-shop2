<?php

namespace Request;

use Model\User;

class EditProfileRequest extends Request
{
    private User $userModel;
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->userModel = new User();
    }
    public function getName(): string
    {
        return $this->data['name'];
    }
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function Validate(): array
    {
        $errors = [];
        if (isset($this->data['name'])) {
            $name = $this->data['name'];
            if (strlen($name) < 3) {
                $errors['name'] = "Имя не может содержать меньше 3 символов";
            }
        }
        if (isset($this->data['email'])) {
            $email = $this->data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный email";
            } else {
                $user = User::getByEmail($email);
                $userId = $_SESSION['userId'];
                if ($user && $user->getEmail() === $email && $user->getId() !== $userId) {
                    $errors['email'] = "Этот Email уже зарегистрирован!";
                }
            }
        }
        return $errors;
    }

}