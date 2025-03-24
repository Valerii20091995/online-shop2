<?php
namespace Model;
class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    protected static function getTableName():string
    {
        return "users";
    }
    public static function createObj(array $user):self|null
    {
        if (!$user) {
            return null;
        }
        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];
        return $obj;
    }

    public static function getByEmail(string $email):self|null
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("SELECT * FROM $tableName WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if (!$user) {
            return null;
        }
        return self::createObj($user);
    }
    public static function addUser(string $name, string $email, string $password):array|false
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("INSERT INTO $tableName (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $result = $stmt->fetch();
        return $result;
    }
    public static function userVerification(int $userId):self|null
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->query("SELECT * FROM $tableName WHERE id = " . $_SESSION['userId']);
        $user = $stmt->fetch();
        return self::createObj($user);
    }
    public static function updateEmailByID(string $email, int $userId)
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("UPDATE $tableName SET email = :email WHERE id = $userId");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result;
    }
    public static function updateNamedByID(string $name, int $userId)
    {
        $tableName = static::getTableName();
        $stmt = self::getPDO()->prepare("UPDATE $tableName SET name = :name WHERE id = $userId");
        $stmt->execute([':name' => $name]);
        $result = $stmt->fetch();
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }



}