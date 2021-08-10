<?php

function parseBook() {
    $dir = '../img/';
    $f_json = 'https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json';
    $json = file_get_contents("$f_json");
    $obj = json_decode($json,true);
    $imgList = scandir($dir);
    foreach ($obj as $num => $data) {
        if (empty($data['categories'])) {
            $data['categories'] = ['new'];
        }
        if (array_key_exists('thumbnailUrl',$data) && array_key_exists('isbn',$data)) {
            $imgUrl = $data['thumbnailUrl'];
            $id = $data['isbn'].'.png';
            if (!in_array($id, $imgList)) {
                getImage($dir.$id,$imgUrl);
            }
        }
    }
}

function getImage($fileName, $source) {
    file_put_contents($fileName, file_get_contents($source));
}

parseBook();
