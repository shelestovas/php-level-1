<?php

ini_set('display_errors', 0);

const IMAGE_SIZES = [
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

const DATABASE = [
    'host' => 'localhost',
    'database' => 'php1_gallery',
    'username' => 'root',
    'password' => ''
];

$DB = mysqli_connect(
    DATABASE['host'],
    DATABASE['username'],
    DATABASE['password'],
    DATABASE['database']
) or die('<h1>Ошибка соединения с базой</h1>');