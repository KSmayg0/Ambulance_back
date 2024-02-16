<?php

namespace App\Models\Management;

use App\Models\Model;
use PDO;
use PDOException;

class GroupModel extends Model {

    public function getAllGroups(): bool|array|null {
        try {
            $stmt = $this -> getDatabase() -> getConnection()
                -> prepare("SELECT * FROM `groups` ORDER BY name ASC");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e -> getMessage();
        }
        return null;
    }

    public function createGroup($groupName) {
        try {
            $sql = "INSERT INTO `groups`
                    (name)
                    VALUES (:groupName)";
            $stmt = $this -> getDatabase() -> getConnection() -> prepare($sql);
            $stmt -> bindValue(':groupName', $groupName, PDO::PARAM_STR_CHAR);
            $stmt -> execute();
        } catch (PDOException $e) {
            echo $e -> getMessage();
        }
    }

    public function updateGroupById($id, $groupName) {
        try {
            $sql = "UPDATE `groups`
            SET name = :groupName WHERE id = :id";
            $stmt = $this -> getDatabase() -> getConnection()
                -> prepare($sql);
            $stmt -> bindValue('id', $id, PDO::PARAM_INT);
            $stmt -> bindValue('groupName', $groupName, PDO::PARAM_STR_CHAR);
            $stmt -> execute();
        }catch (PDOException $e) {
            echo $e -> getMessage();
        }
    }

    public function deleteGroupById($id): bool {
        try {
            $stmt = $this -> getDatabase() -> getConnection()
                -> prepare("DELETE FROM `groups` WHERE id=?");
            $stmt -> execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo $e -> getMassage();
        }
        return false;
    }

}