<?php

namespace App\Controllers;

use App\Models\Users\UserModel;
use App\Views\User\UserProfileView;

class UserProfileController extends Controller {

    private UserModel $userModel;
    private UserProfileView $userView;

    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->userView = new UserProfileView();
    }

    public function main() {
        $user = $this->userModel->getUserById($_COOKIE['user_id']);
        $this->userView->index($user);
    }

    public function vpn() {
        $userVpnData = $this->userModel->getUserVpnById($_COOKIE['user_id']);
        $this->userView->vpn($userVpnData);
    }

    public function trello() {
        $userTrelloData = $this->userModel->getUserTrelloById($_COOKIE['user_id']);
        $this->userView->trello($userTrelloData);
    }

    public function email() {
        $userEmailData = $this->userModel->getUserEmailById($_COOKIE['user_id']);
        $this->userView->email($userEmailData);
    }

    public function sendNotifyVpn() {
        $result = $this->userModel->sendNotifyVpn();
        $this->userView->vpnTelegramSend($result);
    }
}