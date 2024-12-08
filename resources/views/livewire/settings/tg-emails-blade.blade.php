
<div>
<div class="table-responsive">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">email</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  @foreach($emails as $index => $email)
    <tr wire:key="email-field-{{ $email->id }}">
      <th scope="row">{{$index}}</th>
      <td>{{$email->email}}</td>
      <td>
        <button title="Delete" class="btn btn-sm btn-danger" wire:click="delete({{ $email->id }})"><i class="fa fa-trash-o"></i></button>
    </td>
    </tr>
    @endforeach
  </tbody>
</table>
      </div>

      <div class="mb-3">
      <input class="form-control"  type="text" wire:model="newEmail" placeholder="email">
      </div>
      <div class="mb-3">
    <button class="btn btn-sm btn-primary" wire:click="add">Добавить</button>
      </div>
</div>
