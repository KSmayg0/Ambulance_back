<?php


namespace App\Models\Projects;

use App\Models\Model;
class WorkerModel extends Model
{
    public function getAllUsers(): bool|array|null
    {
        $query = "SELECT ua.id as user_id, ua.name as user_name, ua.lastname as user_surname FROM users_autent ua WHERE role_id = 1";
        return $this->getAllWithNoParams($query);
    }
}