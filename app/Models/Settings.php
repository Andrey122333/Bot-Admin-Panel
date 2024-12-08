<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'telegram_applications_count',
        'telegram_technical_work_message',
        'telegram_locked_account_button_text',
        'telegram_default_locked_account_reason',
    ];
}
