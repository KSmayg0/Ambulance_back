<?php

namespace App\Models\Management;

use App\Models\Model;
use PDO;
use PDOException;

class DepartmentModel extends Model {

    public function getAllDepartments(): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM departments ORDER BY name ASC ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
            return null;
    }

    public function getDepartmentById($id): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM departments WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getAllDepartmentsByFloorId($room_id): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT d.id, d.name  FROM departments AS d
                                JOIN rooms_departments AS rd ON rd.department_id = d.id
                                WHERE rd.room_id =? ORDER BY d.name ASC");
            $stmt->execute([$room_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function createDepartment($departmentName) {
        try {
            $sql = "INSERT INTO `departments` 
                        (name)
                        VALUES (:departmentName)";
            $stmt = $this->getDatabase()->getConnection()->prepare($sql);
            $stmt->bindValue(':departmentName', $departmentName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateDepartmentById($id, $departmentName) {
        try {
            $sql ="UPDATE departments
            SET name = :departmentName WHERE id=:id";
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($sql);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('departmentName', $departmentName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteDepartmentById($id): bool {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM departments_users WHERE department_id=?");
            $stmt->execute([$id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                $stmt = $this->getDatabase()->getConnection()
                    ->prepare("DELETE FROM departments WHERE id=?");
                $stmt->execute([$id]);
                return true;
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }

}