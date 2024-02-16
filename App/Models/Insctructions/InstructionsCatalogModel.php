<?php

namespace App\Models\Insctructions;

use App\Models\Model;
use PDO;
use PDOException;

class InstructionsCatalogModel extends Model {


    public function getFullCatalog(): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM instructions_catalog");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getFullContentOfCatalog($id): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM insctructions_content WHERE instructions_catalog_id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getAllPhones(): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM floors");
            $stmt->execute();
            $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($phones as $key => $val) {
                $stmt2 = $this->getDatabase()
                    ->getConnection()
                    ->prepare("SELECT  rooms.id as room_id, rooms.name as room_name, ip.phone FROM rooms 
                                        LEFT JOIN internal_phones ip on rooms.id = ip.room_id
                                        WHERE floor_id=? ORDER BY rooms.name ASC");
                $stmt2->execute([$val['id']]);
                $rooms = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rooms as $rKey => $rVal) {
                    $stmt3 = $this->getDatabase()
                        ->getConnection()
                        ->prepare("
                            SELECT r.department_id, d.name
                            FROM rooms_departments AS r
                            LEFT JOIN departments AS d ON d.id = r.department_id
                            WHERE r.room_id = ?;");
                    $stmt3->execute([$rVal['room_id']]);
                    $departments = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                    $rooms[$rKey]['departments'] = $departments;

                    $stmt4= $this->getDatabase()->getConnection()
                        ->prepare("
                            SELECT ua.name, ua.lastname FROM rooms_users AS ru
                            JOIN users_autent AS ua ON ua.id = ru.user_id
                            WHERE ru.room_id = ?; 
                        ");
                    $stmt4->execute([$rVal['room_id']]);
                    $users = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                    $rooms[$rKey]['users'] = $users;

                    $stmt5= $this->getDatabase()->getConnection()
                        ->prepare("
                            SELECT COUNT(*) as users_count
                            FROM rooms_users AS ru 
                            WHERE ru.room_id = ? 
                            GROUP BY room_id; 
                        ");
                    $stmt5->execute([$rVal['room_id']]);
                    $users_count = $stmt5->fetchAll(PDO::FETCH_ASSOC);
                    $rooms[$rKey]['users_count'] = $users_count;
                }
                $phones[$key]['rooms'] = $rooms;
            }
            return $phones;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

}