<?php
include 'includes/config.php';
include 'includes/functions.php';

if(!isset($_GET['id'])) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

$sql = 'UPDATE images SET views = views + 1 WHERE id = ' . $id;
mysqli_query($DB, $sql);

$sql = 'SELECT * FROM images WHERE id = ' . $id;
$image = mysqli_query($DB, $sql);

if(!$image || $image->num_rows == 0) {
    session_start();
    $_SESSION['files_error'] = '<strong>Ошибка!</strong> Запрашиваемого изображения не существует.';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.php');
    exit();
}

$image = $image->fetch_object();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Просмотр файла <?=$image->filename?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h3 class="text-center">
        <a href="index.php">Назад</a>
    </h3>
    <h1 class="text-center">Файл <?=$image->filename?> просмотрен <?=$image->views?> <?php endWords($image->views, ['раз', 'раза', 'раз'], true) ?></h1>
    <img src="<?=$image->url?>" alt="" class="center-block img-responsive">
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>