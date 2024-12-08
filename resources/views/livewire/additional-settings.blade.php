<div>
    <div class="mb-3">
        <label class="form-text --bs-emphasis-color" for="telegram_applications_count">Число заявок для отображения в боте:</label>
        <input name="telegram_applications_count" wire:model.lazy="settings.telegram_applications_count" class="form-control" type="number"
            type="number" placeholder="Число заявок для отображения в боте">
    </div>
    <div class="mb-3">
        <label class="form-text --bs-emphasis-color" for="name">Количество заявок на страницу:</label>
        <input name="web_applications_per_page" wire:model.lazy="settings.web_applications_per_page" class="form-control"
            type="number" placeholder="Введите количество заявок, которое необходимо отображать на одной странице">
    </div>
    <div class="mb-3">
        <label class="form-text --bs-emphasis-color" for="name">Сообщение о технических работах:</label>
        <textarea id="telegram_technical_work_message" name="telegram_technical_work_message" wire:model.lazy="settings.telegram_technical_work_message"
            class="form-control" rows="4"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-text --bs-emphasis-color" for="telegram_locked_account_button_text">Текст на кнопке в меню клавиатуры при блокировке
            учетной записи:</label>
        <input name="telegram_locked_account_button_text" wire:model.lazy="settings.telegram_locked_account_button_text"
            class="form-control" type="text"
            placeholder="Введите текст, который пользователь увидит при блокировке своей учетной записи">
    </div>
    <div class="mb-3">
        <label class="form-text --bs-emphasis-color" for="name">Причина блокировки аккаунта по умолчанию:</label>
        <textarea id="telegram_default_locked_account_reason" name="telegram_default_locked_account_reason"
            wire:model.lazy="settings.telegram_default_locked_account_reason" class="form-control" rows="4" maxlength="4096"></textarea>
    </div>
    <button wire:click="save" class="btn btn-success text-dark"><i class="fa fa-save text-dark"></i>&nbsp;Сохранить</button>
</div>
