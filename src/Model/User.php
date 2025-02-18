<?php
require_once '../Model/DataBase.php';
class User
{
    private PDO $PDO;
    public function __construct()
    {
        $this->PDO = DataBase::getPDO();
    }
    public function getByEmail(string $email):array|false
    {
        $stmt = $this->PDO->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result;
    }
    public function addUser(string $name, string $email, string $password):array|false
    {
        $stmt = $this->PDO->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $result = $stmt->fetch();
        return $result;
    }
    public function userVerification(int $userId):array|false
    {
        $stmt = $this->PDO->query('SELECT * FROM users WHERE id = ' . $_SESSION['userId']);
        $result = $stmt->fetch();
        return $result;
    }
    public function updateEmailByID(string $email, int $userId)
    {
        $stmt = $this->PDO->prepare("UPDATE users SET email = :email WHERE id = $userId");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result;
    }
    public function updateNamedByID(string $name, int $userId)
    {
        $stmt = $this->PDO->prepare("UPDATE users SET name = :name WHERE id = $userId");
        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch();
        return $result;
    }


}