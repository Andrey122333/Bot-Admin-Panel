<?php

namespace App\Http\Controllers\tg;

use App\Http\Controllers\Controller;
use App\Models\ApplicationForm;
use App\Models\ApplicationFormAnswer;
use App\Models\Buttons;
use App\Models\GeoPositions;
use App\Models\Settings;
use App\Models\Survey;
use App\Models\TgEmail;
use App\Models\TgUser;
use App\Models\Variable;
use App\Service\KeyboardService;
use App\Service\TgApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public $tgApiService;
    public $keyboardService;
    public $user;
    public $nestingDown;
    public $settings;

    public function __construct(TgApiService $tgApiService, KeyboardService $keyboardService)
    {
        $this->tgApiService = $tgApiService;
        $this->keyboardService = $keyboardService;
        $this->settings = Settings::first();
    }

    public function stop()
    {
    }

    public function main(Request $req)
    {
        if (!isset($req['message']['text']) && !isset($req['callback_query'])) {
            return;
        }

        // Store the request in a file

        // Get the user information
        $from = isset($req['message']['from']) ? $req['message']['from'] : $req['callback_query']['from'];
        $this->user = TgUser::firstOrCreate(
            ['user_id' => $from['id']],
            [
                'first_name' => $from['first_name'] ?? '',
                'last_name' => $from['last_name'] ?? '',
                'language_code' => $from['language_code'] ?? '',
                'is_premium' => $from['is_premium'] ?? false,
                'action' => 'nesting',
                'username' => $from['username'] ?? ''
            ]
        );

        if (isset($from['username']) && $this->user->username != $from['username']) {
            $this->user->username = $from['username'];
            $this->user->save();
        }

        // Answer callback queries
        if (isset($req['callback_query'])) {
            $this->tgApiService->sendTelegram(
                'answerCallbackQuery',
                ['callback_query_id' => $req['callback_query']['id']]
            );
        }

        // Handle banned users
        if (strtotime($this->user->ban_time) > strtotime(date('Y-m-d H:i:s'))) {
            $this->handleBannedUser();
            return;
        }

        // Handle survey
        if ($this->user->action == 'survey') {
            $this->survey($req);
            return;
        }

        // Handle nesting
        if ($this->user->action == 'nesting') {
            $this->handleNesting($req);
            return;
        }
    }

    private function handleBannedUser()
    {
        $buttons = array(
            array(
                'vertical' => 1,
                'horizontal' => 1,
                'name' => $this->settings->telegram_locked_account_button_text
            )
        );

        $buttons = collect($buttons);
        $keyboard = $this->keyboardService->keyboardButtonGeneration($buttons);

        if ($this->user->ban_id == 0) {
            $ban_description = $this->settings->telegram_default_locked_account_reason;
        } else {
            $ban_description = $this->user->ban->description;
        }

        $this->tgApiService->sendTelegram(
            'sendMessage',
            array(
                'chat_id' => $this->user->user_id,
                'text' => $ban_description,
                'disable_web_page_preview' => false,
                'parse_mode' => 'html',
                'reply_markup' => $keyboard,
            )
        );
    }

    private function handleNesting(Request $req)
    {
        $callback_query = $req['callback_query'] ?? null;
        $message_text = $req['message']['text'] ?? null;

        if ($message_text == '/start') {
            $this->start($req);
        } elseif ($callback_query) {
            $data = $callback_query['data'];
            if (is_numeric($data)) {
                $button = Buttons::firstWhere('id', $data);
            } else {
                $data = explode(',', $data);
                $button = Buttons::firstWhere('id', $data[1]);
                app('App\Http\Controllers\tg\class\\' . $data[0])->__invoke($req, $this->user);
            }
        } else if ($message_text) {
            $name = $this->inverseVaribleFunc($message_text);
            $button = Buttons::where('keyboard_button', 1)->where('name', $name)->first();
        }

        if (!empty($button)) {
            $this->nestingDown = $button['nesting_down'];

            switch ($button['type']) {
                case "Geo":
                    $this->newGeo($button);
                    break;
                case "Survey":
                    $this->newSurvey($button);
                    break;
                case "Nesting":
                    if ($button["class"] != '') {
                        app("App\\Http\\Controllers\\tg\\class\\{$button['class']}")->__invoke($req, $this->user);
                    }
                    if ($button['nesting_down']) {
                        $this->keyboardCreate($callback_query ? 0 : 1, $button['nesting_down'], $callback_query ? 0 : 1);
                    }
                    if ($button['keyboard']) {
                        $this->keyboardCreate($callback_query ? 1 : 0, $button['keyboard'], $callback_query ? 1 : 0);
                    }
                    break;
            }
        }
    }


    public function newSurvey($button)
    {
        $survey = Survey::where('name', $this->nestingDown)->first();

        if (isset($survey->questions)) {

            $questions = $survey->questions;

            $number_user_form = ApplicationForm::select("number_user_form")->orderByDesc("number_user_form")->firstWhere('user_id', $this->user->id);

            if (isset($number_user_form->number_user_form))
                $number_user_form = $number_user_form->number_user_form + 1;
            else
                $number_user_form = 0;


            ApplicationForm::create([
                'number_user_form' => $number_user_form,
                'user_id' => $this->user->id,
                'survey_id' => $survey->id,
                'status' => 'progress',
            ]);
            $this->user->action = "survey";
            $this->user->nesting = $button['nesting'];

            $this->user->save();

            $buttons = [
                [
                    'vertical' => 1,
                    'horizontal' => 1,
                    'name' => 'Отмена',
                    'id' => 'cancel',
                ]
            ];

            $keyboard = $this->keyboardService->keyboardGeneration(collect($buttons));

            $this->tgApiService->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $this->user->user_id,
                    'text' => $survey->questions[0]->name,
                    'disable_web_page_preview' => false,
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard,
                )
            );
        }
    }


    public function survey($req)
    {

        $application_form = ApplicationForm::orderByDesc("number_user_form")->firstWhere('user_id', $this->user->id);
        $survey = $application_form->survey;
        $questions = $survey->questions()->where('status', true)->get();
        $application_form_answers = $application_form->application_form_answers;

        if ($req->has('callback_query')) {
            if ($req['callback_query']['data'] == "cancel") {

                $application_form->application_form_answers()->delete();
                $application_form->delete();
                $this->user->action = "nesting";

                $this->tgApiService->sendTelegram(
                    'sendMessage',
                    array(
                        'chat_id' => $this->user->user_id,
                        'text' => 'Отменено',
                        'disable_web_page_preview' => false,
                        'parse_mode' => 'html',
                    )
                );

                $button = Buttons::firstWhere('nesting', $this->user->nesting);

                $this->keyboardCreate($button['keyboard_button'], $button['nesting'], $button['keyboard_button']);

                $this->user->save();
            }
        } else {

            if (count($application_form_answers) < count($questions)) {

                ApplicationFormAnswer::create([
                    'question' => $questions[count($application_form_answers)]->name,
                    'answer' => $req['message']['text'],
                    'application_form_id' => $application_form->id,
                ]);

        
                if ((count($application_form_answers) + 1) < count($questions)) {

                    $buttons = array();

                    $buttons[0]['vertical'] = 1;
                    $buttons[0]['horizontal'] = 1;
                    $buttons[0]['name'] = 'Отмена';
                    $buttons[0]['id'] = 'cancel';

                    $buttons = collect($buttons);


                    $keyboard = $this->keyboardService->keyboardGeneration($buttons);

                    $this->tgApiService->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $this->user->user_id,
                            'text' => $questions[(count($application_form_answers) + 1)]->name,
                            'disable_web_page_preview' => false,
                            'reply_markup' => $keyboard,
                            'parse_mode' => 'html',
                        )
                    );
                } else {

                    $application_form->status = "completed";
                    $application_form->save();

                    $this->user->action = "nesting";

                    $this->tgApiService->sendTelegram(
                        'sendMessage',
                        array(
                            'chat_id' => $this->user->user_id,
                            'text' => $survey->message_after_survey,
                            'disable_web_page_preview' => false,
                            'parse_mode' => 'html',
                        )
                    );

                    $button = Buttons::firstWhere('nesting', $this->user->nesting);

                    $this->keyboardCreate($button['keyboard_button'], $button['nesting'], $button['keyboard_button']);

                    $this->user->save();

                    $application_form->refresh();

                    $this->sendModerators($application_form->application_form_answers);
                    $this->sendMails($application_form->application_form_answers);
                }
            }
        }
    }



    public function sendModerators($application_form_answers)
    {
        $moderators = TgUser::where('role', 'moderator')->get();

        for ($i = 0; $i < count($moderators); $i++) {

            $text = "<b>Новая заявка</b> \n<b>Пользователь:</b> " . $this->user->username . "\n<b>id:</b> " . $this->user->user_id . " \n";

            for ($k = 0; $k < count($application_form_answers); $k++) {
                $text = $text . "\n<b>Вопрос:</b> " . $application_form_answers[$k]->question . "\n<b>Ответ:</b> " . $application_form_answers[$k]->answer;
            }


            $this->tgApiService->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $moderators[$i]->user_id,
                    'text' => $text,
                    'disable_web_page_preview' => false,
                    'parse_mode' => 'html',
                )
            );
        }
    }

    public function sendMails($application_form_answers)
    {
        $emails = TgEmail::all();
        $to_name = "Модератор бота";
        $data = array("application_form_answers" => $application_form_answers, "user" => $this->user);
        for ($i = 0; $i < count($emails); $i++) {
            $to_email = $emails[$i]->email;
            Mail::send('application-form-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Новая заявка');
                $message->from('andreyprogrammer150@gmail.com', 'Andrew Programmer');
            });
        }
    }


    public function newGeo($button)
    {

        $geo = GeoPositions::where('name', $this->nestingDown)->first();

        if (isset($geo)) {

            $this->tgApiService->sendTelegram(
                'sendLocation',
                array(
                    'chat_id' => $this->user->user_id,
                    'latitude' => $geo->latitude,
                    'longitude' => $geo->longitude,
                )
            );

            $this->tgApiService->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $this->user->user_id,
                    'text' => $geo->route_description,
                    'disable_web_page_preview' => false,
                    'parse_mode' => 'html',
                )
            );
        }
    }



    public function varibleFunc($buttons)
    {
        $variables = Variable::where("name", "<>", "none")->get();

        $vars = array();

        foreach ($variables as $key => $variable) {
            $vars["{" . $variable->name . "}"] = $variable->meaning;
        }

        for ($i = 0; $i < count($buttons); $i++) {
            $buttons[$i]['name'] = strtr($buttons[$i]['name'], $vars);
        }

        return $buttons;
    }

    public function inverseVaribleFunc($text)
    {
        $variables = Variable::where("name", "<>", "none")->get();

        $vars = array();

        foreach ($variables as $key => $variable) {
            $vars[$variable->meaning] = "{" . $variable->name . "}";
        }

        $text = strtr($text, $vars);

        return $text;
    }

    // Start


    public function start($req)
    {
        $buttons = Buttons::where('nesting', 'none')->get();

        $buttons = $this->varibleFunc($buttons);

        if ($buttons[0]['keyboard_button'] == 1) {
            $keyboard = $this->keyboardService->keyboardButtonGeneration($buttons);
        } else {
            $keyboard = $this->keyboardService->keyboardGeneration($buttons);
        }

        $this->tgApiService->sendTelegram(
            'sendMessage',
            array(
                'chat_id' => $this->user->user_id,
                'text' => $this->settings->message,
                'disable_web_page_preview' => false,
                'reply_markup' => $keyboard,
                'parse_mode' => 'html',
            )
        );
    }

    public function keyboardCreate($keyboard_button, $nesting, $keyboardGeneration)
    {

        $buttons = Buttons::where('keyboard_button', $keyboard_button)->where('nesting', $nesting)->get();

        if (count($buttons) > 0) {

            $buttons = $this->varibleFunc($buttons);

            if ($keyboardGeneration == 0)
                $keyboard = $this->keyboardService->keyboardGeneration($buttons);
            else
                $keyboard = $this->keyboardService->keyboardButtonGeneration($buttons);

            $this->tgApiService->sendTelegram(
                'sendMessage',
                array(
                    'chat_id' => $this->user->user_id,
                    'text' => $this->settings->message,
                    'disable_web_page_preview' => false,
                    'reply_markup' => $keyboard,
                    'parse_mode' => 'html',
                )
            );
        }
    }
}
