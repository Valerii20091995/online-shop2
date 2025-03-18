<?php

namespace Service\Logger;

class LoggerFileErrorService implements LoggerInterface
{
    public function Logs(\Throwable $exception)
    {
        $errorMessage = sprintf("[%s] Ошибка: %s в файле %s на строке %d\n",
            date("Y-m-d H:i:s"),
            //"[%s] Ошибка: %s в файле %s на строке %d\n" : это [%s] — это место для временной метки, которая будет подставлена в формате Y-m-d H:i:s (например, 2025-03-18 10:30:15).
            //Ошибка: %s — в это место будет подставлено сообщение об ошибке, полученное методом getMessage() объекта исключения.
            //в файле %s — подставляется путь к файлу, где произошла ошибка, с помощью метода getFile() объекта исключения.
            //на строке %d — подставляется номер строки, на которой произошла ошибка, с помощью метода getLine() объекта исключения.
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        $logPath = "../Storage/Log/errors.txt";
        // error_log(информация об ошибке, 3 это значит что ошибка будет записана в файл из 3-го параметра, путь до файла с ошибками)
        error_log($errorMessage, 3, $logPath);
    }

}