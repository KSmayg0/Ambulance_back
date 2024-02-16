<?php

namespace App\Router;
use App\Config\Config;
use App\Controllers\NotFoundController;
use JetBrains\PhpStorm\NoReturn;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class Router {

    /** массив $routes это двумерный массив вида
     * [0]
     *   'uri'=>/,
     *   'move'=>MainController@index
     * [1]
     *    'uri'=>/instructions,
     *   'move'=>InstructionsController@main
     */
    static array $routes = [];

    static public function generateRoutes(): void {
        //добавяем в массив $routes URI и контроллер который должен отработать
        self::addRoute('/', 'MainController@index');
        self::addRoute('/user-profile', 'UserProfileController@main');


        self::addRoute('/requests', 'RequestsController@main');
        self::addRoute('/requests/requestsDetails', 'RequestsController@requestsDetails');
        self::addRoute('/requests/requestsArhiveDetails', 'RequestsController@requestsArhiveDetails');
        self::addRoute('/requests/getRequestToEdit', 'RequestsController@getRequestToEdit');
        self::addRoute('/requests/updateRequest', 'RequestsController@updateRequest');
        self::addRoute('/requests/deleteRequest', 'RequestsController@deleteRequest');
        self::addRoute('/requests/getRequest', 'RequestsController@getRequest');
        self::addRoute('/requests-details', 'RequestsController@requestsDetails');
        self::addRoute('/requests/createExcel', 'ExcelController@createExcel');
        self::addRoute('/requests/createExcelNonArhive', 'ExcelController@createExcelNonArhive');
        self::addRoute('/requests/createExcelArhive', 'ExcelController@createExcelArhive');
        #POST
        self::addRoute('/api/requests/requestsDetails', 'ApiRequestsController@requestsDetails');
        self::addRoute('/api/requests/requestsArhiveDetails', 'ApiRequestsController@requestsArhiveDetails');
        self::addRoute('/api/requests/getRequestToEdit', 'ApiRequestsController@getRequestToEdit');
        self::addRoute('/api/requests/updateRequest', 'ApiRequestsController@updateRequest');
        self::addRoute('/api/requests/deleteRequest', 'ApiRequestsController@deleteRequest');
        self::addRoute('/api/requests/getRequest', 'ApiRequestsController@getRequest');
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        # Нужный нам маршрут для POST запроса
        /*
        Запросы на запрос информации
        */
        ### Показать всех пользователей
        self::addRoute('/api/user', 'ApiRequestsController@userInfo');
        ### Показать все машины
        self::addRoute('/api/cars', 'ApiRequestsController@carsInfo');
        ### Показать все бригады
        self::addRoute('/api/brigade', 'ApiRequestsController@brigadeInfo');
        ### Показать все заявки вне архива
        self::addRoute('/api/requests', 'ApiRequestsController@requestsInfo');
        ### Показать все заявки в архиве
        self::addRoute('/api/requestsarchive', 'ApiRequestsController@requestsArchiveInfo');
        ### Показать профиль пользователя
        ### Показать все адреса
          self::addRoute('/api/adress', 'ApiRequestsController@adressInfo');
        /*
         Запросы на добавление информации
        */
        ### Добавить пользователя (администратором)
        self::addRoute('/api/adduser', 'ApiRequestsController@addUser');
        ### Добавить машину
        self::addRoute('/api/addcar', 'ApiRequestsController@addCar');
        ### Добавить бригаду
        self::addRoute('/api/addbrigade', 'ApiRequestsController@addBrigade');
        ### Добавить заявку
        /*
         Запросы на изменение информации
        */
        ### Изменить данные пользователя (администратором)
        self::addRoute('/api/changeuser', 'ApiRequestsController@changeUser');
        ### Изменить данные пользователем (свой профиль)
        ### Изменить данные машины
        ### Изменить данные бригады
        ### Изменить данные заявки
        ### Убрать заявку в архив
        /*
         Запросы на удаление информации
        */
        ### Удалить пользователя
        ### Удалить машину
        ### Удалить бригаду
        /*
        Селект запросы
        */
        ### Запросить все роли
        self::addRoute('/api/roles', 'ApiRequestsController@getRoles');
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        #GET
        self::addRoute('/api/requests/requestsArhiveDetails?token_api=123&role_id=1', 'ApiRequestsController@requestsArhiveDetails');
        self::addRoute('/api/requests/requestsDetails?token_api=123&role_id=1', 'ApiRequestsController@requestsDetails');
        if (isset($_GET['request_id'])) {
            self::addRoute('/api/requests/getRequestToEdit?token_api=123&role_id=1&request_id='.$_GET['request_id'], 'ApiRequestsController@getRequestToEdit');
        }

        //        self::addRoute('/instructions', 'InstructionsController@main');
//        self::addRoute('/instructions-content', 'InstructionsController@loadContent');
//        self::addRoute('/instructions-details', 'InstructionsController@instDetails');
//        self::addRoute('/phones-list', 'InstructionsController@phoneDetails');


//        self::addRoute('/projects/moveObjectToArchive', 'ProjectsController@moveObjectToArchive');
//        self::addRoute('/projects/moveFromArchiveToWork', 'ProjectsController@moveFromArchiveToWork');
//        self::addRoute('/projects/moveObjectToWork', 'ProjectsController@moveObjectToWork');
//        self::addRoute('/projects/getProjectsFromArchive', 'ProjectsController@getProjectsFromArchive');
//        self::addRoute('/projects/checkObject', 'ProjectsController@checkObject');
//        self::addRoute('/projects-details', 'ProjectsController@projectsDetails');
//        self::addRoute('/projects-details', 'ProjectsController@projectsRequestsDetails');

//        self::addRoute('/vpn', 'UserProfileController@vpn');
//        self::addRoute('/trello', 'UserProfileController@trello');
//        self::addRoute('/email', 'UserProfileController@email');
//        self::addRoute('/sendVpn', 'UserProfileController@sendNotifyVpn');
//        self::addRoute('/support', 'SupportController@main');
//        self::addRoute('/manuals', 'ManualsController@main');
//        self::addRoute('/user_management', 'ManagementController@userManagement');
//        self::addRoute('/user_management/create', 'ManagementController@userManagementCreate');
//        self::addRoute('/user_management/update', 'ManagementController@userManagementUpdate');
//        self::addRoute('/user_management/updateModalLoad', 'ManagementController@updateModalLoad');
//        self::addRoute('/user_management/delete', 'ManagementController@userManagementDelete');
//        self::addRoute('/group_management', 'ManagementController@groupManagement');
//        self::addRoute('/group_management/create', 'ManagementController@groupManagementCreate');
//        self::addRoute('/group_management/update', 'ManagementController@groupManagementUpdate');
//        self::addRoute('/group_management/delete', 'ManagementController@groupManagementDelete');
//        self::addRoute('/department_management', 'ManagementController@departmentManagement');
//        self::addRoute('/department_management/create', 'ManagementController@departmentManagementCreate');
//        self::addRoute('/department_management/update', 'ManagementController@departmentManagementUpdate');
//        self::addRoute('/department_management/delete', 'ManagementController@departmentManagementDelete');
//        self::addRoute('/getroomsforselect', 'ManagementController@getRoomsForSelect');
//        self::addRoute('/getroomsforselectUpdate', 'ManagementController@getRoomsForSelectUpdate');
//        self::addRoute('/getdepartmentsforselect', 'ManagementController@getDepartmentsForSelect');
//        self::addRoute('/getdepartmentsforselectUpdate', 'ManagementController@getDepartmentsForSelectUpdate');
//        self::addRoute('/office', 'ManagementController@getOfficeStructure');
//        self::addRoute('/office/createNewRoom', 'ManagementController@createNewRoom');
//        self::addRoute('/office/deleteRoom', 'ManagementController@deleteRoom');
//        self::addRoute('/office/editRoomModal', 'ManagementController@editRoomModal');
//        self::addRoute('/office/addDepartmentToRoom', 'ManagementController@addDepartmentToRoom');
//        self::addRoute('/office/deleteDepartmentFromRoom', 'ManagementController@deleteDepartmentFromRoom');
//        self::addRoute('/services', 'ServiceController@main');
//        self::addRoute('/services/permissions-to-changes', 'ServiceController@permsToChange');
//        self::addRoute('/services/mail-to-change', 'ServiceController@mailToChange');
//        self::addRoute('/services/perms/gipro', 'ServiceController@permsGipro');
//        self::addRoute('/services/perms/getPermsByYearId', 'ServiceController@getPermsByYearId');
//        self::addRoute('/services/perms/getPermById', 'ServiceController@getPermById');
//        self::addRoute('/services/perms/createPermission', 'ServiceController@createPermission');
//        self::addRoute('/services/perms/editPermission', 'ServiceController@editPermission');
//        self::addRoute('/services/perms/giproregion', 'ServiceController@permsGiproRegion');
//        self::addRoute('/services/perms/chechnya', 'ServiceController@permsChechnya');
//        self::addRoute('/services/mail/gipro', 'ServiceController@mailGipro');
//        self::addRoute('/services/mail/giproregion', 'ServiceController@mailGiproRegion');
//        self::addRoute('/services/mail/getMailsByYearId', 'ServiceController@getMailsByYearId');
//        self::addRoute('/services/mail/createMail', 'ServiceController@createMail');
//        self::addRoute('/services/mail/createSpecialMail', 'ServiceController@createSpecialMail');
//        self::addRoute('/services/mail/getMailById', 'ServiceController@getMailById');
//        self::addRoute('/services/mail/editMail', 'ServiceController@editMail');
//        self::addRoute('/services/mail/checkMailAlreadyExist', 'ServiceController@checkMailAlreadyExist');
    }

    static public function get($uri): void {
        //проверяем - авторизирован ли пользователь

        $logger = new Logger(Config::$LOG_ROUTER_NAME);
        $debugHandler = new StreamHandler(Config::$LOG_ROUTER_FILE_PATH, Level::Debug);
        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );
        $debugHandler->setFormatter($formatter);
        $logger->pushHandler($debugHandler);

        if (!self::authorization($logger)) {

            /**если все хорошо разбираем пришедший запрос.
             * Для этого обращаемся к суперглобальной переменной $_SERVER
             */
            //достаем метод $_GET или $_POST
            $method = $_SERVER['REQUEST_METHOD'];
            //Достаем URI запроса
            $uri = $_SERVER['REQUEST_URI'];
            //сверяем, что такой uri существует в нашем массиве $routes
            $checkArray = self::searchInRoutes($uri);

            //если такой uri существует - то check будет true
            if ($checkArray[0]['check']) {
                //проверяем запрос. пришел он на api или нет

                $uriToApiCheck = str_replace('/', '', $checkArray[0]['uri']);

                if (preg_match('/^api/', $uriToApiCheck)) {
                    $controllerPath = 'ControllersApi';
                } else {
                    $controllerPath = 'Controllers';
                }

                //тут пока костыли
                if ($method == 'GET') {
                    //разбиваем пааметр move в массиве. Это к примеру - MainController@index
                    //тут получим  $controller = MainController
                    $controller = explode('@', $checkArray[0]['move'])[0];
                    //тут получим $method = index
                    $method = explode('@', $checkArray[0]['move'])[1];
                    //определяем namespace контроллера, что бы не плодить кучу импортов сверху файла
                    $controller = '\App\\'.$controllerPath.'\\'.$controller;
                    //тут строка имеет вид - $objectController = new MainController, то есть - просто создаем объект
                    $objectController = new $controller;
                    //вызываем у контрорреа его метод
                    //к примеру MainController->index();
                    $objectController->$method();
                } else if ($method == 'POST') {
                    //разбиваем пааметр move в массиве. Это к примеру - MainController@index
                    //тут получим  $controller = MainController
                    $controller = explode('@', $checkArray[0]['move'])[0];
                    //тут получим $method = index
                    $method = explode('@', $checkArray[0]['move'])[1];
                    //определяем namespace контроллера, что бы не плодить кучу импортов сверху файла
                    $controller = '\App\\'.$controllerPath.'\\'.$controller;
                    //тут строка имеет вид - $objectController = new MainController, то есть - просто создаем объект
                    $objectController = new $controller;
                    //вызываем у контрорреа его метод
                    //к примеру MainController->index();
                    $objectController->$method();
                }
            } else {
                //если uri не существует в массиве $routes показываем 404
                $objectController = new NotFoundController();
                $objectController ->renderView();
            }
        } else {
            //если пользователь не авторизован
            //header('Refresh: 0; url='.Config::$AUTH_REF);
            header('Refresh: 0; url='.Config::$AUTH_REF);
        }
    }

    static public function addRoute($uri, $move): void {
        //тут ничего сложного. Просто кладен в массив данные
        self::$routes[] = array(
            'uri'=>$uri,
            'move'=>$move
        );
    }

    static function searchInRoutes($uri): array {
        //создаем одномерный массив $checkArray с единственным значением 'check'=>false
        $checkArray = array();
            $checkArray[0] = array(
                'check'=>false,
            );

        //Обходим массив с маршрутами $routes
        foreach (self::$routes as $key => $route) {
            //сравниваем uri в $routes и uri который пришел параметром в функцию
            //если есть совпадение - добавляем в массив данные
            if ($route['uri'] == $uri) {
                $checkArray[0] = array(
                    'check'=>true,
                    'uri'=>$uri,
                    'move'=>$route['move']
                );

                return $checkArray;
            }
        }

        return $checkArray;
    }

    static function authorization(Logger $logger): bool {

        $logger->debug("Router authorization method started");

        //авторизация максимальна проста и тупа - проверяем наличие кук у клиента
        if (isset($_COOKIE['token_id']) && $_COOKIE['token_id'] == 'qwe33%1') {
            return true;
        } else if (isset($_GET['token_api']) && $_GET['token_api']=='123') {
            $logger->debug("Router get params");
            $logger->debug(print_r($_GET, true));
            $logger->debug("authorization method return - true");
            $logger->debug("Router authorization method ending");
            return true;
        } else if (isset($_POST['token_api']) && $_POST['token_api']=='123') {
            $logger->debug("Router post params");
            $logger->debug(print_r($_POST, true));
            $logger->debug("authorization method return - true");
            $logger->debug("Router authorization method ending");
            return true;
        }
        $logger->debug("authorization method return - false");
        $logger->debug("Router authorization method ending with fail!");
        return false;
    }


    //Метод для дебага массива $routes
   #[NoReturn] static function debugUri(): void {
        echo '<br> Список ури';
        echo '<pre>';
        var_dump(Router::$routes);
        echo '</pre>';
        die();
    }

}
