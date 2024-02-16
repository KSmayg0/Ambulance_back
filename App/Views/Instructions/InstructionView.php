<?php

namespace App\Views\Instructions;

use App\Views\View;

class InstructionView extends View
{
    public function index(): void
    {
        self::renderTemplate('main-instruction.html');
    }

    public function instDetails($catalog): void
    {
        self::renderTemplate('instructions.html', array("catalog" => $catalog));
    }

    public function instContentOfCatalog($content): void
    {
        self::renderTemplate('instructions_content.html', array("content" => $content));
    }

    public function phoneDetails($phones): void
    {
        self::renderTemplate('phones.html', array("phones" => $phones));
    }
}