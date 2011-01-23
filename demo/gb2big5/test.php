<?php

include("./gbtobig5.php");

$convert = new gbTobig5();
$data = file_get_contents("http://shafa.sinaapp.com/cmd/star_list");
$big5 = "";
for ($i=0; $i < mb_strlen($data, 'utf-8'); $i++) {
    $one = mb_substr($data, $i, 1 , 'utf-8');
    $word = $convert->gb2big5($one);
    if (!empty($word))
        $big5 .= $word;
    else
        $big5 .= $one;
}

echo $big5;

