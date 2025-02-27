<?php

namespace Model;

class Order extends Model
{
    public function addOrder(string $name, string $phone, string $comment, string $address, int $userId):int|false
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO orders (name, phone, comment, address, user_Id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id");
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':comment' => $comment,
            ':address' => $address,
            ':user_id' => $userId
        ]);
        $data = $stmt->fetch();

        return $data['id'];
    }
    public function getAllByUserId(int $userId):array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }




}