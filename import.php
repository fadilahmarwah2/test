<?php
require 'vendor/autoload.php';
require 'helpers.php';

use Buchin\GoogleSuggest\GoogleSuggest;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;


if(!isset($argv[1])){
	echo "Please specify keyword: php import.php \"keyword1,keyword2,keyword3\"\n";
	die;
}


echo "=> Gathering initial keywords\n";

$keywords = explode(',', $argv[1]);

foreach ($keywords as $keyword) {
	foreach (range('a', 'z') as $char) {
		$keywords = array_unique(array_merge($keywords, (array)@GoogleSuggest::grab($keyword . ' ' . $char)));

		echo '.';
	}

	sleep(rand(1,5));
}

echo "\n
=================================" . '
Importing: ' . $argv[1] . "
=================================\n\n";

$count = 1;

do {
	try {
		$keyword = array_shift($keywords);


		echo '==> scraping #' . $count . ': ' . str_slug($keyword) . "...\n";

		$data = [
			'related' => [],
			'images' => [],
		];

		$related = (array)@GoogleSuggest::grab($keyword);

		if(!empty($related)){
			$new_keywords = [];

			foreach ($related  as $r) {
				if(!data_exists($r) && $r !== $keyword){
					$new_keywords[] = $r;
				}
			}

			$keywords = array_merge($keywords, $new_keywords);

			$data['related'] = $related;
		}

		$images = (array)@GoogleImageGrabber::grab($keyword);

		if(!empty($images)){
			$data['images'] = $images;

			file_put_contents(get_filename($keyword), serialize($data));
		}

	} catch (\Exception $e) {
		echo '===>' . $e->getMessage() . "\n";
		sleep(rand(5, 60));
	}

	sleep(1);
	$count++;

} while (!empty($keywords));