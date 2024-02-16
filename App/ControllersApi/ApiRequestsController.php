<?php


namespace App\ControllersApi;


use App\Models\Model;
use App\Models\Projects\PriorityModel;
use App\Models\Projects\RequestsModel;
use App\Models\Projects\StatusModel;
use App\Models\Projects\WorkerModel;
use App\Views\Projects\ProjectView;
use App\Views\View;

class ApiRequestsController extends ControllerApi
{

    private ProjectView $projectView;
    private RequestsModel $requestsModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectView = new ProjectView();
        $this->requestsModel = new RequestsModel();
    }
    #Отображение всех заявок, которые находятся не в архиве
    public function requestsDetails(): void
    {
        try {
            $arrayd = $this->requestsModel->getAllRequestsForPivotTable();
            $arrayd['checkWorker'] = $this->requestsModel->checkWorkerApi();
            $this->projectView->apiSender($arrayd);
            if (isset($_GET['token_id'])) {
                $this->getLogger()->debug("Запрос через GET");
                $this->getLogger()->debug(print_r($_GET, true));
            } else if (isset($_POST['token_id'])) {
                $this->getLogger()->debug("Запрос через POST");
                $this->getLogger()->debug(print_r($_POST, true));
            } else {
                $this->getLogger()->debug("Контроллер запущен");
            }
            $this->getLogger()->debug(print_r($arrayd, true));
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }

    }
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// НАЧАЛО ФУНКЦИЙ
/*
*
* ЗАПРОСЫ НА ОТОБРАЖЕНИЕ ИНФОРМАЦИИ
*/
    #Отображение информации о всех пользователях
    public function userInfo(): void
    {
        try {
            $arrayd = $this->requestsModel->getUserInfoApi();
            $this->projectView->apiSender($arrayd);
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }
    #Отображение информации о всех адресах
    public function adressInfo(): void
    {
        try {
            $arrayd = $this->requestsModel->getAdressInfoApi();
            $this->projectView->apiSender($arrayd);
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }
    #Отображение информации о всех машинах
    public function carsInfo(): void
    {
      try {
          $arrayd = $this->requestsModel->getCarsInfoApi();
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    #Отображение информации о всех бригадах
    public function brigadeInfo(): void {
      try {
          $arrayd = $this->requestsModel->getBrigadesInfoApi();
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    #Отобразить все заявки, которые находятся вне архива
    public function requestsInfo(): void {
      try {
          $arrayd = $this->requestsModel->getRequestsInfoApi();
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    #Отобразить все заявки, которые находятся в архиве
    public function requestsArchiveInfo(): void {
      try {
          $arrayd = $this->requestsModel->getRequestsArchiveInfoApi();
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    /*
    *
    * ЗАПРОСЫ НА ДОБАВЛЕНИЕ ИНФОРМАЦИИ
    */
    #Добавить пользователя (администратор)
    public function addUser(): void {
      try {
          $json = file_get_contents('php://input');
        // var_dump($json);
        // die();
        // debugArrayThenDie($_POST);
         $data = json_decode($json,true);
         // var_dump($data);
         // die();
           // debugArrayThenDie($data['data']);
          $arrayd = $this->requestsModel->addUserApi($data);
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    #Добавить машину
    public function addCar(): void {
      try {

          $data = json_decode($_POST['dataToSend']);

          $arrayd = $this->requestsModel->addCarApi($data);
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    #Добавить бригаду
    public function addBrigade(): void {
      try {

          $data = json_decode($_POST['dataToSend']);

          $arrayd = $this->requestsModel->addBrigadeApi($data);
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
    /*
    *
    * ЗАПРОСЫ НА ВЫБОРКУ ИНФОРМАЦИИ С ПОМОЩЬЮ СЕЛЕКТ
    */
    #Отобразить все роли
    public function getRoles(): void
    {
        try {
            $arrayd = $this->requestsModel->getAllRoles();
            $this->projectView->apiSender($arrayd);
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }
    // КОНЕЦ ФУНКЦИЙ
/*
*
* ЗАПРОСЫ НА ИЗМЕНЕНИЕ ИНФОРМАЦИИ В БАЗЕ
*/
    public function changeUser(): void {
      try {
          $json = file_get_contents('php://input');
        // var_dump($json);
        // die();
        // debugArrayThenDie($_POST);
         $data = json_decode($json,true);
         // var_dump($data);
         // die();
           // debugArrayThenDie($data['data']);
          $arrayd = $this->requestsModel->changeUserApi($data);
          $this->projectView->apiSender($arrayd);
      } catch (\Exception $e) {
          $this->getLogger()->error($e->getMessage());
      }
    }
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    public function requestsArhiveDetails(): void
    {
        $arrayd = $this->requestsModel->getAllArhiveRequestsForPivotTable();
        $arrayd['checkWorker'] = $this->requestsModel->checkWorkerApi();
        $this->projectView->apiSender($arrayd);
    }

    public function getRequestToEdit(): void {

        if(isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
        } else if ($_POST['request_id']) {
            $request_id = $_POST['request_id'];
        } else {
            $request_id = null;
        }

        $request= $this->requestsModel->getRequestById($request_id);
        $array_status = (new StatusModel())->getAllStatus();
        $array_priority = (new PriorityModel())->getAllPriority();
        $array_worker = (new WorkerModel())->getAllUsers();
        //debugArray($request);
        $request['status_select'] = $array_status;
        $request['priority_select'] = $array_priority;
        $request['worker_select'] = $array_worker;
        $request['checkWorker'] = $this->requestsModel->checkWorkerApi();
        $this->projectView->apiSender($request);
    }

    public function updateRequest(): void
    {
//        $this->getLogger()->debug("_________________________");
//        $this->getLogger()->debug("UPDATE");
//        $this->getLogger()->debug("Запрос через POST");
//        $this->getLogger()->debug(print_r($_POST, true));
        $data = json_decode($_POST['data']);
//        $this->getLogger()->debug("_________________________");
//        $this->getLogger()->debug(print_r($data, true));

        $this->requestsModel->updateRequestApi($data);

    }
    public function deleteRequest(): void
    {
        $data = json_decode($_POST['data']);
        $this->requestsModel->deleteRequest($data);
    }

    public function getRequest(): void
    {
        $data = json_decode($_POST['data']);
        $this->requestsModel->getRequest(get_object_vars($data));
    }
 }
