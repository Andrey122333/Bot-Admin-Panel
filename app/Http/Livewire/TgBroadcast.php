<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Survey;
use App\Models\ApplicationForm;
use App\Models\TgUser;
use App\Service\TgApiService;
use App\Helpers\RegionConverter;
use Illuminate\Http\Request;

class TgBroadcast extends Component
{
    protected $tgApiService;

    public $user_id;
    public $surveys;
    public $languages = [];
    public $regions = [];
    public $tgpremium;
    public $message;
    public $surveys_selected = [];

    protected $rules = [
        'message' => ['required', 'string', 'min:10', 'max:4096']
    ];

    public function render(Request $request)
    {
        $user_id = $request->route('user_id');

        if ($user_id) {
            $user_id = TgUser::where('user_id', $user_id)->first();
            if (!$user_id) {
                abort(404, 'Пользователь не найден');
            }
        }

        if (!$this->user_id) {
            $this->user_id = request()->user_id;
        }

        $this->surveys = Survey::all();
        $this->languages = TgUser::select('language_code')
            ->distinct()
            ->whereNotNull('language_code')
            ->get()
            ->map(function ($language) {
                $regionConverter = new RegionConverter();
                $regionConverter->setRegionCode($language->language_code);
                $countryFlag = $regionConverter->getFlagEmoji();
                $countryName = $regionConverter->getCountryName();

                return [
                    'code' => $language->language_code,
                    'countryFlag' => $countryFlag,
                    'countryName' => $countryName,
                ];
            });

        return view('livewire.tg-broadcast')->with('success', session('success'));
    }

    public function broadcastMessage()
    {
        $this->validate();
        $this->tgApiService = new TgApiService();

        $users = [];
        $query = TgUser::query();

        if ($this->user_id) {
            $query->where('user_id', $this->user_id);
        } else {
            if (!empty($this->surveys_selected)) {
                $surveys_participants = ApplicationForm::whereIn('survey_id', $this->surveys_selected)
                    ->whereNotIn('status', ['progress'])
                    ->distinct()
                    ->pluck('user_id')
                    ->toArray();
                $query->whereIn('id', $surveys_participants);
            }

            if ($this->tgpremium) {
                $query->where('is_premium', 1);
            }

            if (!empty($this->regions)) {
                $query->whereIn('language_code', $this->regions);
            }
        }

        $users = $query->pluck('user_id')->toArray();

        $sentCount = 0;
        foreach ($users as $user) {
            $this->tgApiService->sendTelegram(
                'sendMessage',
                [
                    'chat_id' => $user,
                    'text' => $this->message,
                ]
            );
            $sentCount++;
        }

        session()->flash('success', "Message sent successfully to $sentCount users!"); // add sentCount to success message
        $this->emit('broadcastMessage');
        return response()->json(['status' => 'success']);
    }
}
