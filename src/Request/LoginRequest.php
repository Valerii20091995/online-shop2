<?php

namespace Request;

class LoginRequest extends Request
{
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): string
    {
        return $this->data['password'];
    }
    public function Validate(): array
    {
        $errors = [];
        // проверка наличия переменных
        if (!isset($this->data['email'])) {
            $errors['email'] = "Поле Username обязательно для заполнения!";
        }
        if (!isset($this->data['password'])) {
            $errors['password'] = "Поле Password обязательно для заполнения!";
        }
        return $errors;
    }

}