<?php

namespace App\Http\Livewire\Settings;

use App\Models\TgAdmin;
use App\Models\TgUser;
use Livewire\Component;

class TgAdminsBlade extends Component
{
    public $admins;
    public $newAdmin;

    public function add()
    {
        
        $this->admins = TgUser::updateOrCreate(
            ["user_id" => $this->newAdmin],
            ["role" => 'moderator']);
    }

    public function delete($id) {
        TgUser::where('id',$id)->update(['role' => 'user']);
    }

    public function render()
    {
        $this->admins = TgUser::where('role', 'moderator')->get();

        return view('livewire.settings.tg-admins-blade');
    }
}
