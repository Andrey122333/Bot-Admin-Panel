<div>
    <div class="mb-3">
        <select class="form-control" id="selectvalue" wire:model="selectedSurvey">
            <option value="" selected>Все заявки</option>
            @foreach ($surveys as $survey)
                <option value="{{ $survey['id'] }}">{{ $survey['name'] }}</option>
            @endforeach
        </select>
    </div>
    @if ($application_forms->total())
        <div class="table-responsive-xl">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Номер</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Опрос</th>
                        <th scope="col" width="20%">Содержимое</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Статус</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($application_forms as $index => $application_form)
                        <tr wire:key="application_form-field-{{ $application_form->id }}">
                            <th scope="row">{{ $index }}</th>
                            <td>{{ $application_form->number_user_form }}</td>
                            @if ($application_form->user != null)
                                @if ($application_form->user->username != '')
                                    <td><a href="https://t.me/{{ $application_form->user->username }}"
                                            target="_blank">{{ '@' . $application_form->user->username }}</a></td>
                                @else
                                    <td>{{ $application_form->user->user_id }}</td>
                                @endif
                            @endif
                            <td>{{ $application_form->survey->name }}</td>
                            <td>
                                @if (isset($parsed_answers[$index]))
                                    @foreach (collect($parsed_answers[$index])->groupBy('name') as $name => $answers)
                                        @switch($answers[0]['type'])
                                            @case('crypto_wallet')
                                                <span class="badge badge-warning p-1" title="Криптокошельки">
                                                    <i class="fa fa-database"></i>&nbsp;{{ $name }}&nbsp;<sup>({{ count($answers) }})</sup>
                                                </span>
                                            @break
                                            @case('custom_data')
                                                <span class="badge badge-light p-1" title="Ссылки">
                                                    <i class="fa fa-link"></i>&nbsp;{{ $name }}&nbsp;<sup>({{ count($answers) }})</sup>
                                                </span>
                                            @break
                                            @case('debit_card')
                                                <span class="badge badge-primary p-1" title="Дебитовые карты">
                                                    <i class="fa fa-credit-card"></i>&nbsp;{{ $name }}&nbsp;<sup>({{ count($answers) }})</sup>
                                                </span>
                                            @break
                                            @case('personal_data')
                                                <span class="badge badge-light p-1" title="Персональные данные">
                                                    <i class="fa fa-address-card-o"></i>&nbsp;{{ $name }}&nbsp;<sup>({{ count($answers) }})</sup>
                                                </span>
                                            @break
                                            @default
                                                <span class="badge badge-light p-1">
                                                    <i class="fa fa-id-badge"></i>&nbsp;{{ $name }}&nbsp;<sup>({{ count($answers) }})</sup>
                                                </span>
                                        @endswitch
                                    @endforeach
                                @endif

                            </td>
                            <td>{{ $application_form->created_at }}</td>
                            <td>{{$application_form->status}}
                                
                            <!-- <input wire:model="edit_application_forms.{{ $index }}.status" type="text"
                                    value="{{ $application_form->status }}"> -->
                            </td>
                            <td><a href="{{ url('admin/application-forms/' . $application_form->id) }}" title="Edit"
                                    role="button" class="btn btn-sm btn-spinner btn-info text-light">Ответы</a>
                            </td>
                            <td>
                                <a href="{{ url('admin/tg-broadcast/' . $application_form->user->user_id) }}"
                                    title="Отправить сообщение" class="btn btn-sm btn-primary">
                                    <i class="fa fa-paper-plane"></i>
                                </a>
                            </td>
                            <td>
                                <button title="Удалить" class="btn btn-sm btn-danger"
                                    wire:click="deleteThis({{ $application_form->id }})"><i
                                        class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <ul class="pagination">
                                        <li
                                            class="page-item {{ $application_forms->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link"
                                                href="{{ $application_forms->previousPageUrl() }}">Назад</a>
                                        </li>
                                        @php($lastPage = $application_forms->lastPage())
                                        @for ($i = 1; $i <= $lastPage; $i++)
                                            <li
                                                class="page-item {{ $i == $application_forms->currentPage() ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $application_forms->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li
                                            class="page-item {{ $application_forms->hasMorePages() ? '' : 'disabled' }}">
                                            <a class="page-link"
                                                href="{{ $application_forms->nextPageUrl() }}">Вперед</a>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <button class="btn btn-success text-dark" wire:click="save"><i
                                            class="fa fa-save text-dark"></i>&nbsp;Сохранить</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            Записей не найдено.
        </div>
    @endif
</div>