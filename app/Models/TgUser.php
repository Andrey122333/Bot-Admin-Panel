<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'role',
        'username',
        'ban_id',
        'ban_time',
        'name',
        'first_name',
        'last_name',
        'language_code',
        'is_premium',
    ];

    public function ban()
    {
        return $this->belongsTo(Ban::class);
    }
}
