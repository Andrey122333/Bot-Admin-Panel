<?php
namespace App\Service;

use App\Models\Settings;

class TgApiService
{

    public $token;

    public function __construct()
    {
        $this->token = Settings::first()->token;
    }

    public function sendTelegram($method, $response)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

        // Добавляем опцию keepalive
        curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);
        curl_setopt($ch, CURLOPT_TCP_KEEPIDLE, 120);
        curl_setopt($ch, CURLOPT_TCP_KEEPINTVL, 60);

        $result = curl_exec($ch);

        curl_close($ch);


        return $result;
    }
}
