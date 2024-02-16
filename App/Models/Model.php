<?php
namespace App\Models;

use App\Config\Config;
use App\Database\Database;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PDO;
use PDOException;

class Model {

    private Database $database;
    private Logger $logger;

    public function __construct(){
        $this->database = new Database();
        $this->logger = new Logger(Config::$LOG_MODEL_NAME);
        $debugHandler = new StreamHandler(Config::$LOG_MODEL_FILE_PATH, Level::Debug);
        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );
        $debugHandler->setFormatter($formatter);
        $this->logger->pushHandler($debugHandler);
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database {
        return $this->database;
    }

    protected function getAllWithNoParams(string $query): bool|array|null
    {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    protected function getAllWithNoParamsBot(string $query): bool|array|null
    {
        try{
            $stmt = $this->getDatabase()->getConnectionBot()
                ->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    protected function getById(string $query, int $id): bool|array|null
    {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    protected function getByIdFormBot(string $query, int $id): bool|array|null
    {
        try{
            $stmt = $this->getDatabase()->getConnectionBot()
                ->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    protected function getByStringParameter(string $query, string $param): bool|array|null
    {
        try{
            $stmt = $this->getDatabase()->getConnection()
                ->prepare($query);
            $stmt->execute([$param]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

}