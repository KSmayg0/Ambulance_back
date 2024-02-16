<?php

namespace App\Views\Projects;

use App\Views\View;

class ProjectView extends View
{
    public function index(): void
    {
        $this->renderTemplate('main-project.html');
    }

    // Отобразить все заявки
    //role="User"
    public function requestsDetails($requests, $role): void
    {
//        debugArray($requests);
        $this->renderTemplate('requests-details.html', array('requests'=>$requests, 'role'=>$role));
    }

    public function requestsArhiveDetails($requests, $role): void
    {
        $this->renderTemplate('requests-archive.html', array('requests'=>$requests, 'role'=>$role));
    }

    public function modalRequestToEdit(array $request, array $select_status, array $select_priotiry, array $select_workers, $role): void
    {
        debugArray($request);
//        debugArray($select_status);
//        debugArrayThenDie($select_priotiry);
       // debugArray($isAdmin);
        $this->renderTemplate('requestModalToEdit.html', array('request'=>$request,'select_status'=>$select_status, 'select_priotiry'=>$select_priotiry,
            'select_workers' => $select_workers, 'role' => $role));
    }

    public function modalRequestToMoveToWork(array $request): void
    {
        $this->renderTemplate('requestModalToMoveFromArchive.html', array('request'=>$request));
    }

    public function apiSender($array) {
//        echo '123';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($array);
        die();
    }

}