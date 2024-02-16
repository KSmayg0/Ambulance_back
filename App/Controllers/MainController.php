<?php
namespace App\Controllers;

use App\Models\Users\UserModel;
use App\Views\Main\MainView;

class MainController extends Controller {

    public function index(): void
    {

        $userModel = new UserModel();

        $user = $userModel->getUserById($_COOKIE['user_id']);
        $render = new MainView();

        $render->index($user);
    }

}