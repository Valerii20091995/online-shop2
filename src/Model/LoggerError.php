<?php

namespace Model;
use Throwable;
class LoggerError extends Model
{
    protected function getTableName(): string
    {
        return "errors";
    }
    public function addError(Throwable $exception)
    {
        $stmt =$this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (error_message, file, line)
            VALUES (:error_message, :file, :line)"
        );
        $stmt->execute([
            ':error_message' => $exception->getMessage(),
            ':file' => $exception->getFile(),
            ':line' => $exception->getLine()
        ]);
    }


}