<?php

namespace App\Controllers;

use App\Views\Manuals\ManualView;

class ManualsController extends Controller {
    public function main() {
        $render = new ManualView();
        $render->index();
    }
}