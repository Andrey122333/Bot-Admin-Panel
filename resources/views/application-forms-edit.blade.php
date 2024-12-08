@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.create'))

@section('body')
<div class="card">
    <form method="POST" action="{{ url()->current() }}/save">
        @csrf
        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
            <a href="{{ substr(url()->current(), 0, strrpos(url()->current(), '/')) }}" class="btn btn-primary btn-lg">
                <i class="fa fa-arrow-left text-black"></i>
            </a>
            <h3 class="mb-0">Заявка №{{ $application_form->id }}</h3>
            <button class="btn btn-success btn-lg text-dark d-none d-md-block" type="submit">
                <i class="fa fa-save text-dark"></i> Сохранить
            </button>
            <button class="btn btn-success btn-lg text-white d-md-none" type="submit">
                <i class="fa fa-save"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="username">Username:</label>
                        <div class="col-md-10">
                            @if ($application_form->user->username != '')
                            <a href="https://t.me/{{ $application_form->user->username }}" target="_blank">{{
                                $application_form->user->username }}</a>
                            @else
                            <input name="user_id" class="form-control" value="{{ $application_form->user->user_id }}"
                                id="user_id" type="text" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="survey_name">Опрос:</label>
                        <div class="col-md-10">
                            <input name="survey_name" class="form-control" value="{{ $application_form->survey->name }}"
                                id="survey_name" type="text" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mx-md-auto">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="created_at">Создан:</label>
                        <div class="col-md-10">
                            @php setlocale(LC_TIME, 'ru_RU.UTF-8') @endphp
                            <input name="created_at" class="form-control"
                                value="{{ $application_form->created_at->formatLocalized('%d.%m.%Y в %H:%M (%A)') }}"
                                id="created_at" type="text" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="status">Статус:</label>
                        <div class="col-md-10">
                            <input name="status" class="form-control" value="{{ $application_form->status }}"
                                id="status" type="text" placeholder="Статус">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Вопрос</th>
                    <th scope="col">Ответ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($application_form_answers as $index => $application_form_answer)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td>{{ $application_form_answer->question }}</td>
                    <td>{!! json_decode(str_replace('\n', '<br>', json_encode($application_form_answer->answer))) !!}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
