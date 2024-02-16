<?php

namespace App\Views\Manuals;

use App\Views\View;

class ManualView extends View
{
    public function index(): void
    {
        self::renderTemplate('main-manual.html');
    }
}