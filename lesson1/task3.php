<?php

$a = 5;
$b = '05';
var_dump($a == $b);         // Почему true? - строка преобразуется в integer как первое, ведущие нули удаляются.
var_dump((int)'012345');     // Почему 12345? - ведущие нули удаляются, в числах в них нет смысла.
var_dump((float)123.0 === (int)123.0); // Почему false? - разные типы данных
var_dump((int)0 === (int)'hello, world'); // Почему true? - текст пытается преобразоваться в число, но это невозмжно, поэтому результат преобразования будет 0.