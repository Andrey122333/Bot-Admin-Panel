<div>
<div class="mb-3">
    <select class="form-control" id="selectvalue" wire:model="selectedGroup">
      @foreach($groups as $group)
      <option>{{$group['group']}}</option>
      @endforeach
    </select>
</div>
<div class="table-responsive-xl">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Название</th>
      <th scope="col">Описание</th>
      <th scope="col">Группа</th>
      <th scope="col">Значение</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  @foreach($variables as $index => $variable)
    <tr wire:key="variable-field-{{ $variable->id }}">
      <th scope="row">{{$index}}</th>
      <td><input wire:model="variables.{{ $index }}.name" type="text" placeholder="Название"></td>
      <td><textarea class="form-control" wire:model="variables.{{ $index }}.description" rows="3" placeholder="Описание"></textarea></td>
      <td><input wire:model="variables.{{ $index }}.group" type="text" placeholder="Группа"></td>
      <td><input wire:model="variables.{{ $index }}.meaning" type="text" placeholder="Значение"></td>
      <td>
        <button title="Delete" class="btn btn-sm btn-danger" wire:click="deleteVariable({{ $variable->id }})"><i class="fa fa-trash-o"></i></button>
    </td>
    </tr>
    @endforeach
  </tbody>
</table>
      </div>
      <div class="mb-5">
    <button class="btn btn-primary" wire:click="addVariable">Создать переменную</button>
    <button class="btn btn-success text-dark" wire:click="saveVariable"><i class="fa fa-save text-dark"></i>&nbsp;Сохранить</button>
      </div>
</div>