<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFormAnswer extends Model
{
    use HasFactory;


    protected $fillable = [
        'question',
        'answer',
        'application_form_id',

    ];

    public function application_form()
    {
        return $this->belongsTo(ApplicationForm::class);
    }

}
