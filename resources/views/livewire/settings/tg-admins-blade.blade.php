<div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">id пользователя</th>
                    <th scope="col">role</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $index => $admin)
                    <tr wire:key="admin-field-{{ $admin->id }}">
                        <th scope="row">{{ $index }}</th>
                        <td>{{ $admin->user_id }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>
                            <button title="Удалить" class="btn btn-sm btn-danger"
                                wire:click="delete({{ $admin->id }})"><i class="fa fa-trash-o"></i></button>
                        </td>
                        <td> <a href="{{ url('admin/tg-broadcast/' . $admin->user_id) }}" title="Отправить сообщение"
                                class="btn btn-sm btn-primary">
                                <i class="fa fa-paper-plane"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-3">
        <input class="form-control" type="text" wire:model="newAdmin" placeholder="id пользователя">
    </div>
    <div class="mb-3">
        <button class="btn btn-sm btn-primary" wire:click="add">Добавить</button>
    </div>
</div>