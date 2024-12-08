<div class="mb-3">
<h3 class="text-center">Короткий адрес: {{$user->username}}</h3>
<h3 class="text-center">id: {{$user->user_id}}</h3>
</div>
<div class="table-responsive">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Вопрос</th>
      <th scope="col">Ответ</th>
    </tr>
  </thead>
  <tbody>
  @foreach($application_form_answers as $index => $application_form_answer)
    <tr wire:key="application_form_answer-field-{{ $application_form_answer->id }}">
      <th scope="row">{{$index}}</th>
      <td>{{$application_form_answer->question}}</td>
      <td>{{$application_form_answer->answer}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
      </div>