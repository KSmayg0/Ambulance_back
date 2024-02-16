<?php

namespace App\Views\User;

use App\Views\View;

class UserProfileView extends View
{

    public function index($user): void
    {
        self::renderTemplate('user-profile.html', array("user" => $user));
    }

    public function vpn($userVpnData): void
    {
        self::renderTemplate('vpn.html', array("userData" => $userVpnData));
    }

    public function trello($userTrelloData): void
    {
        self::renderTemplate('trello.html', array("userData" => $userTrelloData));
    }

    public function email($userEmailData): void
    {
        self::renderTemplate('email.html', array("userData" => $userEmailData));
    }

    public function vpnTelegramSend(mixed $result): void
    {
        self::renderTemplate('vpn_send_tg.html', array("result" => $result));
    }

}