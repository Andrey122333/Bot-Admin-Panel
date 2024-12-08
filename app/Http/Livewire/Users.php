<?php

namespace App\Http\Livewire;

use App\Models\Ban;
use App\Models\TgUser;
use Carbon\Carbon;
use Livewire\Component;

class Users extends Component
{
    public $users;
    public $bans;

    protected $rules = [
        'users.*.banType' => 'required|integer',
        'users.*.banTime' => 'required|integer',
    ];

    public function ban($id)
    {
        $user = TgUser::firstWhere('user_id', $id);
        if (!$user) {
            return;
        }

        $foundUser = null;
        foreach ($this->users as $u) {
            if ($u->user_id == $id) {
                $foundUser = $u;
                break;
            }
        }

        if ($foundUser && $foundUser->banTime) {
            $ban = Ban::firstWhere('id', $foundUser->banType);
            $user->ban_id = $ban ? $ban->id : 0;
            $user->ban_time = Carbon::now()->addHours($foundUser->banTime);
            $user->save();
        }
    }

    public function unban($id)
    {
        TgUser::where('user_id', $id)->update([
            'ban_time' => '0000-00-00 00:00:00',
            'ban_id' => 0
        ]);
        $this->users = TgUser::get();
    }

    public function mount()
    {
        $time = Carbon::now();

        TgUser::where("ban_id", "<>", '0')->where('ban_time', '<=', $time)->update(['ban_id' => 0]);

        $this->users = TgUser::get();
        $this->bans = Ban::select("id", "name")->get();
    }

    public function render()
    {
        $this->bans = Ban::select("id", "name")->get();
        return view('livewire.users');
    }
}
