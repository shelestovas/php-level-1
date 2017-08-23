<?php


$files = makeNormalArrayFiles($_FILES['files']);

if(count($files) < 1) {
    session_start();
    $_SESSION['files_error'] = '<strong>Ошибка!</strong> Не выбраны файлы для загрузки';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.php');
    exit();
}

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

$files = validateFiles($files);

$entries = array_diff(scandir('media/'), ['.', '..']);


$success_uploads = $error_uploads = [];

foreach($files['valid_success'] as $file) {
    $file_info = pathinfo($file['name']);
    $file_name = translitName($file_info['filename']);

    $file_name = checkFileName($file_name, $file_info['extension'], $entries);

    if(move_uploaded_file($file['tmp_name'], 'media/' . $file_name)) {
        $success_uploads[] = $file_name;
        //createThumbnail('media/' . $file_name, true, $image_sizes['thumbnail']['width'], $image_sizes['thumbnail']['height'], $file_name);
        createThumbnail('media/' . $file_name, true, $image_sizes, $file_name);
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



/**
 * Функция обрезки изображения
 *
 * @param $path
 * @param $save
 * @param $width
 * @param $height
 * @return bool
 */
//function createThumbnail($path, $save, $width, $height, $filename) {
function createThumbnail($path, $save, $image_sizes, $filename) {
    $info = getimagesize($path); //получаем размеры картинки и ее тип
    $size = array($info[0], $info[1]); //закидываем размеры в массив

    foreach($image_sizes as $key => $sizes) {
        $width = $sizes['width'];
        $height = $sizes['height'];
//В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($path); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($path);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($path);
        } else {
            //return false;
            continue;
        }
        $thumb = imagecreatetruecolor($width, $height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера

        if ($info['mime'] == 'image/png') {
            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
            imagesavealpha($thumb, TRUE);
        }

        $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника
        $thumb_aspect = $width / $height; //отношение ширины к высоте аватарки
        if ($src_aspect < $thumb_aspect) { //узкий вариант (фиксированная ширина) $scale = $width / $size[0]; $new_size = array($width, $width / $src_aspect); $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2); //Ищем расстояние по высоте от края картинки до начала картины после обрезки } else if ($src_aspect > $thumb_aspect) {
//широкий вариант (фиксированная высота)
            $scale = $height / $size[1];
            $new_size = array($height * $src_aspect, $height);
            $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0); //Ищем расстояние по ширине от края картинки до начала картины после обрезки
        } else {
//другое
            $new_size = array($width, $height);
            $src_pos = array(0, 0);
        }
        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);
//Копирование и изменение размера изображения с ресемплированием

        $cropped_filename = pathinfo($filename);
        $new_filename = 'media/' . $width . 'x' . $height . '-' . $cropped_filename['filename'] . '.' . $cropped_filename['extension'];

        if ($save === false) {
            //return imagepng($thumb);
            imagepng($thumb);
        } else {
            //return imagepng($thumb, $new_filename);//Сохраняет JPEG/PNG/GIF изображение
            imagepng($thumb, $new_filename);
        }
    }
}

/**
 * Проверка на совпадение имени файла с уже загружеными файлами
 *
 * @param $file_name
 * @param $extension
 * @param $entries
 * @return string
 */
function checkFileName($file_name, $extension, $entries) {
    $original = $file_name;

    $i = 1;
    if(!in_array($file_name . '.' . $extension, $entries)) {
        return $file_name . '.' . $extension;
    } else {
        while (in_array($file_name . '.' . $extension, $entries)) {
            $file_name = $original . '-' . $i;

            if (!in_array($file_name . '.' . $extension, $entries))
                return $file_name . '.' . $extension;
            $i++;
        }
    }
}

/**
 * Проверяем файлы на соответствие типа и максимального размера
 *
 * @param $files
 * @return array
 */
function validateFiles($files) {
    $type = 'image';
    $valid_error = $valid_success = [];

    foreach($files as $file) {
        $file_type = explode('/', $file['type']);
        if(($type != $file_type[0]) || ($file['size'] > 1048576)) {
            $valid_error[] = $file;
        } else {
            $valid_success[] = $file;
        }
    }

    return [
        'valid_success' => $valid_success,
        'valid_error' => $valid_error
    ];
}

/**
 * Приводим стандартный массив с переданными данными в нормальный читаемый вид
 *
 * @param $files
 * @return array
 */
function makeNormalArrayFiles($files) {
    $result = [];
    for($i = 0; $i < count($files['name']); $i++) {
        if($files['error'][$i] == 0) {
            foreach ($files as $key => $data) {
                $result[$i][$key] = $data[$i];
            }
        }
    }
    return $result;
}

/**
 * Переводим названия файлов в латиницу
 *
 * @param $name
 * @return string
 */
function translitName($name) {
    $array = [
        'default' => [
            "а" => "a", "А" => "A",
            "б" => "b", "Б" => "B",
            "в" => "v", "В" => "V",
            "г" => "g", "Г" => "G",
            "д" => "d", "Д" => "D",
            "е" => "e", "Е" => "E",
            "ё" => "e", "Ё" => "E",
            "ж" => "j", "Ж" => "J",
            "з" => "z", "З" => "Z",
            "и" => "i", "И" => "I",
            "й" => "y", "Й" => "Y",
            "к" => "k", "К" => "K",
            "л" => "l", "Л" => "L",
            "м" => "m", "М" => "M",
            "н" => "n", "Н" => "N",
            "о" => "o", "О" => "O",
            "п" => "p", "П" => "P",
            "р" => "r", "Р" => "R",
            "с" => "s", "С" => "S",
            "т" => "t", "Т" => "T",
            "у" => "u", "У" => "U",
            "ф" => "f", "Ф" => "F",
            "х" => "h", "Х" => "H",
            "ц" => "ts", "Ц" => "Ts",
            "ч" => "ch", "Ч" => "Ch",
            "ш" => "sh", "Ш" => "Sh",
            "щ" => "sch", "Щ" => "Sch",
            "ъ" => "y", "Ъ" => "",
            "ы" => "yi", "Ы" => "Yi",
            "ь" => '', "Ь" => '',
            "э" => "e", "Э" => "E",
            "ю" => "yu", "Ю" => "Yu",
            "я" => "ya", "Я" => "Ya",
        ],
        'url' => [
            " " => '-',
            ',' => '', '.' => '',
            '`' => '', "'" => '',
            '"' => '', '_' => '',
            '/' => '', '\\' => '',
            '<' => '', '>' => '',
            '!' => '', '?' => '',
            '|' => '',
            '{' => '', '}' => '',
            '(' => '', ')' => '',
            '^' => '', '#' => '',
            '@' => '', '%' => '',
            '*' => '', '№' => '',
            ':' => '', ';' => ''
        ]
    ];
    return strtolower(strtr($name, array_merge($array['default'], $array['url'])));
}
