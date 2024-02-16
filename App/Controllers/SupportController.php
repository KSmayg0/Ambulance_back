<?php

namespace App\Controllers;

use App\Views\Support\SupportView;

class SupportController extends Controller {
    public function main() {
        $render = new SupportView();
        $render->index();
    }
}