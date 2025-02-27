<?php
$autoload = function (string $className) {
    $path = str_replace("\\", "/", $className);
    $path =$path . '.php';
    $path ='./../' . $path;
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};
spl_autoload_register($autoload);
$app = new Core\App();
$app->run();
