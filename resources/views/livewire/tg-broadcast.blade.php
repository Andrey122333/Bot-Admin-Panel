<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white py-2">{{ 'Отправка сообщений от бота' }}</div>
            <div class="card-body">
                <form wire:submit.prevent="broadcastMessage">
                    @if (isset($user_id))
                        <div class="form-group row">
                            <label for="user_id"
                                class="col-md-4 col-form-label text-md-right">{{ 'Пользователь' }}</label>
                            <div class="col-md-6" wire:ignore>
                                <input type="text" id="user_id" class="form-control" name="user_id"
                                    wire:model="user_id" readonly>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="message" class="col-md-4 col-form-label text-md-right">{{ 'Сообщение' }}</label>
                        <div class="col-md-6">
                            <textarea wire:model="message" id="message" class="form-control @error('message') is-invalid @enderror" name="message"
                                required autocomplete="message"></textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($success)
                                <div class="alert alert-success my-2">{{ $success }}</div>
                            @endif
                        </div>
                    </div>
                    @if (!isset($user_id))
                        <div class="form-group row">
                            <label for="languages"
                                class="col-md-4 col-form-label text-md-right">{{ 'Регион' }}</label>
                            <div class="col-md-6" wire:ignore>
                                <select id="regions-dropdown" class="form-control" multiple wire:model="regions">
                                    @foreach ($languages as $language)
                                        <option value="{{ $language['code'] }}">{{ $language['countryFlag'] }}
                                            {{ $language['countryName'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="languages"
                                class="col-md-4 col-form-label text-md-right">{{ 'Участники опросов' }}</label>
                            <div class="col-md-6" wire:ignore>
                                <select id="surveys_selected-dropdown" class="form-control" multiple
                                    wire:model="surveys_selected">
                                    @foreach ($surveys as $survey)
                                        <option value="{{ $survey['id'] }}">{{ $survey['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="tgpremium"
                                class="col-md-4 col-form-label text-md-right">{{ 'Telegram Premium' }}</label>
                            <div class="col-md-6">
                                <label class="switch switch-3d switch-success">
                                    <input type="checkbox" class="switch-input" wire:model="tgpremium">
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-success text-dark"><i
                                    class="fa fa-paper-plane text-dark"></i> {{ __('Отправить') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white py-2">{{ 'Telegram синтаксис форматирования сообщений' }}
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><code>*</code><em>жирный текст</em><code>*</code></li>
                    <li><code>_</code><em>курсивный текст</em><code>_</code></li>
                    <li><code>`</code><em>моноширинный текст</em><code>`</code></li>
                    <li><code>[ссылка](http://example.com)</code></li>
                    <li><code>`inline code`</code></li>
                    <li><code>```code block```</code></li>
                    <li><code>&gt; цитата</code></li>
                    <li><code># хэштег</code></li>
                    <li><code>@username</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('#surveys_selected-dropdown').select2();
        $('#surveys_selected-dropdown').on('change', function(e) {
            let data = $(this).val();
            @this.set('surveys_selected', data);
        });

        $('#regions-dropdown').select2();
        $('#regions-dropdown').on('change', function(e) {
            let data = $(this).val();
            @this.set('regions', data);
        });

        window.livewire.on('broadcastMessage', () => {
            $('#surveys_selected-dropdown').select2();
            $('#regions-dropdown').select2();
        });
    });
</script>
