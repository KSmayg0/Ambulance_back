<?php

namespace App\Models\Users;

use App\DTO\User;
use App\Models\Model;
use PDO;
use PDOException;

class UserModel extends Model {

    public function getUserById($id): bool|array|null  {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare(
                "SELECT usrAuth.name, usrAuth.lastname, usrAuth.phone, usrAuth.active, d.name AS depName, r.name AS roomName, f.name AS floorName, ip.phone AS intPhone 
                        FROM users_autent AS usrAuth
                        LEFT JOIN departments_users AS du ON du.user_id = usrAuth.id
                        LEFT JOIN departments AS d ON d.id = du.department_id
                        LEFT JOIN rooms_users AS ru ON ru.user_id = usrAuth.id
                        LEFT JOIN rooms AS r ON r.id = ru.room_id
                        LEFT JOIN floors AS f ON f.id = r.floor_id
                        LEFT JOIN internal_phones AS ip ON ip.room_id = r.id 
                        WHERE usrAuth.id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getUserForManagementById($id): bool|array|null  {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare(
                "SELECT usrAuth.id AS user_id, usrAuth.login,usrAuth.password,usrAuth.name, usrAuth.lastname, usrAuth.phone, usrAuth.active, d.id AS department_id, r.id AS room_id, f.id AS floor_id
                        FROM users_autent AS usrAuth
                        LEFT JOIN departments_users AS du ON du.user_id = usrAuth.id
                        LEFT JOIN departments AS d ON d.id = du.department_id
                        LEFT JOIN rooms_users AS ru ON ru.user_id = usrAuth.id
                        LEFT JOIN rooms AS r ON r.id = ru.room_id
                        LEFT JOIN floors AS f ON f.id = r.floor_id
                        WHERE usrAuth.id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getUserVpnById($id): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM vpn_user WHERE user_id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getUserTrelloById($id): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM trello_user WHERE user_id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getUserEmailById($id): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM email_user WHERE user_id=?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function sendNotifyVpn() {
        $user = $this->getUserForNotify($_COOKIE['user_id']);
        $message = "Поступила заявка на VPN-подключение от сотрудника "
        .$user[0]['name']." ".$user[0]['lastname'].". "
        ."Моб. телефон сотрудника - ".$user[0]['phone'].", "
        ."Этаж - ".$user[0]['floor_name'].", "
        ."Блок - ".$user[0]['room_name'].", "
        ."Отдел - ".$user[0]['department_name'].", "
        ."Внутр. номер - ".$user[0]['in_phone'].".";

        return sendToItGroup($message);

    }

    public function getAllUsersForManagement(): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare(
                "SELECT usrAuth.id, usrAuth.login, usrAuth.password, usrAuth.name, usrAuth.lastname, usrAuth.phone,usrAuth.active,
                                d.name AS depName, r.name AS roomName, f.name AS floorName
                        FROM users_autent AS usrAuth
                        LEFT JOIN departments_users AS du ON du.user_id = usrAuth.id
                        LEFT JOIN departments AS d ON d.id = du.department_id
                        LEFT JOIN rooms_users AS ru ON ru.user_id = usrAuth.id
                        LEFT JOIN rooms AS r ON r.id = ru.room_id
                        LEFT JOIN floors AS f ON f.id = r.floor_id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function createUser($user): void {
//        debugArrayThenDie($user);
        $db = $this->getDatabase()->getConnection();
        try {
            $sql = "INSERT INTO `users_autent` 
                        (login, password, token_id, name, lastname, phone, active)
                        VALUES (:login, :password, 'qwe33%1', :name, :lastname, :phone, 1)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $user['login'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':password', $user['password'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':name', $user['name'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':lastname', $user['lastname'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':phone', $user['phone'], PDO::PARAM_STR_CHAR);
            $stmt->execute();

            $newUserId = $db->lastInsertId();

            $sql2 = "INSERT INTO `rooms_users` (room_id, user_id) VALUES (:room_id, :user_id)";
            $sql3 = "INSERT INTO `departments_users` (department_id, user_id) VALUES (:department_id, :user_id)";

            $db->beginTransaction();

            $stmt2 = $db->prepare($sql2);
            $stmt3 = $db->prepare($sql3);

            $stmt2->bindValue(':room_id', $user['room'], PDO::PARAM_INT);
            $stmt2->bindValue(':user_id', $newUserId, PDO::PARAM_INT);

            $stmt3->bindValue(':department_id', $user['department'], PDO::PARAM_INT);
            $stmt3->bindValue(':user_id', $newUserId, PDO::PARAM_INT);

            $stmt2->execute();
            $stmt3->execute();

            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function updateUserById($user): void {
//        debugArrayThenDie($user);
        $id = $user['user_id'];
        $db = $this->getDatabase()->getConnection();
        try {
            $sql ="UPDATE users_autent
            SET 
                login = :login, password = :password, 
                name = :name, lastname = :lastname, 
                phone = :phone, active = :active
            WHERE id=:id";

            $sql2 = "UPDATE rooms_users SET room_id = :room_id WHERE user_id=:user_id";
            $sql3 = "UPDATE departments_users SET department_id= :department_id WHERE user_id=:user_id";

            $db->beginTransaction();

            $stmt = $db->prepare($sql);
            $stmt2 = $db->prepare($sql2);
            $stmt3 = $db->prepare($sql3);

            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('login', $user['loginUpdate'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue('password', $user['passwordUpdate'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue('name', $user['nameUpdate'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue('lastname', $user['lastnameUpdate'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue('phone', $user['phoneUpdate'], PDO::PARAM_STR_CHAR);
            $stmt->bindValue('active', $user['active'], PDO::PARAM_INT);

            $stmt2->bindValue('room_id', $user['roomUpdate'], PDO::PARAM_INT);
            $stmt2->bindValue('user_id', $id, PDO::PARAM_INT);

            $stmt3->bindValue('department_id', $user['departmentUpdate'], PDO::PARAM_INT);
            $stmt3->bindValue('user_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            $stmt2->execute();
            $stmt3->execute();

            $db->commit();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function enableUser($id, $active): void {
        try {
            $sql ="UPDATE users_autent
            SET 
                active = :active
            WHERE id=:id";

            $stmt = $this->getDatabase()->getConnection()
                ->prepare($sql);

            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('active', $active, PDO::PARAM_INT);
            $stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteUserById($id) {
        try {
            $user = $this->getUserById($id);
            if ($user[0]['active'] == 0 ) {
                $stmt = $this->getDatabase()->getConnection()
                    ->prepare("DELETE FROM users_autent WHERE id=?");
                $stmt->execute([$id]);
                return true;
            }
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



    private function getAllUsers(): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare("SELECT * FROM users_autent");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }



    private function getUserForNotify($id): bool|array|null {
        try {
            $stmt = $this->getDatabase()->getConnection()->prepare(
                "SELECT ua.name, ua.lastname, ua.phone, r.name AS room_name, f.name AS floor_name, i_f.phone AS in_phone, d.name AS department_name FROM users_autent AS ua
                        LEFT JOIN rooms_users AS ru ON ru.user_id = ua.id
                        LEFT JOIN departments_users AS du ON du.user_id = ua.id
                        LEFT JOIN departments AS d ON d.id = du.department_id
                        JOIN rooms AS r ON r.id = ru.room_id
                        JOIN floors AS f ON f.id = r.floor_id
                        LEFT JOIN internal_phones AS i_f ON i_f.room_id = r.id
                        WHERE ua.id=?;");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }



}