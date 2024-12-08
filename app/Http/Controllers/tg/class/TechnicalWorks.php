<?php

namespace App\Http\Controllers\tg\class;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\TgApiService;
use App\Models\Settings;

class TechnicalWorks extends Controller
{
    public $tgApiService;

    public function __construct(TgApiService $tgApiService)
    {
        $this->tgApiService = $tgApiService;
    }


    public function __invoke($req, $user) {

        $settings = Settings::first();
        $technicalWorkMessage = $settings->telegram_technical_work_message;

        $this->tgApiService->sendTelegram(
            'sendMessage', 
            array(
                'chat_id' => $user->user_id,
                'text' => $technicalWorkMessage,
                'disable_web_page_preview' => false,
            )
        );
    }
}
