<?php

namespace Model;
use Throwable;
class Logger extends Model
{
    protected static function getTableName(): string
    {
        return "errors";
    }
    public static function addLog(Throwable $exception)
    {
        $tableName = self::getTableName();
        $stmt =static::getPDO()->prepare(
            "INSERT INTO $tableName (error_message, file, line)
            VALUES (:error_message, :file, :line)"
        );
        $stmt->execute([
            ':error_message' => $exception->getMessage(),
            ':file' => $exception->getFile(),
            ':line' => $exception->getLine()
        ]);
    }


}