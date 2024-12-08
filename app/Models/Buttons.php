<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buttons extends Model
{
    use HasFactory;

    protected $fillable = [
        'nesting',
        'type',
        'keyboard_button',
        'nesting_down',
        'class',
        'keyboard',
        'horizontal',
        'vertical',
    ];
}
