<?php
include 'includes/config.php';
include 'includes/functions.php';

$files = makeNormalArrayFiles($_FILES['files']);

if(count($files) < 1) {
    session_start();
    $_SESSION['files_error'] = '<strong>Ошибка!</strong> Не выбраны файлы для загрузки';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.php');
    exit();
}

$files = validateFiles($files);

$entries = array_diff(scandir('media/'), ['.', '..']);


$success_uploads = $error_uploads = [];

foreach($files['valid_success'] as $file) {
    $file_info = pathinfo($file['name']);
    $file_name = translitName($file_info['filename']);

    $file_name = checkFileName($file_name, $file_info['extension'], $entries);

    $file_url = 'media/' . $file_name;

    if(move_uploaded_file($file['tmp_name'], $file_url)) {
        $success_uploads[] = $file_name;

        $sql = "INSERT INTO images (url, filename) VALUES ('$file_url', '$file_name');";
        $upload = mysqli_query($DB, $sql);

        createThumbnail($file_url, true, IMAGE_SIZES, $file_name);
    } else {
        $error_uploads[] = $file;
    }
}
session_start();
$_SESSION['result_uploads'] = [
    'success_uploads' => $success_uploads,
    'error_uploads' => $error_uploads,
    'valid_error' => $files['valid_error']
];
header('HTTP/1.1 301 Moved Permanently');
header('Location: index.php');
exit();