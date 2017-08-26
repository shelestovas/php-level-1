<?php
include 'includes/config.php';
include 'includes/functions.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Галерея</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h1 class="text-center">
        Галерея
    </h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post" action="upload.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file-label">Загрузка файлов</label>
                    <input type="file" id="file-label" name="files[]" multiple>
                    <p class="help-block">Только изображения и максимальный размер файла не больше 1 мб.</p>
                </div>
                <button type="submit" class="btn btn-default">Загрузить</button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['files_error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?=$_SESSION['files_error']?>
        </div>
        <?php unset($_SESSION['files_error']) ?>
    <?php } ?>

    <?php if (isset($_SESSION['result_uploads'])) { ?>
        <?php if(count($_SESSION['result_uploads']['success_uploads']) > 0) { ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Следующие файлы были успешно загружены:
                <ul>
                    <?php foreach($_SESSION['result_uploads']['success_uploads'] as $item) { ?>
                        <li><?=$item?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if(count($_SESSION['result_uploads']['error_uploads']) > 0) { ?>
            <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Следующие файлы по непонятным причинам загрузить не удалось:
                <ul>
                    <?php foreach($_SESSION['result_uploads']['error_uploads'] as $item) { ?>
                        <li><?=$item['name']?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if(count($_SESSION['result_uploads']['valid_error']) > 0) { ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Следующие файлы не прошли валидацию, т.е. не являются изображениями либо размер файла больше 1 мб:
                <ul>
                    <?php foreach($_SESSION['result_uploads']['valid_error'] as $item) { ?>
                        <li><?=$item['name']?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php unset($_SESSION['result_uploads']) ?>
    <?php } ?>

    <?php
    $sql = 'SELECT * FROM images ORDER BY views DESC';
    $images = mysqli_query($DB, $sql);
    ?>

    <?php if($images->num_rows > 0) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <?php while($image = mysqli_fetch_object($images)) { ?>
                    <div class="media-item-pt">
                        <a href="show_image.php?id=<?=$image->id?>" class="thumbnail">
                            <img src="<?php getImageBySize($image->filename, IMAGE_SIZES['thumbnail'], true) ?>" alt="" class="img-responsive center-block">
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<style>
    .media-item-pt {
        float: left;
        padding: 10px;
    }
    .media-item-pt .thumbnail {
        position: relative;
        margin-bottom: 0;
    }
    .media-item-pt img {
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
    }
    .media-item-pt .thumbnail:before {
        content: '';
        display: block;
        padding-top: 100%;
    }

    @media (min-width: 1200px) {
        .media-item-pt {
            width: 12.5%;
        }
    }
    @media (min-width: 992px) and (max-width: 1199px) {
        .media-item-pt {
            width: 16.666%;
        }
    }
    @media (min-width: 769px) and (max-width: 991px) {
        .media-item-pt {
            width: 20%;
        }
    }
    @media (min-width: 530px) and (max-width: 768px) {
        .media-item-pt {
            width: 25%;
        }
    }
    @media (max-width: 529px) {
        .media-item-pt {
            width: 33.3333%;
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>