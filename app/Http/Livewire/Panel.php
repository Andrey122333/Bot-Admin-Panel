<?php
// 1. Добавить что названия кнопок для клавиатуры должны быть уникальны

namespace App\Http\Livewire;

use App\Models\Buttons;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Panel extends Component
{
    public $selectedNesting = 'none';
    public $buttons;
    public $selectedCheck;
    public $oldNesting = 'qwedwad3213311133qsw213123213dadaadwq2112edas';
    public $settings;
    public $allClass;

    protected $rules = [
        'buttons.*.name' => 'string',
        'buttons.*.class' => 'string',
        'buttons.*.type' => 'string',
        'buttons.*.nesting_down' => 'string',
        'buttons.*.keyboard' => 'string',
        'buttons.*.horizontal' => 'integer',
        'buttons.*.vertical' => 'integer',
        'settings.message' => 'string',
    ];

    public function mount()
    {
        $this->allClass = glob("../app/Http/Controllers/tg/class/*.php"); 
        $this->settings = Settings::select("message")->first();
    }

    public function changeMessage()
    {
        Settings::where('id', 1)->update(['message' => $this->settings->message]);
    }

    public function save()
    {

        $this->validate();

        foreach ($this->buttons as $button) {
            $button->save();
        }
    }

    public function delete($id)
    {

        Buttons::where('id', $id)->delete();
        $this->buttons = Buttons::where('nesting', $this->selectedNesting)->get();
    }

    public function addButton()
    {

        $this->validate();

        $vertical = $this->buttons->max('vertical');
        $horizontal = $this->buttons->where('vertical', $vertical)->max('horizontal');

        if ($vertical == 0 || $horizontal == 0) {
            $vertical = 1;
            $horizontal = 1;
        } else if ($horizontal > 1) {
            $vertical += 1;
            $horizontal = 1;
        } else {
            $horizontal += 1;
        }

        Buttons::where('nesting', $this->selectedNesting)
            ->update(['keyboard_button' => $this->selectedCheck]);

        $buttons = Buttons::create([
            "nesting" => $this->selectedNesting,
            "keyboard_button" => $this->selectedCheck,
            "vertical" => $vertical,
            "type" => "Nesting",
            "horizontal" => $horizontal
        ]);

        $this->buttons = Buttons::where('nesting', $this->selectedNesting)->get();
    }

    public function SaveKeyboard()
    {
        $this->validate();

        foreach ($this->buttons as $button) {
            $button->keyboard_button = $this->selectedCheck;
            $button->save();
        }
    }

    public function render()
    {
        // Выборка уникальных значений клавиатур и соответствующих им кнопок
        $keyboards = Buttons::select("keyboard")
            ->groupBy('keyboard')
            ->where('keyboard', '<>', '')
            ->addSelect('keyboard_button')
            ->get()
            ->map(function ($keyboard) {
                return [
                    'nesting_down' => $keyboard['keyboard'],
                    'keyboard_button' => Buttons::select('keyboard_button')
                        ->where('nesting', $keyboard['keyboard'])
                        ->value('keyboard_button') ?? 0,
                ];
            });

        // Выборка уникальных значений nesting_down и соответствующих им кнопок
        $nestings = Buttons::select('nesting_down')
            ->where('type', 'Nesting')
            ->where('nesting_down', '<>', '')
            ->groupBy('nesting_down')
            ->orderBy('keyboard_button')
            ->addSelect('keyboard_button')
            ->get()
            ->map(function ($nesting_down) {
                return [
                    'nesting_down' => $nesting_down['nesting_down'],
                    'keyboard_button' => Buttons::select('keyboard_button')
                        ->where('nesting', $nesting_down['nesting_down'])
                        ->value('keyboard_button') ?? 0,
                ];
            });

        // Объединение результатов выборок и удаление дубликатов
        $nestings = collect($keyboards)->merge(collect($nestings))->unique('nesting_down')->sortBy('nesting_down');

        // Выборка уникальных значений nesting и соответствующих им кнопок
        $mynesting = Buttons::select('nesting')
            ->groupBy('nesting')
            ->orderBy('keyboard_button')
            ->addSelect('keyboard_button')
            ->get()
            ->map(function ($nesting) {
                return [
                    'nesting_down' => $nesting['nesting'],
                    'keyboard_button' => $nesting['keyboard_button'],
                ];
            });

        // Объединение результатов выборок и удаление дубликатов
        $testy = $nestings->merge($mynesting)->unique('nesting_down')->values()->all();

        // Выборка кнопок для выбранного nesting
        if ($this->selectedNesting != $this->oldNesting) {
            $this->buttons = Buttons::where('nesting', $this->selectedNesting)->get();
            $this->selectedCheck = $this->buttons->isNotEmpty()
                ? Buttons::select('keyboard_button')
                ->where('nesting', $this->selectedNesting)
                ->value('keyboard_button')
                : false;
            $this->oldNesting = $this->selectedNesting;
        }

        return view('livewire.panel', [
            'nestings' => $testy,
            'keyboards' => $keyboards,
            'testy' => $testy,
        ]);
    }
}
