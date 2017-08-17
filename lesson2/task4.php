<?php
/**
 * Реализовать функцию с тремя параметрами: function mathOperation($arg1, $arg2, $operation), где $arg1, $arg2 – значения аргументов,
 * $operation – строка с названием операции. В зависимости от переданного значения операции выполнить одну из
 * арифметических операций (использовать функции из пункта 3) и вернуть полученное значение (использовать switch).
 */


$a = rand(0, 100);
$b = rand(0, 100);
$operation_lists = ['+', '-', '/', '*'];
$operation = $operation_lists[rand(0, 3)];

mathOperation($a, $b, $operation);

function mathOperation($a, $b, $operation) {
    switch($operation) {
        case '+':
            $result = sum($a, $b);
            break;
        case '-':
            $result = diff($a, $b);
            break;
        case '/':
            $result = div($a, $b);
            break;
        case '*':
            $result = mult($a, $b);
            break;
        default:
            $result = 'Неверная операция';
            break;
    }
    echo '$a = ' . $a . '<br />';
    echo '$b = ' . $b . '<br />';
    echo 'Операция: ' . $operation . '<br />';
    echo $result;
}

function sum($a, $b) {
    return 'Результат операции: ' + ($a + $b);
}
function diff($a, $b) {
    return 'Результат операции: ' + ($a - $b);
}
function div($a, $b) {
    if($b == 0)
        return 'Делить на ноль нельзя!!!';
    return 'Результат операции: ' + ($a / $b);
}
function mult($a, $b) {
    return 'Результат операции: ' + ($a * $b);
}