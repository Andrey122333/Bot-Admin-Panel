<?php

namespace App\Http\Controllers\tg\class;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\Settings;
use App\Models\TgUser;
use App\Service\KeyboardService;
use App\Service\TgApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationForms extends Controller
{
    public $tgApiService;
    public $keyboardService;
    public $settings;
    public $user;

    public function __construct(TgApiService $tgApiService, KeyboardService $keyboardService)
    {
        $this->tgApiService = $tgApiService;
        $this->keyboardService = $keyboardService;
        $this->settings = Settings::first();
    }

    public function __invoke($req, $user)
    {
        $this->user = $user;
        if (isset($req['callback_query']['data']) and !is_numeric($req['callback_query']['data'])) {
            $data = explode(',', $req['callback_query']['data']);

            $application_form = ApplicationForm::where('number_user_form', '<', $data[1])
                ->orderByDesc('number_user_form')
                ->where('user_id', $this->user->id)
                ->limit($this->settings->telegram_applications_count)
                ->get();
        } else {
            $application_form = ApplicationForm::orderByDesc('number_user_form')
                ->where('user_id', $this->user->id)
                ->limit($this->settings->telegram_applications_count)
                ->get();
        }

        if (isset($application_form[0])) {
            $buttons = [
                [
                    'vertical' => 1,
                    'horizontal' => 1,
                    'name' => '<<',
                    'id' => isset($data) ? 'ApplicationForms,' . ($data[1] + $this->settings->telegram_applications_count) : 'ApplicationForms,' . $this->settings->telegram_applications_count,
                ],
                [
                    'vertical' => 1,
                    'horizontal' => 2,
                    'name' => '>>',
                    'id' => 'ApplicationForms,' . $application_form[count($application_form) - 1]->number_user_form,
                ],
            ];

            $buttons = collect($buttons);

            $text = "<b>Ваши заявки:</b> \n\n";
            foreach ($application_form as $form) {
                $text .= "<b>Название:</b> {$form->survey->name}\n"
                    . "<b>Номер:</b> {$form->number_user_form}\n"
                    . "<b>Статус:</b> {$form->status}\n\n";
            }

            $keyboard = $this->keyboardService->keyboardGeneration($buttons);

            $this->tgApiService->sendTelegram(
                'sendMessage',
                [
                    'chat_id' => $this->user->user_id,
                    'text' => $text,
                    'disable_web_page_preview' => false,
                    'reply_markup' => $keyboard,
                    'parse_mode' => 'html',
                ]
            );
        }
    }
}
