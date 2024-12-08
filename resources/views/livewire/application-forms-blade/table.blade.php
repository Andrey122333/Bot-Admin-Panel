<div class="table-responsive-xl">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Номер</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Опрос</th>
                <th scope="col">Дата</th>
                <th scope="col">Статус</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($application_forms as $index => $application_form)
            <tr wire:key="application_form-field-{{ $application_form->id }}">
                <th scope="row">{{$index}}</th>
                <td>{{$application_form->number_user_form}}</td>
                @if ($application_form->user->username != '') 
                <td>{{$application_form->user->username}}</td>
                @else
                <td>{{$application_form->user->user_id}}</td>
                @endif
                <td>{{$selectedSurvey}}</td>
                <td>{{$application_form->created_at}}</td>
                <td><input wire:model="application_forms.{{ $index }}.status"  type="text" placeholder="Статус"></td>
                <td><a href="{{ url('admin/application-forms/'.$application_form->id) }}" title="Edit" role="button" class="btn btn-sm btn-spinner btn-info text-light">Ответы</a></td>
                <td>
                    <button title="Delete" class="btn btn-sm btn-danger" wire:click="deleteThis({{ $application_form->id }})"><i class="fa fa-trash-o"></i></button>
                </td>
            </tr>
            @endforeach
