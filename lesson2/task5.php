<?php
/**
 *  Посмотреть на встроенные функции PHP. Используя имеющийся HTML шаблон, вывести текущий год в подвале при помощи встроенных функций PHP.
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        header, article, footer {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        header {
            background-color: aquamarine;
            height: 100px;
        }

        section {
            background-color: khaki;
            padding: 40px 50px;
        }

        article {
            background-color: palegreen;
            height: 150px;
            margin: 10px 0;
        }
        footer {
            height: 100px;
            background-color: thistle;
        }
    </style>
</head>
<body>

<header>
    Шапка сайта
</header>

<section>
    <article>
        Статья 1
    </article>
    <article>
        Статья 2
    </article>
</section>

<footer>
    © <?=date('Y')?> Все права защищены!
</footer>

</body>
</html>
