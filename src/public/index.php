<?php
$autoloadControllers = function (string $className)
{
    $path = "../Controllers/$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};
$autoloadCore = function (string $className)
{
    $path = "../Core/$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};
$autoloadModel = function (string $className)
{
    $path = "../Model/$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};
spl_autoload_register($autoloadCore);
spl_autoload_register($autoloadControllers);
spl_autoload_register($autoloadModel);
$app = new App();
$app->run();
