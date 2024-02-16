<?php


namespace App\Controllers;

use App\Models\Projects\RequestsModel;
use App\Models\Projects\PriorityModel;
use App\Models\Projects\StatusModel;
use App\Models\Projects\WorkerModel;

use App\Views\Projects\ProjectView;


class RequestsController extends Controller
{
    private ProjectView $projectView;
    private RequestsModel $requestsModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectView = new ProjectView();
        $this->requestsModel = new RequestsModel();
    }

    public function main(): void
    {
        $this->projectView->index();
    }

    #Отображение всех заявок, которые находятся не в архиве
    public function requestsDetails(): void
    {
        $arrayd = $this->requestsModel->getAllRequestsForPivotTable();
//        debugArray($arrayd);
        $this->projectView->requestsDetails($arrayd, $this->requestsModel->checkWorker());
    }

    #Взять пользователем заявку в работу
    public function getRequest(): void {
        $request = $_POST['dataToSend'];
        $this->requestsModel->getRequest($request);
        $this->requestsDetails();
    }

    #Отображение всех заявок, которые находятся в архиве
    public function requestsArhiveDetails(): void
    {
        $arrayd = $this->requestsModel->getAllArhiveRequestsForPivotTable();
        $this->projectView->requestsArhiveDetails($arrayd, $this->requestsModel->checkWorker());
    }

    #Чтение данных для изменения заявки в форме
    public function getRequestToEdit(): void {

        $request= $this->requestsModel->getRequestById($_POST['dataToSend']);
        $array_status = (new StatusModel())->getAllStatus();
        $array_priority = (new PriorityModel())->getAllPriority();
        $array_worker = (new WorkerModel())->getAllUsers();
        //debugArray($request);

        $this->projectView->modalRequestToEdit($request, $array_status, $array_priority, $array_worker, $this->requestsModel->checkWorker());
    }

    #Обновление заявки
    public function updateRequest() {
        $request = $_POST['dataToSend'];
        $this->requestsModel->updateRequest($request);
        $this->requestsDetails();
    }

    #Завершение и удаление заявки (перенос в архив)
    public function deleteRequest() {
        $this->requestsModel->deleteRequest($_POST['dataToSend']);
        $this->requestsDetails();
    }
}