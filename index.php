<?php
// error_reporting(0);
//Включаем все ошибки
ini_set('display_errors',1);
error_reporting(E_ALL);

//Функция регистрации всех наших классов приложения.
spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR , $class);
    include  __DIR__ . DIRECTORY_SEPARATOR.$class.'.php';
});

//Набор функций для этапа разработки. В проде - отключить
include(__DIR__ . '/App/Helpers/dev.php');
//Набор функций для ТГ-бота
include(__DIR__ . '/App/Helpers/telegram_sender.php');

//Подключаем зависимости. Twig и все такое
require_once 'vendor/autoload.php';
//Импортируем Роутер
use App\Router\Router;
//Вызываем фукнцию генерации маршрутов
Router::generateRoutes();
//Обрабатываем запрос пришедший на сервер
debugArrayThenDie(Router::get($_SERVER));