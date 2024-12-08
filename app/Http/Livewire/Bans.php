<?php

namespace App\Http\Livewire;

use App\Models\Ban;
use Livewire\Component;

class Bans extends Component
{

    public $bans;

    protected $rules = [
        'bans.*.name' => 'string',
        'bans.*.description' => 'string',
    ];

    public function save() {
        $this->validate();
 
        foreach ($this->bans as $ban) {
            $ban->save();
        }
    }

    public function add() {

        $this->validate();
 
        foreach ($this->bans as $ban) {
            $ban->save();
        }

        Ban::create([
            "name" => "none",
            "description" => "dsa",
        ]);

        $this->bans = Ban::get();
    }

    public function deleteBan($id) {
        
        Ban::where('id', $id)->delete();
        $this->bans = Ban::get();
    }

    
    public function mount()
    {
        $this->bans = Ban::get();
    }


    public function render()
    {

        return view('livewire.bans');
    }
}
