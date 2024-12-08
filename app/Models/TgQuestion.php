<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TgQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'survey_id',
    ];

    
}
