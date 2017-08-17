<?php
/**
 * Реализовать основные 4 арифметические операции в виде функций с двумя параметрами. Обязательно использовать оператор return.
 */

$a = rand(0, 100);
$b = rand(0, 100);
$operation_lists = ['+', '-', '/', '*'];
$operation = $operation_lists[rand(0, 3)];

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