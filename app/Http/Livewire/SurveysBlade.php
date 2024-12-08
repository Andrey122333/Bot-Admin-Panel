<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\TgEmail;
use App\Models\TgQuestion;
use Livewire\Component;

class SurveysBlade extends Component
{
    public $questions;
    public $surveys;
    public $survey;
    public $selectedSurvey;
    public $newSurvey;
    public $messageAfterSurvey;
    public $oldSurvey = 'dasdasdw67as7d67asdsa';


    protected $rules = [
        'questions.*.name' => 'string',
        'questions.*.status' => 'boolean',
        'surveys.*.name' => 'string',
        'surveys.*.message_after_survey' => 'string',
    ];

    function addSurvey() {
        $survey = Survey::create([
            "name" => $this->newSurvey,
            "message_after_survey" => $this->messageAfterSurvey,
        ]);
        $this->surveys = Survey::all();
        $this->selectedSurvey = $this->newSurvey;
        $this->survey = Survey::where('name', $this->selectedSurvey)->first();
    }

    public function deleteSurvey() {
         $this->survey->questions()->delete();
         $this->survey->delete();
         $this->surveys = Survey::all();
    }

    public function saveQuestion() {
        $this->validate();
 
        foreach ($this->questions as $question) {
            $question->save();
        }
    }

    public function addQuestion() {
        $this->validate();
 
        foreach ($this->questions as $question) {
            $question->save();
        }

        $questions = TgQuestion::create([
            "survey_id" => $this->survey->id,
            "status" => true,
            "name" => "Вопрос",
        ]);

        $this->questions = TgQuestion::where('survey_id', $this->survey->id)->get();
    }

    public function deleteQuestion($id) {
        TgQuestion::where('id', $id)->delete();
        $this->questions = TgQuestion::where('survey_id', $this->survey->id)->get(); //->refresh();
    }

    public function mount()
    {
        $this->surveys = Survey::all();
        $this->survey = Survey::first();
        if (isset($this->survey->questions)) {
            $this->questions = $this->survey->questions;
            $this->selectedSurvey = $this->survey->name;
        }
    }

    public function render()
    {

        if ($this->selectedSurvey!=$this->oldSurvey) {
            $this->survey = Survey::where('name', $this->selectedSurvey)->first();
            if (isset($this->survey->questions)) 
            $this->questions = $this->survey->questions;
            else
            $this->questions = TgQuestion::where('survey_id', 0)->get();
            $this->oldSurvey = $this->selectedSurvey;
          }

        return view('livewire.surveys-blade');
    }
}
