<?php
$title = 'Заголовок вкладки браузера';
$h1 = 'Заголвоок h1';
$date = date('Y');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title ?></title>
</head>
<body>
    <h1><?php echo $h1 ?></h1>
    Текущий год: <?php echo $date ?>
</body>
</html>