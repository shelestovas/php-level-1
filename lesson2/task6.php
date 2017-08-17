<?php
/**
 * *С помощью рекурсии организовать функцию возведения числа в степень. Формат: function power($val, $pow), где $val – заданное число, $pow – степень.
 */

$val = rand(0, 300);
$pow = rand(0, 15);

echo "$val<sup>$pow</sup> = " . power($val, $pow);

function power($val, $pow) {
    if($pow != 0) {
        return $val * power($val, --$pow);
    }
    return 1;
}