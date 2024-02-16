<?php

namespace App\Models\Service;

use App\Models\Model;
use PDO;
use PDOException;
class ServiceModel extends Model
{

    public function getProjectsForPermissions($permEntity = null): bool|array|null
    {
        if ($permEntity == 3) {
            $query = "SELECT obj.id as object_id, obj.name as object_name
                   FROM objects obj
                    WHERE arhive = 0 AND obj.id = 83;
                   ";
        } else {
            $query = "SELECT obj.id as object_id, obj.name as object_name
                   FROM objects obj
                    WHERE arhive = 0;
                   ";
        }

        return $this->getAllWithNoParams($query);
    }

    public function getProjectsForMails(): bool|array|null
    {
        $query = "SELECT obj.id as object_id, obj.name as object_name
               FROM objects obj
                WHERE arhive = 0;
               ";

        return $this->getAllWithNoParams($query);
    }

    public function getPermissionEntities(): bool|array|null
    {
        $query = "SELECT * FROM permissions_entities";
        return $this->getAllWithNoParams($query);
    }

    public function getMailEntities(): bool|array|null
    {
        $query = "SELECT * FROM mail_entities";
        return $this->getAllWithNoParams($query);
    }

    public function getYearByIdForPermsTable($year_id): int
    {
        $query = "SELECT year FROM permission_years WHERE id = ?";
        $yearArr = $this->getById($query, $year_id);
        return mb_substr($yearArr[0]['year'], -2);
    }

    public function getPermsByYearId($data): bool|array|null
    {
        $table = null;
        if ($data['permission_entity_id'] == 1) {
            $table = 'permissions_giprozdraw';
        } elseif ($data['permission_entity_id'] == 2) {
            $table = 'permissions_giprozdraw_region';
        } elseif ($data['permission_entity_id'] == 3) {
            $table = 'permissions_checnya';
        }
        $query = "SELECT 
                    pn.id, pn.date, pn.change_number,
                    pn.stage,
                    pn.section as section_name,
                    pn.annul,
                    o.name as object_name,
                    au.lastname
                    FROM ".$table." AS pn
                    LEFT JOIN objects o ON o.id = pn.object_id
                    LEFT JOIN users_autent au ON au.id = pn.user_id
                    WHERE pn.year_id = ?
                    ORDER BY pn.id DESC;";
        return $this->getById($query, $data['year_id']);
    }

    public function getMailsByYearId($data): bool|array|null
    {
        $table = null;
        if ($data['mail_entity_id'] == 1) {
            $table = 'mails_giprozdraw';
        } elseif ($data['mail_entity_id'] == 2) {
            $table = 'mails_giprozdraw_region';
        }

        $query = "SELECT 
                    m.id, 
                    m.number_id,
                    m.date,
                    m.description,
                    m.annul,
                    au.lastname
                    FROM ".$table." AS m
                    LEFT JOIN users_autent au ON au.id = m.user_id
                    WHERE m.year_id = ?
                    ORDER BY m.number_id + 0 DESC;";

        $mails = $this->getById($query, $data['year_id']);
        $this->getLogger()->debug(print_r($mails, true));
        return $mails;
    }

    public function getPermsYears($permission_id): bool|array|null
    {
        $query = "SELECT * FROM permission_years WHERE permission_id = ? order by year DESC ";
        return $this->getById($query, $permission_id);
    }

    public function getMailYears($mail_id): bool|array|null
    {
        $query = "SELECT * FROM mail_years WHERE mail_id = ? order by year DESC ";
        return $this->getById($query, $mail_id);
    }

    public function getPermissionById(mixed $data): bool|array|null
    {
        $table = null;
        if ($data['permission_entity_id'] == 1) {
            $table = 'permissions_giprozdraw';
        } elseif ($data['permission_entity_id'] == 2) {
            $table = 'permissions_giprozdraw_region';
        } elseif ($data['permission_entity_id'] == 3) {
            $table = 'permissions_checnya';
        }

        $query = "SELECT pg.id as permission_id, pg.change_number AS change_number,
                pg.stage, pg.section as section_name,
                o.name AS object_name
                FROM ".$table." AS pg
                JOIN objects AS o ON o.id = pg.object_id
                WHERE pg.id = ?";
        return $this->getById($query, $data['permission_id']);
    }

    public function getMailById(mixed $data): bool|array|null
    {
        $table = null;
        if ($data['mail_entity_id'] == 1) {
            $table = 'mails_giprozdraw';
        } elseif ($data['mail_entity_id'] == 2) {
            $table = 'mails_giprozdraw_region';
        }

        $query = "SELECT id as mail_id,
                number_id,
                description
                FROM ".$table."
                WHERE id = ?";
        return $this->getById($query, $data['mail_id']);
    }

