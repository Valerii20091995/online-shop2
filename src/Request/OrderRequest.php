<?php

namespace Request;

class OrderRequest extends Request
{
    public function getName(): string
    {
        return $this->data['name'];
    }
    public function getPhone(): string
    {
        return $this->data['phone'];
    }
    public function getComment(): string
    {
        return $this->data['comment'];
    }

    public function getAddress(): string
    {
        return $this->data['address'];
    }
    public function validate(): array
    {
        $errors = [];
        if (isset($this->data['name'])) {
            if (strlen($this->data['name']) < 3) {
                $errors['name'] = 'Имя пользователя должно быть больше 3 символов';
            } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_\-\.]+$/u', $this->data['name'])) {
                $errors['name'] = "Имя пользователя может содержать только буквы, цифры, символы '_', '-', '.'";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($this->data['address'])) {
            if (!preg_match('/^[\d\s\w\.,-]+$/u', $this->data['address'])) {
                $errors['address'] = "Адрес содержит недопустимые символы";
            }elseif (!preg_match('/[а-яА-ЯёЁ]+\s+\d+/', $this->data['address'])) {
                $errors['address'] = "Укажите номер дома и улицу";
            }
        } else {
            $errors['address'] = "Address должен быть заполнен";
        }
        if (isset($this->data['phone'])) {
            $cleanedPhone = preg_replace('/\D/', '', $this->data['phone']);
            if(strlen($cleanedPhone) < 11) {
                $errors['phone'] = 'Номер телефона не может быть меньше 11 символов';
            }elseif (!preg_match('/^\+\d+$/', $this->data['phone'])) {
                $errors['phone'] = "+ и цифры после";
            }
        } else {
            $errors['phone'] = "Поле Phone должно быть заполнено";
        }
        return $errors;
    }

}