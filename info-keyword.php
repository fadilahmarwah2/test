<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }

require 'vendor/autoload.php';
require 'helpers.php';

$keywords = keywords();

$list = implode("\r\n", $keywords);

$file = "./info/keyword.txt";

file_put_contents($file, $list);

echo "\r\n=> Keyword Ready..\r\n\r\n";