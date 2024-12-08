<?php

namespace App\Http\Controllers\tg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\TgApiService;
use App\Models\Settings;

class ForwardingControllers extends Controller
{
    public $tgApiService;

    public function __construct(TgApiService $tgApiService)
    {
        $this->tgApiService = $tgApiService;
    }


    public function __invoke($req, $from_id) {

        $settings = Settings::first();

        if (isset($req['message']['text'])) {
            $this->tgApiService->sendTelegram(
                'forwardMessage', 
                array(
                    'chat_id' => 568227873,
                    'from_chat_id' => $from_id,
                    'disable_notification' => 0,
                    'protect_content' => 0,
                    'message_id' => $req['message']['message_id']
                )
                );
        }

        
    }
}
