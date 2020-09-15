<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }
require 'constants.php';
require 'vendor/autoload.php';
require 'helpers.php';

echo "=> generating html export\n";

file_put_contents('export/html/index.html', view('home', [],false));

foreach (keywords() as $keyword)
{
	$slug = new_slug($keyword);
	$data = get_data($slug);

	$data['keyword'] = str_replace('-', ' ', $slug);
    
	file_put_contents('export/html/' . $slug . '.html', view('image', $data, false));

	echo "\r\n[\033[32msuccess\033[39m] ==> {$slug}.html\r\n";
}

if (!file_exists('export/html/p'))
{
    mkdir('export/html/p', 0777, true);
    sleep(1);
}

foreach (pages() as $page_name)
{
	file_put_contents("export/html/p/{$page_name}.html", view('pages.page', ['page' => $page_name],false));

	echo "\r\n[\033[32msuccess\033[39m] ==> {$page_name}.html\r\n";
}
