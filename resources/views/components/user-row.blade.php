@php
    $isBanned = $banTime !== '0000-00-00 00:00:00';
    
    use App\Helpers\RegionConverter;

    
    $regionConverter = new RegionConverter();
    $regionConverter->setRegionCode($langcode);
    $countryFlag = $regionConverter->getFlagEmoji();
    $countryName = $regionConverter->getCountryName();
    $continentName = $regionConverter->getContinentName($langcode);
    
    $banInfo = '';
    if ($isBanned) {
        $customDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $banTime);
        $currentDate = \Carbon\Carbon::now();
        $diffInHours = $customDate->diffInHours($currentDate);
        $formattedDate = $customDate->format('Y-m-d H:i:s');
        $banInfo = '<span class="badge badge-primary mr-1">' . $formattedDate . '</span>' . '<span class="badge badge-secondary mr-1">' . $diffInHours . ' ч.</span>';
    }
@endphp

<tr @if ($isBanned) class="table-danger" @endif>
    <th scope="row">{{ $index }}</th>
    <td>{{ $id }}</td>
    <td>
        @if($username)
            <a href="https://t.me/{{ $username }}" target="_blank">{{ '@' . $username }}</a>
        @endif
    </td>
    <td>{{ $firstname }}</td>
    <td>{{ $countryFlag }} {{ $countryName }} ({{ $continentName }})</td>
    <td>
        @if (!$isBanned)
            <select wire:model="users.{{ $index }}.banType" class="form-control">
                <option value="0">По умолчанию</option>
                @foreach ($bans as $ban)
                    <option value="{{ $ban['id'] }}">{{ $ban['name'] }}</option>
                @endforeach
            </select>
        @else
            <span>{{ $banName }}</span>
        @endif
    </td>
    <td>
        @if (!$isBanned)
            <div class="input-group">
                <input wire:model.lazy="users.{{ $index }}.banTime" type="number" class="form-control"
                    style="max-width: 150px; padding-right: 1rem!important" wire:keydown.lazy />
                <div class="input-group-append">
                    <span class="input-group-text">часов</span>
                </div>
            </div>
        @else
            {!! $banInfo !!}
        @endif
    </td>
    <td>
        @if ($isBanned)
            <button title="" class="btn btn-sm btn-success" wire:click="unban({{ $id }})"
                title="Разблокировать пользователя">Разблокировать</button>
        @else
            <button title="" class="btn btn-sm btn-danger" wire:click="ban({{ $id }})"
                title="Заблокировать пользователя" wire:loading.attr="disabled">Заблокировать</button>
        @endif
    </td>
    <td>
        <a href="{{ url('admin/tg-broadcast/' . $id) }}" title="Отправить сообщение" class="btn btn-sm btn-primary">
            <i class="fa fa-paper-plane"></i>
        </a>
    <td>
</tr>
