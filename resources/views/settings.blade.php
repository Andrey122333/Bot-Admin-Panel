@extends('brackets/admin-ui::admin.layout.default')
@section('title', trans('admin.admin-user.actions.create'))
@section('body')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white py-2">Настройки Telegram-бота</div>
                <div class="card-body">
                    @if ($response != '')
                        <div class="alert {{ $response->getStatusCode() >= 400 ? 'alert-danger' : 'alert-success' }}"
                            role="alert">
                            {{ $response->getBody() }}
                        </div>
                    @endif
                    <form action="settings" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold" for="token">Token:</label>
                            <input class="form-control" value="{{ $settings->token }}" type="text" name="token"
                                placeholder="token" id="token">
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold" for="name">Name:</label>
                            <input class="form-control" value="{{ $settings->name }}" type="text" name="name"
                                placeholder="name" id="name">
                        </div>
                        <button class="btn btn-success text-dark" type="submit"><i class="fa fa-save text-dark"></i>
                            Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
        @if (isset($settings->token) && preg_match('/[0-9]{1,}:\w*/', $settings->token) && isset($settings->name))
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white py-2">Отладка Telegram-бота</div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3" onclick="getWebhookInfo('{{ $settings->token }}')">
                            <i class="fa fa-info-circle"></i> Получить информацию
                        </button>

                        <button class="btn btn-primary mb-3" onclick="resetPendingUpdateCount('{{ $settings->token }}')"
                            id="reset-queue-btn" style="display:none">
                            <i class="fa fa-trash"></i> Сбросить очередь сообщений
                        </button>

                        <div class="row">
                            <div class="col-md-12">
                                <pre class="alert-secondary py-2"><code id="webhook-info"></code></pre>
                            </div>
                        </div>

                        <a href="https://t.me/{{ $settings->name }}" target="_blank" class="btn btn-primary">
                            <i class="fa fa-telegram"></i> Открыть бота в Telegram
                        </a>

                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary text-white py-2">Дополнительные настройки</div>
                <div class="card-body">
                    @livewire('additional-settings')
                </div>
            </div>
        </div>
    </div>
    <script>
        const token = '{{ csrf_token() }}';

        document.addEventListener("DOMContentLoaded", function(event) {
            getWebhookInfo();
        });

        function getWebhookInfo() {
            $.ajax({
                url: 'get-webhook-info',
                type: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    var json = JSON.stringify(response, null, 4);
                    $('#webhook-info').text(json);
                    if (response.pending_update_count > 0) {
                        $('#reset-queue-btn').show();
                    } else {
                        $('#reset-queue-btn').hide();
                    }
                }
            });
        }

        function resetPendingUpdateCount() {
            $.ajax({
                url: 'reset-pending-update-count',
                type: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    alert('Очередь сообщений была успешно сброшена!');
                }
            });
            getWebhookInfo();
        }
    </script>
    @livewireScripts
@endsection
