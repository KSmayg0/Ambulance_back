<?php

namespace App\Views;

use App\Config\Config;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class View
{

    public FilesystemLoader $loader;
    public Environment $twig;

    private Logger $logger;
    public function __construct()
    {
        $this->loader = new FilesystemLoader(array('resources/templates',
            'resources/templates/carcass',
            'resources/templates/carcass/select',
            'resources/templates/instructions',
            'resources/templates/projects',
            'resources/templates/manuals',
            'resources/templates/user',
            'resources/templates/management',
            'resources/templates/support',
            'resources/templates/service',
            'resources/templates/main'
        ));
        $this->twig = new Environment($this->loader, array(
            'auto_reload' => true
        ));

        $this->twig->addFilter(new TwigFilter('html_entity_decode', 'html_entity_decode'));

        $this->logger = new Logger(Config::$LOG_VIEW_NAME);
        $debugHandler = new StreamHandler(Config::$LOG_VIEW_PATH, Level::Debug);
        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );
        $debugHandler->setFormatter($formatter);
        $this->logger->pushHandler($debugHandler);
    }

    protected function renderTemplate($activeView, $array=array()): void
    {
        try {
            echo $this->twig->render($activeView, $array);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e->getMessage();
        }
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }
}