<?php


namespace App\Models\Projects;

use App\Models\Model;
use PDO;
use PDOException;
use function Sodium\add;

class RequestsModel extends Model
{

#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// НАЧАЛО ФУНКЦИЙ
    /*
    *
    * ЗАПРОСЫ НА ОТОБРАЖЕНИЕ ИНФОРМАЦИИ
    */
    #Отобразить всех сотрудников
    public function getUserInfoApi(): bool|array|null
    {

        $query = "SELECT Em.id, Em.name, Em.surname, Em.patronymic, Em.email,
        Em.telephone, Em.role_id, R.role_name, Em.password
        FROM Employee AS Em
        INNER JOIN Role AS R
        ON Em.role_id=R.id
        ORDER BY Em.id ASC;";

        return $this->createRequestArray($query);

    }
    #Отобразить все адреса
    public function getAdressInfoApi(): bool|array|null {
      $query = "SELECT *
      FROM Adress;";

      return $this->createRequestArray($query);
    }
    #Отобразить все машины
    public function getCarsInfoApi(): bool|array|null
    {
      $query = "SELECT *
      FROM Car;";

      return $this->createRequestArray($query);

    }
    #Отобразить все бригады
    public function getBrigadesInfoApi(): bool|array|null
    {
      $query = "SELECT *
      FROM Brigade;";

      return $this->createRequestArray($query);
    }
    #Отобразить все заявки, которые находятся вне архива
    public function getRequestsInfoApi(): bool|array|null
    {
      $query = "SELECT *
      FROM Request
      WHERE archive=0 ;";

      return $this->createRequestArray($query);
    }
    #Отобразить все заявки, которые находятся в архиве
    public function getRequestsArchiveInfoApi(): bool|array|null
    {
      $query = "SELECT *
      FROM Request
      WHERE archive=1 ;";

      return $this->createRequestArray($query);
    }
    /*
    *
    * ЗАПРОСЫ НА ДОБАВЛЕНИЕ ИНФОРМАЦИИ
    */
    #Добавить пользователя (администратор)
    public function addUserApi($user)
    {

      // $user = get_object_vars($user);

// debugArrayThenDie($user['name']);
      $db = $this->getDatabase()->getConnection();
      try {
          $sql = "INSERT INTO `Employee`
                      (name,surname,patronymic,email,telephone,role_id,password)
                      VALUES (:name, :surname, :patronymic, :email, :telephone, :role_id, :password) ;";
          $stmt = $db->prepare($sql);
          $stmt->bindValue(':name', $user['name'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':surname', $user['surname'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':patronymic', $user['patronymic'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':email', $user['email'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':telephone', $user['telephone'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':role_id', $user['role_id'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':password', $user['password'], PDO::PARAM_STR_CHAR);
          $stmt->execute();

          $db->beginTransaction();

          $db->commit();

      }catch (PDOException $e) {
          $db->rollBack();
          echo $e->getMessage();
      }

    }
    #Добавить машину
    public function addCarApi($car)
    {
      $car = get_object_vars($car);
      $db = $this->getDatabase()->getConnection();
      try {
          $sql = "INSERT INTO `Car`
                      (car_number,car_region)
                      VALUES (:car_number, :car_region) ;";

          $stmt = $db->prepare($sql);
          $stmt->bindValue(':car_number', $car['car_number'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':car_region', $car['car_region'], PDO::PARAM_STR_CHAR);
          $stmt->execute();

          $db->beginTransaction();

          $db->commit();

      }catch (PDOException $e) {
          $db->rollBack();
          echo $e->getMessage();
      }
    }
    #Добавить бригаду
    public function addBrigadeApi($brigade)
    {
      $brigade = get_object_vars($brigade);
      $db = $this->getDatabase()->getConnection();
      try {
          $sql = "INSERT INTO `Brigade`
                      (driver_id,paramedic_id,doctor_id)
                      VALUES (:driver_id, :paramedic_id, :doctor_id) ;";

          $stmt = $db->prepare($sql);
          $stmt->bindValue(':driver_id', $brigade['driver_id'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':paramedic_id', $brigade['paramedic_id'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':doctor_id', $brigade['doctor_id'], PDO::PARAM_STR_CHAR);
          $stmt->execute();

          $db->beginTransaction();

          $db->commit();

      }catch (PDOException $e) {
          $db->rollBack();
          echo $e->getMessage();
      }
    }
    /*
    *
    * ЗАПРОСЫ НА ВЫБОРУ ИНФОРМАЦИИ С ПОМОЩЬЮ СЕЛЕКТ
    */
    #Вывести информацию о ролях
    public function getAllRoles(): bool|array|null
    {

        $query = "SELECT *
        FROM Role;";

        return $this->createRequestArray($query);

    }
/*
*
* ЗАПРОСЫ НА ИЗМЕНЕНИЕ ИНФОРМАЦИИ
*/
    public function changeUserApi($user) {
      // var_dump($user);
      // die();
      $db = $this->getDatabase()->getConnection();
      try {
          $sql = "UPDATE `Employee`
                      SET name = :name, surname = :surname, patronymic = :patronymic, email = :email, telephone = :telephone, role_id = :role_id, password = :password
                    WHERE id = :id ;";
          $stmt = $db->prepare($sql);
          $stmt->bindValue(':name', $user['name'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':surname', $user['surname'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':patronymic', $user['patronymic'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':email', $user['email'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':telephone', $user['telephone'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':role_id', $user['role_id'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':password', $user['password'], PDO::PARAM_STR_CHAR);
          $stmt->bindValue(':id', $user['id'], PDO::PARAM_STR_CHAR);
          $stmt->execute();

          $db->beginTransaction();

          $db->commit();

      }catch (PDOException $e) {
          $db->rollBack();
          echo $e->getMessage();
      }
    }
    /*
    *
    * ЗАПРОСЫ НА УДАЛЕНИЕ ИНФОРМАЦИИ
    */
    public function deleteUserApi($user) {
      $db = $this->getDatabase()->getConnection();
      try{
        $sql = "DELETE FROM `Employee` WHERE id=:id ;";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $user['id'], PDO::PARAM_STR_CHAR);
        $stmt->execute();

        $db->beginTransaction();

        $db->commit();

      } catch(PDOException $e) {
        $db->rollBack();
        echo $e->getMessage();
      }
    }
    // КОНЕЦ ФУНКЦИЙ
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    #Отобразить все заявки из бд, которые находятся все архива
    public function getAllRequestsForPivotTable(): bool|array|null
    {

        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id,
       req.chat_id as chat_id, req.date as date_create, s.name as status_name, p.name as priority_name,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    LEFT JOIN status s on s.id = req.status_id
    LEFT JOIN priority p on p.id = req.priority_id
        WHERE arhive = 0
    ORDER BY date_create ASC, priority_name DESC;";

        return $this->createRequestArray($query);

    }

    #Отобразить все заявки из бд, которые находятся в архиве
    public function getAllArhiveRequestsForPivotTable(): bool|array|null
    {

        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id,
       req.chat_id as chat_id, req.date as date_create, s.name as status_name, p.name as priority_name,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    LEFT JOIN status s on s.id = req.status_id
    LEFT JOIN priority p on p.id = req.priority_id
        WHERE arhive = 1
    ORDER BY date_create ASC, priority_name DESC;";

        return $this->createRequestArray($query);
    }

    #Взять id заявки для обработки в форме
    public function getRequestById(int $request_id): bool|array
    {

        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id,
       req.chat_id as chat_id, req.date as date_create, req.status_id, req.priority_id,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    WHERE req.id = ?;";


        return $this->createRequestArrayUser($query, $request_id);

    }

    #Изменить заявку
    public function updateRequest($request): void
    {
        $db = $this->getDatabase()->getConnectionBot();
        try {
            $query = "UPDATE requests
            SET
            status_id=:status_id, date_expired=:date_expired, priority_id=:priority_id,worker_id=:worker_id, arhive=:arhive
        WHERE id=:id;";

            $db->beginTransaction();

            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $request['id'], PDO::PARAM_INT);
            $stmt->bindValue(':status_id', $request['status_id'], PDO::PARAM_INT);
            $stmt->bindValue(':date_expired', $this->checkForNull($request['date_expired']), PDO::PARAM_STR);
            $stmt->bindValue(':priority_id', $request['priority_id'], PDO::PARAM_INT);
            $stmt->bindValue(':worker_id', $this->checkForNull($request['worker_id']), PDO::PARAM_INT);
            $stmt->bindValue(':arhive', 0, PDO::PARAM_INT);
//            $this->extracted($stmt, $request);
            $stmt->execute();

            $db->commit();

        } catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public function updateRequestApi($request) {
        $request = get_object_vars($request);
        $db = $this->getDatabase()->getConnectionBot();
        try {
            $query = "UPDATE requests
            SET
            status_id=:status_id, date_expired=:date_expired, priority_id=:priority_id,worker_id=:worker_id, arhive=:arhive
        WHERE id=:id;";

            $db->beginTransaction();

            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $request['request_id'], PDO::PARAM_INT);
            $stmt->bindValue(':status_id', $request['status_id'], PDO::PARAM_INT);
            $stmt->bindValue(':date_expired', $this->checkForNull($request['date_expired']), PDO::PARAM_STR);
            $stmt->bindValue(':priority_id', $request['priority_id'], PDO::PARAM_INT);
            $stmt->bindValue(':worker_id', $this->checkForNull($request['worker_id']), PDO::PARAM_INT);
            $stmt->bindValue(':arhive', 0, PDO::PARAM_INT);
//            $this->extracted($stmt, $request);
            $stmt->execute();
            $db->commit();

            $this->getLogger()->info("Заявка обновлена");
        } catch (PDOException $e) {
            $db->rollBack();
            $this->getLogger()->error($e->getMessage());
        }
    }

    #Закрыть заявку после проверки
    public function deleteRequest(int $request_id): void
    {

        $db = $this->getDatabase()->getConnectionBot();
        try {

            $query = "UPDATE requests SET status_id = :status_id, arhive = :arhive WHERE id = :id;";

            $db->beginTransaction();

            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $request_id, PDO::PARAM_INT);
            $stmt->bindValue(':status_id', 4, PDO::PARAM_INT);
            $stmt->bindValue(':arhive', 1, PDO::PARAM_INT);

            $stmt->execute();

            $db->commit();

        } catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    #Взять заявку пользователем
    public function getRequest($request): void
    {
        $db = $this->getDatabase()->getConnectionBot();
        try {
            $query = "UPDATE requests
            SET
            status_id=:status_id, worker_id=:worker_id
            WHERE id=:id;";

            $db->beginTransaction();

            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $request['request_id'], PDO::PARAM_INT);
            $stmt->bindValue(':status_id', 2, PDO::PARAM_INT);
            $stmt->bindValue(':worker_id', $request['worker_id'], PDO::PARAM_INT);
//            $this->extracted($stmt, $request);
            $stmt->execute();

            $db->commit();

        } catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    #Проверка сотрудника на роль администратора
    public function checkWorker(): string
    {
        switch ($_COOKIE['role_id']) {
            case "1":
                return "Admin";
                break;
            case "2":
                return "User";
                break;
            case "3":
                return "Guest";
                break;
        }
    }

    public function checkWorkerApi(): string
    {
        if (isset($_GET['role_id'])) {
            switch ($_GET['role_id']) {
                case "1":
                    return "Admin";
                    break;
                case "2":
                    return "User";
                    break;
                case "3":
                    return "Guest";
                    break;
            }
        } else if (isset($_POST['role_id'])) {
            switch ($_POST['role_id']) {
                case "1":
                    return "Admin";
                    break;
                case "2":
                    return "User";
                    break;
                case "3":
                    return "Guest";
                    break;
            }
        }

    }

    #Проверка на NULL переменной
    private function checkForNull($element)
    {
        if (empty($element)) {
            return NULL;
        } else {
            return $element;
        }
    }

    #Взять id пользователя
    private function getUserId(): array
    {
        $query = "SELECT user_id FROM ForBot;";
        $user_id = $this->getAllWithNoParamsBot($query);
        $user_id_str = array();
        foreach ($user_id as $user) {
            foreach ($user as $id) {
                array_push($user_id_str, $id);
            }
        }
        return $user_id_str;
    }

    #Взять имя пользователя
    private function getUserName(): array
    {
        #Взять имена и фамилии пользователей, оставивших заявки
        $user_id = implode("','", $this->getUserId());
        //Берём имена пользователей из базы
        $query1 = "SELECT CONCAT(lastname, ' ', name) AS name FROM users_autent WHERE id IN ('$user_id')";
        $user_name = $this->getAllWithNoParams($query1);
        $user_name1 = array();
        foreach ($user_name as $user) {
            foreach ($user as $arr) {
                array_push($user_name1, $arr);
            }
        }
        return $user_name1;
    }

    private function getWorkerId(): array
    {
        //Берём wroker_id из таблицы с сотрудниками
        $query = "SELECT id FROM users_autent WHERE (role_id=1) OR (role_id=2);";
        $worker_id = $this->getAllWithNoParams($query);
        $worker_id_str = array();
        foreach ($worker_id as $worker) {
            foreach ($worker as $id) {
                array_push($worker_id_str, $id);
            }
        }
        return $worker_id_str;
    }

    private function getWorkerName(): array
    {
        #Взять имена и фамилии исполнителей заявки
        $worker_id = implode("','", $this->getWorkerId());
        //Берём имена сотрудников из базы
        $query1 = "SELECT CONCAT(lastname, ' ', name) AS name FROM users_autent WHERE (id IN ('$worker_id')) AND ((role_id=1) OR (role_id=2));";
        $worker_name = $this->getAllWithNoParams($query1);
        $worker_name1 = array();
        foreach ($worker_name as $worker) {
            foreach ($worker as $arr) {
                array_push($worker_name1, $arr);
            }
        }
        return $worker_name1;
    }
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    # Костыльный промежуточный метод для получения данных запроса
    private function createRequestArray(string $query): array
    {
        $requests = $this->getAllWithNoParamsBot($query);

        // $name_id = array_combine($this->getUserId(), $this->getUserName());

        // $worker = array_combine($this->getWorkerId(), $this->getWorkerName());
        // $copyarray = $requests;
        // foreach ($copyarray as $key => $value) {
        //     $requests[$key]['user_name_surname'] = $name_id[$value['user_id']];
        // }
        // foreach ($copyarray as $key => $value) {
        //     if ($requests[$key]['worker_id'] != null) {
        //         $requests[$key]['worker_name_surname'] = $worker[$value['worker_id']];
        //     }
        // }
        return $requests;
    }
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    private function createRequestArrayUser(string $query, $request_id): array
    {
        $requests = $this->getByIdFormBot($query, $request_id);

        $name_id = array_combine($this->getUserId(), $this->getUserName());

        $copyarray = $requests;
        foreach ($copyarray as $key => $value) {
            $requests[$key]['user_name_surname'] = $name_id[$value['user_id']];
        }
        return $requests;
    }
}
