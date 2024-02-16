<?php

namespace App\Database;

use App\Config\Config;
use PDO;
use PDOException;

class Database {

    public function __construct() {
    }

    public function getConnection() {
        try {
            $dbConn = new PDO('mysql:host='.Config::$DB_SERVER.';dbname='.Config::$DB_NAME, Config::$DB_USERNAME, Config::$DB_PASSWORD);
            $dbConn->exec("set names utf8");
            return $dbConn;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getConnectionBot() {
        try {
            $dbConn = new PDO('mysql:host='.Config::$DB_SERVER.';dbname='.Config::$DB_NAME_BOT, Config::$DB_USERNAME, Config::$DB_PASSWORD);
            $dbConn->exec("set names utf8");
            return $dbConn;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

}