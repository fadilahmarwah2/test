<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }

require 'vendor/autoload.php';
require 'helpers.php';

echo "=> Shuriken image downloader\n";

foreach (keywords() as $keyword) {
	$slug = new_slug($keyword);
	$data = get_data($slug);

	$data['keyword'] = str_replace('-', ' ', $slug);

    echo "\r\n==> downloading images for {$data['keyword']}";
    $images = [];

    foreach ($data['images'] as $key => $image) {
		
		if($key == 9)//Jumlah limit gambar per keyword
		{
			break;
		}
		
    	$ext = explode('.', $image['url']);
    	$ext = array_pop($ext);

        $ext = strtolower($ext);

        if(in_array($ext, ['png', 'webp', 'svg', 'bmp', 'gif'])){
            continue;
        }

    	if(!in_array($ext, ['jpg', 'jpeg'])){
    		$ext = 'jpg';
    	}

        /*if(!in_array($ext, ['jpg', 'png', 'webp', 'svg', 'bmp'])){
            $ext = 'jpg';
        }*/

    	$filename = get_filename($image['keyword']);

    	$filename = str_replace(['.srz.php','data/','-'],[ '-' . $key . '.' . $ext,'export/local/',' '], $filename);

    	$content = @file_get_contents($image['url']);

    	if(is_null($content)){
    		continue;
    	}	

    	file_put_contents($filename, $content);
    	echo ".";
    }
}
