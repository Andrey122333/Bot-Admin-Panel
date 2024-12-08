<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message_after_survey',
        'survey_id',
    ];

    public function questions()
    {
        return $this->hasMany(TgQuestion::class, 'survey_id', 'id');
    }

    public function application_forms()
    {
        return $this->hasMany(ApplicationForm::class);
    }
}
