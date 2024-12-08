<?php

namespace App\Http\Livewire;

use App\Helpers\UserMessageDataExtractor;

use App\Models\ApplicationForm;
use App\Models\ApplicationFormAnswer;
use App\Models\Settings;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;


class ApplicationFormsBlade extends Component
{
    use WithPagination;

    public $delete;
    public $survey;
    public $surveys;
    public $oldSurvey = 'dasdasdw67as7d67asdsa';
    public $selectedSurvey;
    public $perPage = 10;
    public $sortDirection = 'desc';
    public $settings;

    protected $application_forms;

    protected $edit_application_forms;

    protected $rules = [
        'edit_application_forms.*.status' => 'string',
    ];

    public function mount()
    {
        $this->settings = Settings::select('*')->first();
        if ($this->settings->web_applications_per_page) {
            $this->perPage = $this->settings->web_applications_per_page;
        }

        $this->surveys = Survey::all();
        $this->application_forms = ApplicationForm::where("status", "<>", "progress")->orderBy('created_at', $this->sortDirection)->paginate($this->perPage);

        if ($this->application_forms->isNotEmpty()) {
            $this->survey = $this->application_forms->first()->survey;
            if ($this->survey) {
                $this->selectedSurvey = $this->survey->name;
            } else {
                $this->selectedSurvey = '';
            }
        }
    }

    public function deleteThis($id)
    {
        // Удалить данные
        DB::transaction(function () use ($id) {
            ApplicationForm::whereIn('id', [$id])->delete();
            ApplicationFormAnswer::whereIn('application_form_id', [$id])->delete();
        });

        // Загрузить обновленные данные
        $this->application_forms = ApplicationForm::select('*')
            ->where('status', '<>', 'progress')
            ->orderBy('created_at', $this->sortDirection)
            ->paginate($this->perPage);
    }


    public function delete()
    {
        ApplicationForm::where('id', $this->delete)->delete();
        ApplicationFormAnswer::where("application_form_id", $this->delete)->delete();
        $this->survey->refresh();
    }

    public function save()
    {
        $this->validate();

        foreach ($this->edit_application_forms as $application_form) {
            $application_form->save();
        }
    }

    public function render()
    {

        if ($this->selectedSurvey) {
            $this->survey = Survey::where('id', $this->selectedSurvey)->first();

            if ($this->survey) {
                $this->application_forms = $this->survey->application_forms()
                    ->where("status", "<>", "progress")
                    ->orderBy('created_at', $this->sortDirection)
                    ->with('application_form_answers')
                    ->paginate($this->perPage);
                $this->oldSurvey = $this->selectedSurvey;
            }
        } else {
            $this->application_forms = ApplicationForm::where("status", "<>", "progress")
                ->orderBy('created_at', $this->sortDirection)
                ->with('application_form_answers')
                ->paginate($this->perPage);
        }

        $this->edit_application_forms = $this->application_forms;

        // объявляем массив до итерации
        $parsed_answers = [];
        $this->application_forms->getCollection()->transform(function ($item) use (&$parsed_answers) {
            $answers = $item->application_form_answers->pluck('answer')->toArray();
            $answers = implode(' ', $answers);

            $UserMessageDataExtractor = new UserMessageDataExtractor();
            $parsed_answers[] = $UserMessageDataExtractor->parse($answers);

            return $item;
        });
        $application_forms = $this->application_forms;
        return view('livewire.application-forms-blade', compact('application_forms', 'parsed_answers'));
    }
}
