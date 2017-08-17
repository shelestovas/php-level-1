<?php
/**
 * *Написать функцию, которая вычисляет текущее время и возвращает его в формате с правильными склонениями, например:
 * 22 часа 15 минут
 * 21 час 43 минуты
 */

$hour = date('H');
$minute = date('i');

$hour_text = ['час', 'часа', 'часов'];
$minute_text = ['минута', 'минуты', 'минут'];

echo $hour . ' ' . endWords($hour, $hour_text) . ' ' . $minute . ' ' . endWords($minute, $minute_text);

function endWords($num, $words){
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }
    switch ($num) {
        case 1: {
            return($words[0]);
        }
        case 2: case 3: case 4: {
        return($words[1]);
    }
        default: {
            return($words[2]);
        }
    }
}