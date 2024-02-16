<?php

namespace App\Views\Service;

use App\Views\View;

class ServiceView extends View
{

    public function index(): void
    {
        $this->renderTemplate('main-service.html');
    }

    public function permsToChange($permsEntities): void
    {
        $this->renderTemplate('permissions-to-change.html', array('permsEntities'=>$permsEntities));
    }
    public function mailToChange($mailEntities): void
    {
        $this->renderTemplate('mail-to-change.html', array('mailEntities'=>$mailEntities));
    }


    public function permsGipro($objects, $years, $permEntity): void
    {
        $this->renderTemplate('permission-details.html', array('objects'=>$objects, 'permEntity'=>$permEntity,'years'=>$years));
    }
    public function mailGipro($mails, $years, $mailEntity): void
    {
        $this->renderTemplate('mail-details.html', array('mails'=>$mails, 'mailEntity'=>$mailEntity,'years'=>$years));
        $this->getLogger()->debug("Шаблон mail-details.html отработал");
    }

    public function permissionsByYear($user_role_id, $permission_entity_id, $year_id,bool|array|null $permsArray, $year): void
    {
//        debugArray($permsArray);
        $this->renderTemplate('permissions-content.html', array('user_role_id'=>$user_role_id,'permission_entity_id'=> $permission_entity_id,'year_id'=>$year_id, 'permissions'=>$permsArray, 'year'=>$year));
    }
    public function mailsByYear($user_role_id, $mail_entity_id, $year_id,bool|array|null $mailsArray): void
    {
//        debugArray($permsArray);
        $this->renderTemplate('mail-content.html', array('user_role_id'=>$user_role_id,'mail_entity_id'=> $mail_entity_id,'year_id'=>$year_id, 'mails'=>$mailsArray));
    }

    public function permissionModalEdit($permission_entity_id, $year_id, $permission): void
    {
        $this->renderTemplate('permission-edit-modal.html', array('permission_entity_id'=> $permission_entity_id,'year_id'=> $year_id,'permission'=>$permission));
    }
    public function mailModalEdit($mail_entity_id, $year_id, $mail): void
    {
        $this->renderTemplate('mail-edit-modal.html', array('mail_entity_id'=> $mail_entity_id,'year_id'=> $year_id,'mail'=>$mail));
    }
}