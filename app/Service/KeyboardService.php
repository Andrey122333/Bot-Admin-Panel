<?php

namespace App\Service;

class KeyboardService
{
    public function keyboardGeneration($buttons)
    {
        $keyboard = [];
        // Группировка кнопок по вертикали
        foreach ($buttons->groupBy('vertical') as $verticalButtons) {
            $row = [];
            // Создание ряда кнопок
            foreach ($verticalButtons as $button) {
                // Создание кнопки
                $row[] = [
                    'text' => $button['name'],
                    'callback_data' => $button['id'],
                ];
            }
            // Добавление ряда кнопок в клавиатуру
            $keyboard[] = $row;
        }

        // Возврат клавиатуры в виде JSON
        return json_encode([
            'inline_keyboard' => $keyboard,
        ]);
    }

    public function keyboardButtonGeneration($buttons)
    {
        $keyboard = [];
        // Группировка кнопок по вертикали
        foreach ($buttons->groupBy('vertical') as $verticalButtons) {
            $row = [];
            // Создание ряда кнопок
            foreach ($verticalButtons as $button) {
                // Создание кнопки
                $row[] = [
                    'text' => $button['name'],
                ];
            }
            // Добавление ряда кнопок в клавиатуру
            $keyboard[] = $row;
        }

        // Возврат клавиатуры в виде JSON
        return json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ]);
    }


}
