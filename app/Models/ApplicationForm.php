<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_user_form',
        'user_id',
        'survey_id',
        'status',
    ];
    
    public function application_form_answers()
    {
        return $this->hasMany(ApplicationFormAnswer::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(TgUser::class, 'user_id', 'id');
    }

}
