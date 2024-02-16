<?php

namespace App\Controllers;

use App\Config\Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Level;

class Controller
{

    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(Config::$LOG_CONTROLLER_NAME);
        $debugHandler = new StreamHandler(Config::$LOG_CONTROLLER_FILE_PATH, Level::Debug);
        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );
        $debugHandler->setFormatter($formatter);
        $this->logger->pushHandler($debugHandler);
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

}