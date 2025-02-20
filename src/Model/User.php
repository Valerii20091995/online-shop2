<?php

class User extends Model
{
    public function getByEmail(string $email):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result;
    }
    public function addUser(string $name, string $email, string $password):array|false
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $result = $stmt->fetch();
        return $result;
    }
    public function userVerification(int $userId):array|false
    {
        $stmt = $this->pdo->query('SELECT * FROM users WHERE id = ' . $_SESSION['userId']);
        $result = $stmt->fetch();
        return $result;
    }
    public function updateEmailByID(string $email, int $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email = :email WHERE id = $userId");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result;
    }
    public function updateNamedByID(string $name, int $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET name = :name WHERE id = $userId");
        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch();
        return $result;
    }


}