<?php

namespace App\Views\Main;

use App\Config\Config;
use App\Views\View;

class MainView extends View
{

    public function index($user): void
    {
        self::renderTemplate('main.html', array("user" => $user, 'to_gall_ref' => Config::$TO_GALL_REF));
    }
}