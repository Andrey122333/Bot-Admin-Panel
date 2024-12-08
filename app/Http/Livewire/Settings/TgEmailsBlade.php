<?php

namespace App\Http\Livewire\Settings;

use App\Models\TgEmail;
use Livewire\Component;

class TgEmailsBlade extends Component
{
    public $emails;
    public $newEmail;

    public function add()
    {
        
        $emails = TgEmail::create([
            "email" => $this->newEmail
        ]);
    }

    public function delete($id) {
        TgEmail::where('id', $id)->delete();
    }

    public function render()
    {

        $this->emails = TgEmail::all();

        return view('livewire.settings.tg-emails-blade');
    }
}
