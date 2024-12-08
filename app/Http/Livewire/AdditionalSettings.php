<?php

namespace App\Http\Livewire;

use App\Models\Settings;
use Livewire\Component;

class AdditionalSettings extends Component
{

    public $settings;

    protected $rules = [
        'settings.telegram_applications_count' => 'integer',
        'settings.web_applications_per_page' => 'integer',
        'settings.telegram_technical_work_message' => 'nullable|string',
        'settings.telegram_locked_account_button_text' => 'nullable|string',
        'settings.telegram_default_locked_account_reason' => 'nullable|string',
    ];

    public function mount()
    {
        $this->settings = Settings::select('*')->first();
    }

    public function save()
    {
        $this->validate();

        Settings::where('id', 1)->update([
            'web_applications_per_page' => $this->settings->web_applications_per_page,
            'telegram_applications_count' => $this->settings->telegram_applications_count,
            'telegram_technical_work_message' => $this->settings->telegram_technical_work_message,
            'telegram_locked_account_button_text' => $this->settings->telegram_locked_account_button_text,
            'telegram_default_locked_account_reason' => $this->settings->telegram_default_locked_account_reason,
        ]);
    }


    public function render()
    {
        return view('livewire.additional-settings');
    }
}
