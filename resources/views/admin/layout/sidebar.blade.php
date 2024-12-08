<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title pb-1"><i class="fa fa-tasks"></i> {{ __('Заявки') }}</li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/application-forms') }}">
                    <small>{{ __('Все заявки') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/surveys') }}">
                    <small>{{ __('Конструктор вопросов') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/tg-admins') }}">
                    <small>{{ __('Модераторы заявок') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/tg-emails') }}">
                    <small>{{ __('Email уведомления') }}</small></a></li>
            <li class="nav-title pb-1 pt-3"><i class="fa fa-users"></i> {{ __('Пользователи') }}</li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/users') }}">
                    <small>{{ __('Список пользователей') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/bans') }}">
                    <small>{{ __('Причины блокировок') }}</small></a></li>
            <!-- <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/tg-broadcast') }}">
                    <small>{{ __('Рассылка') }}</small></a></li> -->
            <li class="nav-title pb-1 pt-4"><i class="fa fa-map-marker"></i> {{ __('Геопозиции') }}</li>
            <li class="nav-item"><a class="nav-link p-1 pl-3"
                    href="{{ url('admin/geo-position') }}"><small>{{ __('Конструктор геометок') }}</small></a></li>
            <li class="nav-title pb-1 pt-4"><i class="fa fa-telegram"></i> {{ __('Настройки бота') }}</li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/') }}"><small>{{ __('Конструктор меню') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/variables') }}">
                    <small>{{ __('Список переменных') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/settings') }}">
                    <small>{{ __('Общие настройки') }}</small></a></li>
            {{-- Do not delete me :) I'm used for auto-generation menu items --}}
            <li class="nav-title pb-1 pt-4">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/admin-users') }}"><small>{{ __('Manage access') }}</small></a></li>
            <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/translations') }}"><small>{{ __('Translations') }}</small></a></li>
            {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
            {{-- <li class="nav-item"><a class="nav-link p-1 pl-3" href="{{ url('admin/configuration') }}"><small>{{ __('Configuration') }}</small></a></li> --}}
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>



