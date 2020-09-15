<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }

require 'vendor/autoload.php';
require 'helpers.php';

foreach (glob('sitemap/*.txt') as $file) {
	unlink($file);
}

$keywords = keywords();

$list = array_chunk($keywords,150);

echo "\r\n ==> [\033[32mgenerating Sitemap\033[39m] <== \r\n\r\n";

$no = 0;

foreach ($list as $key => $sublist)
{
	$no++;

	$list = implode("\r\n", $sublist);

	$sitemap_name = "submap_{$no}.txt";

	file_put_contents("sitemap/{$sitemap_name}", $list);

	echo "[\033[32msuccess\033[39m] ==> {$sitemap_name}\r\n";
}

echo "\r\n=> Sitemap Ready..\r\n\r\n";