<div wire:poll.5000ms>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">id</th>
                    <th scope="col">@username</th>
                    <th scope="col">Имя пользователя</th>
                    <th scope="col">Язык пользователя</th>
                    <th scope="col">Тип бана</th>
                    <th scope="col">Часы</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    @php
                        $banName = $user->ban_id == 0 && $user->ban_time == 0 ? $user->banName : 'По умолчанию';
                        $banTime = $user->ban_id == 0 && $user->ban_time == 0 ? $user->banTime : $user->ban_time;
                    @endphp
                    <x-user-row :index="$index" :id="$user->user_id" :firstname="$user->first_name" :username="$user->username" :langcode="$user->language_code"
                        :ban-name="$banName" :ban-time="$banTime" :ban-id="$user->ban_id" :bans="$bans"
                        wire:key="user-field-{{ $user->id }}" />
                @empty
                    <tr>
                        <td colspan="6">Записей не найдено.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
