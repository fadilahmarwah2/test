<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }
require 'constants.php';
require 'vendor/autoload.php';
require 'helpers.php';

echo "=> generating xml for wp\n";

$list_file = keywords();

shuffle($list_file);

$split = ARTICLE_PER_XML;

$list = array_chunk($list_file,$split);

foreach ($list as $key => $sublist)
{
	$pno = $key + 1;

	file_put_contents("export/wp{$pno}.xml", export('export.wp', [
	'keywords' => $sublist,
	'argv' => $argv
	], false));

	echo "\r\n[\033[32msuccess\033[39m] ==> {$pno}\r\n";
}