<?php


namespace App\Models\Projects;

use App\Models\Model;
class PriorityModel extends Model
{
    public function getAllPriority(): bool|array|null
    {
        $query = "SELECT priority.id as priority_id, priority.name as priority_name FROM priority";
        return $this->getAllWithNoParamsBot($query);
    }
}