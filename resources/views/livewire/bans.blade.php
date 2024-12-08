<div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Название</th>
                            <th scope="col">Описание</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bans as $index => $ban)
                            <tr wire:key="ban-field-{{ $ban->id }}">
                                <th scope="row">{{ $index + 1 }}</th>
                                <td><input wire:model="bans.{{ $index }}.name" type="text" placeholder="Название" class="form-control"></td>
                                <td><textarea class="form-control" wire:model="bans.{{ $index }}.description" rows="3" placeholder="Описание"></textarea></td>
                                <td>
                                    <button title="Delete" class="btn btn-sm btn-danger" wire:click="deleteBan({{ $ban->id }})">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col text-center">
            <button class="btn btn-primary" wire:click="add">
                <i class="fa fa-plus"></i>&nbsp;Добавить
            </button>
        </div>
        <div class="col text-center">
            <button type="submit" class="btn btn-success text-dark" wire:click="save">
                <i class="fa fa-save text-dark"></i>&nbsp;Сохранить
            </button>
        </div>
    </div>
</div>