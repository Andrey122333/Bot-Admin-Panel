<div>
    <style>
        li {
            list-style-type: none;
        }

        .text {
            float: left;
            width: 180px;
        }
    </style>

    <div class="mb-3">
        <select class="form-control" id="selectvalue" wire:model="selectedNesting">
            @if (count($nestings) == 0)
                <option>none</option>
            @else
                @php
                    $message_nestings = [];
                    $keyboard_nestings = [];
                    
                    foreach ($nestings as $nesting) {
                        if ($nesting['keyboard_button'] == 0) {
                            $message_nestings[] = $nesting['nesting_down'];
                        } elseif ($nesting['keyboard_button'] == 1) {
                            $keyboard_nestings[] = $nesting['nesting_down'];
                        }
                    }
                @endphp

                @if (!empty($message_nestings))
                    <optgroup label="Меню в сообщении">
                        @foreach ($message_nestings as $nesting)
                            <option>{{ $nesting }}</option>
                        @endforeach
                    </optgroup>
                @endif

                @if (!empty($keyboard_nestings))
                    <optgroup label="Клавиатурное меню">
                        @foreach ($keyboard_nestings as $nesting)
                            <option>{{ $nesting }}</option>
                        @endforeach
                    </optgroup>
                @endif
            @endif
        </select>

    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" wire:click="SaveKeyboard" wire:model="selectedCheck" type="checkbox"
                id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Клавиатурное меню
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Сообщение для меню:</label>
        <textarea class="form-control" wire:change="changeMessage" wire:model="settings.message"
            id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <div class="table-responsive-xl">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Текст</th>
                        <th scope="col">Тип&nbsp;действия</th>
                        <th scope="col">Действие</th>
                        <th scope="col">Выбор&nbsp;класса</th>
                        <th scope="col">
                            @if ($selectedCheck)
                                Меню в сообщении @elseКлавиатурное меню
                            @endif
                        </th>
                        <th scope="col" width="100">По&nbsp;вертикали</th>
                        <th scope="col" width="100">По&nbsp;горизонтали</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buttons as $index => $button)
                        <tr wire:key="button-field-{{ $button->id }}">
                            <th scope="row">{{ $index }}</th>
                            <td><input wire:model="buttons.{{ $index }}.name" type="text" class="form-control"
                                    placeholder="Текст"></td>
                            <td>
                                <select wire:model="buttons.{{ $index }}.type" class="form-control">
                                    <option value="Nesting">Вложенное меню</option>
                                    <option value="Survey">Опрос</option>
                                    <option value="Geo">Геометка</option>
                                </select>
                            </td>
                            <td>
                                @if ($button->type == 'Nesting')
                                    <div wire:ignore>
                                        <select style="width: 100%;" myindex="{{ $index }}"
                                            class="select2 nesting">
                                            <option>{{ $button->nesting_down }}</option>
                                            @if ($button->nesting_down != '')
                                                <option></option>
                                            @endif
                                            @if ($selectedCheck)
                                                @foreach ($nestings as $nesting)
                                                    @if ($nesting['keyboard_button'] == 1 && $nesting['nesting_down'] != $button->nesting_down)
                                                        <option>{{ $nesting['nesting_down'] }}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($nestings as $nesting)
                                                    @if ($nesting['keyboard_button'] == 0 && $nesting['nesting_down'] != $button->nesting_down)
                                                        <option>{{ $nesting['nesting_down'] }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @else
                                    <input wire:model="buttons.{{ $index }}.nesting_down" type="text"
                                        class="form-control" placeholder="Действие">
                                @endif
                            </td>
                            <td>
                                <div wire:ignore><select style="width: 100%;" myindex="{{ $index }}"
                                        class="select2 class">
                                        <option>{{ $button->class }}</option>
                                        @if ($button->class != '')
                                            <option></option>
                                        @endif
                                        @foreach ($allClass as $myClass)
                                            @if (basename($myClass, '.php') != $button->class)
                                                <option>{{ basename($myClass, '.php') }}</option>
                                            @endif
                                        @endforeach
                                    </select></div>
                            </td>

                            <td>
                                <div wire:ignore>
                                    <select style="width: 100%;" wire:model.lazy="keyboard" class="select2 keyboard">
                                        <option>{{ $button->keyboard }}</option>
                                        @if ($button->keyboard != '')
                                            <option></option>
                                        @endif
                                        @foreach ($nestings as $nesting)
                                            @if ($nesting['nesting_down'] != $button->keyboard)
                                                @if ($selectedCheck && !$nesting['keyboard_button'])
                                                    <option>{{ $nesting['nesting_down'] }}</option>
                                                @elseif (!$selectedCheck && $nesting['keyboard_button'])
                                                    <option>{{ $nesting['nesting_down'] }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                            </td>
                            <td><input wire:model="buttons.{{ $index }}.vertical" type="text"
                                    class="form-control" placeholder="По вертикали"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </td>
                            <td><input wire:model="buttons.{{ $index }}.horizontal" type="text"
                                    class="form-control" placeholder="По горизонтали"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </td>
                            <td>
                                <button title="Delete" class="btn btn-sm btn-danger"
                                    wire:click="delete({{ $button->id }})"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" wire:click="addButton">
                                    <i class="fa fa-plus"></i>&nbsp;Создать кнопку
                                </button>
                                <button class="btn btn-success text-dark" wire:click="save">
                                    <i class="fa fa-save text-dark"></i>&nbsp;Сохранить
                                </button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        // Initialize select2
        $('.select2').select2({
            tags: true
        });

        // Bind change events to the relevant elements
        $('.nesting, .keyboard, .class').on('change', function(e) {
            let item = $(this).select2("val");
            let index = $(this).attr("myindex");
            console.log(index);
            let buttonProp;
            if ($(this).hasClass('nesting')) {
                buttonProp = 'nesting_down';
            } else if ($(this).hasClass('keyboard')) {
                buttonProp = 'keyboard';
            } else if ($(this).hasClass('class')) {
                buttonProp = 'class';
            }
            if (buttonProp) {
                @this.set(`buttons.${index}.${buttonProp}`, item);
            }
        });
    });

    document.addEventListener('livewire:update', function() {
        $('.select2').select2({
            tags: true
        });
    });
</script>
