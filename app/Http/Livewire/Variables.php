<?php

namespace App\Http\Livewire;

use App\Models\Variable;
use Livewire\Component;

class Variables extends Component
{

    public $variables;
    public $groups;
    public $group;
    public $selectedGroup;
    public $oldGroup = 'dasdasdw67as7d67asdsa';


    protected $rules = [
        'variables.*.name' => 'string',
        'variables.*.description' => 'string',
        'variables.*.group' => 'string',
        'variables.*.meaning' => 'string',
    ];

    public function saveVariable() {
        $this->validate();
 
        foreach ($this->variables as $variable) {
            $variable->save();
        }
    }

    public function addVariable() {
        $this->validate();
 
        foreach ($this->variables as $variable) {
            $variable->save();
        }


        if (!isset($this->selectedGroup)) {
            $this->selectedGroup = 'none';
        }

        $questions = Variable::create([
            "name" => "none",
            "group" => $this->selectedGroup,
        ]);

        $this->variables = Variable::where('group', $this->selectedGroup)->get();
    }

    public function deleteVariable($id) {
        Variable::where('id', $id)->delete();
        $this->variables = Variable::where('group', $this->selectedGroup)->get();

        if (count($this->variables)==0) {
            $this->mount();
        }
    }

    public function mount()
    {
        $this->selectedGroup = Variable::select("group")->groupBy('group')->first();
        if (isset($this->selectedGroup->group)) {
            $this->selectedGroup = $this->selectedGroup->group;
            $this->variables = Variable::where('group', $this->selectedGroup)->get();
        }
    }
    
    public function render()
    {
        $this->groups = Variable::select("group")->groupBy('group')->get();

        if ($this->selectedGroup!=$this->oldGroup) {
            $this->variables = Variable::where('group', $this->selectedGroup)->get();
            $this->oldGroup = $this->selectedGroup;
        }

        return view('livewire.variables');
    }
}
