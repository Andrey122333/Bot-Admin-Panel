<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyContoller extends Controller
{
    public function view() {

        return view("livewire/surveys");
    }

    public function main() {
        $survey = Survey::where('name', 'we')->first();
        Survey::where('id', $survey->id)->delete();
    }


}
