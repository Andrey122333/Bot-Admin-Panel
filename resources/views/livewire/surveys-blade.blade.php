<div>
   <div class="mb-3 d-flex flex-column flex-md-row align-items-md-center">
        <select class="form-control mr-md-2 mb-2 mb-md-0" id="selectvalue" wire:model="selectedSurvey">
            @foreach ($surveys as $survey)
                <option>{{ $survey['name'] }}</option>
            @endforeach
        </select>

      <button title="Delete" class="btn btn-sm btn-danger" wire:click="deleteSurvey()" style="height:38px">Удалить</button>
   </div>
   
    <div class="mb-3">
        <input class="form-control" type="text" wire:model="newSurvey" placeholder="Введите название опроса">
    </div>
    <div class="mb-3">
        <textarea class="form-control" rows="4" wire:model="messageAfterSurvey"
            placeholder="Введите сообщение, которое пользователь увидит после прохождения опроса"></textarea>
        <small class="form-text text-muted">В случае, если Вы не введете текст в поле, система использует
            предустановленный текст из своих настроек.</small>
    </div>
    <div class="mb-3">
        <button class="btn btn-sm btn-primary" wire:click="addSurvey">Добавить</button>
    </div>

    <div class="table-responsive-xl">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" width="1%">#</th>
                    <th scope="col">Вопрос</th>
                    <th scope="col" width="1%">Статус</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    <tr wire:key="question-field-{{ $question->id }}">
                        <th scope="row">{{ $index }}</th>
                        <td>
                            <textarea class="form-control" wire:model="questions.{{ $index }}.name" rows="3"
                                placeholder="Введите вопрос, на который пользователь должен ответить"></textarea>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" id="flexCheckDefault-{{ $question->id }}"
                                    wire:model="questions.{{ $index }}.status" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault-{{ $question->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <button title="Delete" class="btn btn-sm btn-danger"
                                wire:click="deleteQuestion({{ $question->id }})"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <button class="btn btn-primary" wire:click="addQuestion">
                            <i class="fa fa-plus"></i>&nbsp;Создать кнопку
                        </button>
                        <button class="btn btn-success text-dark" wire:click="saveQuestion">
                            <i class="fa fa-save text-dark"></i>&nbsp;Сохранить
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>