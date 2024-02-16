<?php

namespace App\Views\Support;

use App\Views\View;

class SupportView extends View
{
    public function index(): void
    {
        self::renderTemplate('support.html');
    }
}