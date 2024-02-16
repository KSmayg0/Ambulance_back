<?php

namespace App\Controllers;

use App\Models\Service\ServiceModel;
use App\Views\Service\ServiceView;

class ServiceController extends Controller
{

    private ServiceView $serviceView;
    private ServiceModel $serviceModel;



    public function __construct()
    {
        parent::__construct();
        $this->serviceModel = new ServiceModel();
        $this->serviceView = new ServiceView();
    }

    public function main(): void
    {
        $this->serviceView->index();
    }


    public function permsToChange(): void
    {
        $this->serviceView->permsToChange($this->serviceModel->getPermissionEntities());
    }
    public function mailToChange(): void
    {
        $this->serviceView->mailToChange($this->serviceModel->getMailEntities());
    }

    public function permsGipro(): void
    {
        $years = $this->serviceModel->getPermsYears($_POST['dataToSend']);
        $objects = $this->serviceModel->getProjectsForPermissions();
        $this->serviceView->permsGipro($objects, $years, $_POST['dataToSend']);
    }
    public function mailGipro(): void
    {
        $years = $this->serviceModel->getMailYears($_POST['dataToSend']);
        $arrayForFunc['year_id'] = $years[0]['id'];
        $arrayForFunc['mail_entity_id'] = $years[0]['mail_id'];
        $this->getLogger()->info("Запрос в базу");
        $mails = $this->serviceModel->getMailsByYearId($arrayForFunc);
        $this->serviceView->mailGipro($mails, $years, $_POST['dataToSend']);
    }

    public function permsGiproRegion(): void
    {
        $years = $this->serviceModel->getPermsYears($_POST['dataToSend']);
        $objects = $this->serviceModel->getProjectsForPermissions();
        $this->serviceView->permsGipro($objects, $years, $_POST['dataToSend']);
    }

    public function mailGiproRegion(): void
    {
        $years = $this->serviceModel->getMailYears($_POST['dataToSend']);
        $arrayForFunc['year_id'] = $years[0]['id'];
        $arrayForFunc['mail_entity_id'] = $years[0]['mail_id'];
        $mails = $this->serviceModel->getMailsByYearId($arrayForFunc);
        $this->serviceView->mailGipro($mails, $years, $_POST['dataToSend']);
    }

    public function permsChechnya(): void
    {
        $years = $this->serviceModel->getPermsYears($_POST['dataToSend']);
        $objects = $this->serviceModel->getProjectsForPermissions($_POST['dataToSend']);
        $this->serviceView->permsGipro($objects, $years, $_POST['dataToSend']);
    }

    public function getPermsByYearId(): void
    {
        $this->permissionsByYear(
            $_POST['dataToSend']['permission_entity_id'],
            $_POST['dataToSend']['year_id'],
            $_POST['dataToSend'],
            $_COOKIE['role_id']
        );
    }
    public function getMailsByYearId(): void
    {
        $this->mailsByYear(
            $_POST['dataToSend']['mail_entity_id'],
            $_POST['dataToSend']['year_id'],
            $_POST['dataToSend'],
            $_COOKIE['role_id']
        );
    }

    public function getPermById(): void
    {
        $this->serviceView->permissionModalEdit(
            $_POST['dataToSend']['permission_entity_id'],
            $_POST['dataToSend']['year_id'],
            $this->serviceModel->getPermissionById($_POST['dataToSend'])
        );
    }
    public function getMailById(): void
    {
        $this->serviceView->mailModalEdit(
            $_POST['dataToSend']['mail_entity_id'],
            $_POST['dataToSend']['year_id'],
            $this->serviceModel->getMailById($_POST['dataToSend'])
        );
    }

    public function createPermission(): void
    {
        $this->serviceModel->createPermission($_POST['dataToSend']);
        $years = $this->serviceModel->getPermsYears($_POST['dataToSend']['permission_entity_id']);
        if ($_POST['dataToSend']['permission_entity_id'] == 3) {
            $objects = $this->serviceModel->getProjectsForPermissions($_POST['dataToSend']['permission_entity_id']);
        } else {
            $objects = $this->serviceModel->getProjectsForPermissions();
        }
        $this->serviceView->permsGipro($objects, $years, $_POST['dataToSend']['permission_entity_id']);
    }
    public function createMail(): void
    {
        $this->serviceModel->createMail($_POST['dataToSend']);
        $years = $this->serviceModel->getMailYears($_POST['dataToSend']['mail_entity_id']);
        $arrayForFunc['year_id'] = $years[0]['id'];
        $arrayForFunc['mail_entity_id'] = $years[0]['mail_id'];
        $mails = $this->serviceModel->getMailsByYearId($arrayForFunc);
        $this->serviceView->mailGipro($mails, $years, $_POST['dataToSend']['mail_entity_id']);
    }

    public function createSpecialMail(): void {

        $this->serviceModel->createSpecialMail($_POST['dataToSend']);
        $years = $this->serviceModel->getMailYears($_POST['dataToSend']['mail_entity_id']);
        $arrayForFunc['year_id'] = $years[0]['id'];
        $arrayForFunc['mail_entity_id'] = $years[0]['mail_id'];
        $mails = $this->serviceModel->getMailsByYearId($arrayForFunc);
        $this->serviceView->mailGipro($mails, $years, $_POST['dataToSend']['mail_entity_id']);

    }

    public function editPermission(): void
    {
//        debugArrayThenDie($_POST);
        $this->serviceModel->setAnnulToPermission($_POST['dataToSend']['permission_entity_id'], $_POST['dataToSend']['permission_number_id']);
        $this->permissionsByYear(
            $_POST['dataToSend']['permission_entity_id'],
            $_POST['dataToSend']['year_id'],
            $_POST['dataToSend'],
            $_COOKIE['role_id']
        );
    }
    public function editMail(): void
    {
//        debugArrayThenDie($_POST);
        $this->serviceModel->setAnnulToMail($_POST['dataToSend']['mail_entity_id'], $_POST['dataToSend']['mail_number_id']);
        $this->mailsByYear(
            $_POST['dataToSend']['mail_entity_id'],
            $_POST['dataToSend']['year_id'],
            $_POST['dataToSend'],
            $_COOKIE['role_id']
        );
    }

    public function checkMailAlreadyExist(): void
    {
        $checkSpecialMailNumber = $this->serviceModel->checkMailAlreadyExist($_POST);
        header('Content-Type: application/json');
        echo json_encode($checkSpecialMailNumber);
    }

    private function permissionsByYear($permission_entity_id, $year_id, $dataArray, $user_role_id): void
    {
        $this->serviceView->permissionsByYear(
            $user_role_id,
            $permission_entity_id,
            $year_id,
            $this->serviceModel->getPermsByYearId($dataArray),
            $this->serviceModel->getYearByIdForPermsTable($year_id));
    }
    private function mailsByYear($mail_entity_id, $year_id, $dataArray, $user_role_id): void
    {
        $this->serviceView->mailsByYear(
            $user_role_id,
            $mail_entity_id,
            $year_id,
            $this->serviceModel->getMailsByYearId($dataArray));
    }
}