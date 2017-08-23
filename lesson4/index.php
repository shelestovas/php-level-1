<?php
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

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
?>

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

    $image_sizes = [
        'thumbnail' => [
            'width' => 150,
            'height' => 150
        ],
        'catalog' => [
            'width' => 200,
            'height' => 200
        ],
        'post_thumbnail' => [
            'width' => 100,
            'height' => 50
        ]
    ];

    $entries = customScanDir('media/', $image_sizes);

    /**
     * Формируем массив с сгенерированными файлами
     *
     * @param $path
     * @param $image_sizes
     * @return array
     */
    function customScanDir($path, $image_sizes){
        $entries = array_diff(scandir($path), ['.', '..']);

        $sizes = [];
        $new_entries = [];

        foreach($image_sizes as $key => $param) {
            $sizes[$key] = $param['width'] . 'x' . $param['height'] . '-';
        }

        foreach($entries as $item) {
            foreach($sizes as $key => $size) {
                if (substr($item, 0, strlen($size)) == $size) {
                    continue 2;
                }
            }
            $new_entries[]['url'] = $item;
        }

        foreach($new_entries as $i => $file) {
            foreach($sizes as $key => $size) {
                if (file_exists('media/' . $size . $file['url'])) {
                    $new_entries[$i][$key] = $size . $file['url'];
                }
            }
        }

        return $new_entries;
    }
    ?>

    <?php if(count($entries) > 0) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <?php foreach($entries as $item) { ?>
                    <div class="media-item-pt">
                        <a href="media/<?=$item['url']?>" target="_blank" class="thumbnail">
                            <img src="media/<?=(isset($item['thumbnail']) ? $item['thumbnail'] : $item['url'])?>" alt="" class="img-responsive center-block">
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