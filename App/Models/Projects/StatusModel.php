<?php


namespace App\Models\Projects;

use App\Models\Model;
class StatusModel extends Model
{
    public function getAllStatus(): bool|array|null
    {
        $query = "SELECT status.id as status_id, status.name as status_name FROM status WHERE id<4;";
        return $this->getAllWithNoParamsBot($query);
    }
}