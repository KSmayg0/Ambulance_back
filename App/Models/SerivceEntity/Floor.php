<?php

namespace App\Models\SerivceEntity;

use App\Models\Model;
use PDO;
use PDOException;

class Floor extends Model {

    public function getAllFloors() {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM floors ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllFloorsWhereRoomsNotNull() {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT DISTINCT f.id, f.name  
                                FROM floors AS f
                                JOIN rooms AS r ON r.floor_id = f.id;
                           ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getFloorById($id): bool|array|null {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM floors WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function createFloor($floorName): void {
        try {
            $sql = "INSERT INTO `floors` 
                        (name)
                        VALUES (:floorName)";
            $stmt = $this->getDatabase()->getConnection()->prepare($sql);
            $stmt->bindValue(':floorName', $floorName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateFloorById($id, $floorName): void{
        try {
            $sql ="UPDATE floors
            SET name = :floorName WHERE id=:id";
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($sql);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('floorName', $floorName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteFloorById($id): bool {
        try {
            $stmt = $this->getDatabase()->getConnection()
                ->prepare("SELECT * FROM rooms WHERE floor_id=?");
            $stmt->execute([$id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                $stmt = $this->getDatabase()->getConnection()
                    ->prepare("DELETE FROM floors WHERE id=?");
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

    public function createNewRoomOnFloor(mixed $floor_id, mixed $newRoomName): void
    {
        $db = $this->getDatabase()->getConnection();
        try {
            $sql = "INSERT INTO `rooms` 
                        (floor_id, name)
                        VALUES (:floor_id, :name)";

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':floor_id', $floor_id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $newRoomName, PDO::PARAM_STR_CHAR);
            $stmt->execute();
            $db->commit();
        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

}