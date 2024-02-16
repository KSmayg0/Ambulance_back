<?php

namespace App\Views\Management;

use App\Views\View;

class ManagementView extends View
{

    public function userManagement($users, $floors): void
    {
        self::renderTemplate('user_management.html', array('users' => $users, 'floors' => $floors));
    }

    public function updateModalLoad($user, $floors): void
    {
        self::renderTemplate('user_update_modal.html', array('user' => $user, 'floors' => $floors));
    }

    public function groupManagement($groups): void
    {
        self::renderTemplate('group_management.html', array('groups' => $groups));
    }

    public function departmentManagement($departments): void
    {
        self::renderTemplate('department_management.html', array('departments' => $departments));
    }

    public function getRoomsForSelect($rooms): void
    {
        self::renderTemplate('users_rooms_select.html', array('rooms' => $rooms));
    }

    public function getRoomsForSelectUpdate($rooms, $floor_change): void
    {
        self::renderTemplate('users_rooms_update_select.html', array('rooms' => $rooms, 'floor_change' => $floor_change));
    }

    public function getDepartmentsForSelect(bool|array|null $departments): void
    {
        self::renderTemplate('users_department_select.html', array('departments' => $departments));
    }

    public function getDepartmentsForSelectUpdate(bool|array|null $departments): void
    {
        self::renderTemplate('users_department_update_select.html', array('departments' => $departments));
    }

    public function getOfficeStructure(bool|array|null $phones): void
    {
        self::renderTemplate('phones.html', array('phones' => $phones, 'edit' => 1));
    }

    public function roomModalShow($room_id,$departmentsInRoom, $departments): void
    {
        self::renderTemplate('room_update_modal.html', array('room_id' =>$room_id,'departmentsInRoom' => $departmentsInRoom,'departments' => $departments));
    }

}