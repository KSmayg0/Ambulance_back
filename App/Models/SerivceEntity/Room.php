<?php

namespace App\Models\SerivceEntity;

use App\Models\Model;
use PDO;
use PDOException;

class Room extends Model {
    public function getAllRooms() {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM rooms ORDER BY name ASC ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getRoomById($id): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM rooms WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getAllRoomsByFloorId(mixed $floor_id): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM rooms WHERE floor_id=? ORDER BY name ASC
");
            $stmt->execute([$floor_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function createRoom($roomName): void {
        try {
            $sql = "INSERT INTO `rooms` 
                        (name)
                        VALUES (:roomName)";
            $stmt = $this->getDatabase()->getConnection()->prepare($sql);
            $stmt->bindValue(':roomName', $roomName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateRoomById($id, $roomName): void{
        try {
            $sql ="UPDATE rooms
            SET name = :roomName WHERE id=:id";
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($sql);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('roomName', $roomName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteRoomById($id): bool {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM rooms_users WHERE room_id=?");
            $stmt->execute([$id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                $stmt = $this->getDatabase()->getConnection()
                    ->prepare("DELETE FROM rooms WHERE id=?");
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

    public function addDepartmentToRoom(int $room_id, int $department_id): void
    {
        $db = $this->getDatabase()->getConnection();
        try {
            $sql = "INSERT INTO `rooms_departments` 
                        (room_id, department_id)
                        VALUES (:room_id, :department_id)";

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':room_id', $room_id, PDO::PARAM_INT);
            $stmt->bindValue(':department_id', $department_id, PDO::PARAM_STR_CHAR);
            $stmt->execute();
            $db->commit();
        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function deleteDepartmentFromRoom(int $room_id, int $department_id): void
    {
        $db = $this->getDatabase()->getConnection();
        try {
            $sql = "DELETE FROM `rooms_departments` 
       WHERE room_id = :room_id AND department_id = :department_id";

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':room_id', $room_id, PDO::PARAM_INT);
            $stmt->bindValue(':department_id', $department_id, PDO::PARAM_STR_CHAR);
            $stmt->execute();
            $db->commit();
        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }
}