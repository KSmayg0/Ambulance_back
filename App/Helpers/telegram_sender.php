<?php

function sendToItGroup($message) {

    $botToken="5811530617:AAHhVybfM3CCLGoVifgwokv-XcbgFH2fIDs";

    $website="https://api.telegram.org/bot".$botToken;
    $chatId='-183522230';  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**
    $params=[
        'chat_id'=>$chatId,
        'text'=>$message,
    ];
    $ch = curl_init($website . '/sendMessage');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
//    debugArray(json_decode($result, true));
    $res = json_decode($result, true);
    return $res['ok'];
}