<?php

namespace App\Controllers;

class NotFoundController extends Controller {

    public function renderView() {
        header('HTTP/1.0 404 not found');
        echo $this->twig->render('404.html');
    }

}