    public function createPermission($data): void
    {
        $db = $this->getDatabase()->getConnection();
        try {

            $table = null;
            if ($data['permission_entity_id'] == 1) {
                $table = 'permissions_giprozdraw';
            } elseif ($data['permission_entity_id'] == 2) {
                $table = 'permissions_giprozdraw_region';
            } elseif ($data['permission_entity_id'] == 3) {
                $table = 'permissions_checnya';
            }

            $sql = 'INSERT INTO `'.$table.'`
                        (object_id, stage, section, user_id, year_id, change_number)
                        VALUES (:object_id, :stage, :section, :user_id, :year_id, :change_number)';

            $db->beginTransaction();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':object_id', $data['objectSelect'], PDO::PARAM_INT);
            $stmt->bindValue(':stage', $data['stageSelect'], PDO::PARAM_INT);
            $stmt->bindValue(':section', $data['section'], PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':year_id', $data['year_id'], PDO::PARAM_INT);
            $stmt->bindValue(':change_number', $data['permNumber'], PDO::PARAM_STR);
            $stmt->execute();

            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function createMail($data): void
    {
        $db = $this->getDatabase()->getConnection();
        try {

            $table = null;
            if ($data['mail_entity_id'] == 1) {
                $table = 'mails_giprozdraw';
            } elseif ($data['mail_entity_id'] == 2) {
                $table = 'mails_giprozdraw_region';
            }

            $sql = 'SELECT number_id FROM '.$table.' WHERE year_id = ? ORDER BY id DESC LIMIT 1;';

            $mailEntity = $this->getById($sql, $data['year_id']);
            if( empty($mailEntity)) {
                $correctId = 1;
            } else {
                $correctId = $this->getCorrectIdForMail($mailEntity[0]['number_id']) + 1;
            }

            $sql = 'INSERT INTO `'.$table.'`
                        (number_id, date, description, user_id, year_id)
                        VALUES (:number_id, :date, :description, :user_id, :year_id)';

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':number_id', $correctId, PDO::PARAM_STR);
            $stmt->bindValue(':date', date("Y-m-d"), PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['newMailDescription'], PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':year_id', $data['year_id'], PDO::PARAM_INT);
            $stmt->execute();

            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function setAnnulToPermission(int $permission_entity_id, int $permission_number_id): void
    {

        $table = null;
        if ($permission_entity_id == 1) {
            $table = 'permissions_giprozdraw';
        } elseif ($permission_entity_id == 2) {
            $table = 'permissions_giprozdraw_region';
        } elseif ($permission_entity_id == 3) {
            $table = 'permissions_checnya';
        }

        $db = $this->getDatabase()->getConnection();

        try {

            $sql ="UPDATE ".$table."
            SET 
                annul = 1
            WHERE id=:id";

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $permission_number_id, PDO::PARAM_INT);
            $stmt->execute();
            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function setAnnulToMail(int $mail_entity_id, int $mail_number_id): void
    {

        $table = null;
        if ($mail_entity_id == 1) {
            $table = 'mails_giprozdraw';
        } elseif ($mail_entity_id == 2) {
            $table = 'mails_giprozdraw_region';
        }

        $db = $this->getDatabase()->getConnection();

        try {

            $sql ="UPDATE ".$table."
            SET 
                annul = 1
            WHERE id=:id";

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $mail_number_id, PDO::PARAM_INT);
            $stmt->execute();
            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function createSpecialMail(mixed $data): void
    {

        $db = $this->getDatabase()->getConnection();
        try {

            $table = null;
            if ($data['mail_entity_id'] == 1) {
                $table = 'mails_giprozdraw';
            } elseif ($data['mail_entity_id'] == 2) {
                $table = 'mails_giprozdraw_region';
            }

            $sql = 'SELECT * FROM '.$table.' WHERE id = ?;';

            $mailEntity = $this->getById($sql, $data['mailId']);
            $correctId = $this->getCorrectIdForSpecialMail($mailEntity[0]['number_id']);

            $sql = 'INSERT INTO `'.$table.'`
                        (number_id, date, description, user_id, year_id)
                        VALUES (:number_id, :date, :description, :user_id, :year_id)';

            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':number_id', $correctId, PDO::PARAM_STR);
            $stmt->bindValue(':date', $mailEntity[0]['date'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['specialMailDescription'], PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':year_id', $data['year_id'], PDO::PARAM_INT);
            $stmt->execute();

            $db->commit();

        }catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function checkMailAlreadyExist($data): bool
    {
        $checkMailAlreadyExist = true;

        try {

            $table = null;

            if ($data['mail_entity_id'] == 1) {
                $table = 'mails_giprozdraw';
            } elseif ($data['mail_entity_id'] == 2) {
                $table = 'mails_giprozdraw_region';
            }

            $sql = 'SELECT * FROM '.$table.' WHERE id = ?;';

            $mailEntity = $this->getById($sql, $data['mail_id']);
            $correctId = $this->getCorrectIdForSpecialMail($mailEntity[0]['number_id']);

            $sql = 'SELECT * FROM '.$table.' WHERE number_id = ?;';

            $mailEntity = $this->getByStringParameter($sql, $correctId);

            if(empty($mailEntity)) {
                $checkMailAlreadyExist = false;
            }

        }catch (PDOException $e) {
            return $e->getMessage();
        }

        return $checkMailAlreadyExist;
    }

    private function getCorrectIdForMail(string $id): int {
        if(is_numeric($id)) {
            return $id + 0;
        }else {
            $idAfterExplode = explode('/', $id)[0];
            return $idAfterExplode + 0;
        }
    }

    private function getCorrectIdForSpecialMail(string $id): string {
        if(is_numeric($id)) {
            return $id."/1";
        }else {
            $idFist = explode('/', $id)[0];
            $idLast = explode('/', $id)[1];
            $idLastNumeric =  ($idLast + 0) + 1;
            return $idFist."/".$idLastNumeric;
        }
    }
}