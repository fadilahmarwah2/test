<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }
require 'constants.php';
require 'vendor/autoload.php';
require 'helpers.php';

use Buchin\GoogleSuggest\GoogleSuggest;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use Buchin\Badwords\Badwords;

if(!isset($argv[1])){
	echo "Please specify keyword: php import.php \"keyword1,keyword2,keyword3\"\n";
	die;
}


echo "=> Gathering initial keywords\n";

$keywords = explode(',', $argv[1]);
$lang = isset($argv[2]) ? $argv[2] : '';
$country = isset($argv[3]) ? $argv[3] : '';
$max = isset($argv[4]) ? $argv[4] : PHP_INT_MAX;
$source = 'i';

$scrap_mode = CONTENT_MODE;

foreach ($keywords as $key => $keyword) {
	if(Badwords::isDirty($keyword)){
		unset($keywords[$key]);
	}

	foreach (range('a', 'z') as $char) {
		$init = (array)@GoogleSuggest::grab($keyword . ' ' . $char, $lang, $country, $source);
		foreach ($init as $kw) {
			if(!Badwords::isDirty($kw)){
				$keywords[] = $kw;
			}
		}

		$keywords = array_unique($keywords);

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
		if($count > $max){
			echo "Import finished. Congratulations!\n";
			die;
		}

		$keyword = array_shift($keywords);


		if(Badwords::isDirty($keyword))
		{
			echo "==> [BADWORD] : {$keyword}...\n";
			continue;
		}
		else
		{
			$slug = new_slug($keyword);

			echo "==> scraping #{$count}: {$slug}...\n";
		}

		$data = [
			'related' => [],
			'images' => [],
			'sentences' => [],
		];

		if($scrap_mode !== 'IMAGE_ONLY')
		{
			$sentences = (array)@get_sentences($keyword);
			$data['sentences'] = $sentences;
		}

		$related = (array)@GoogleSuggest::grab($keyword, $lang, $country, $source);

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


		if(!empty($images))
		{
			$data['images'] = $images;

			if($scrap_mode == 'IMAGE_ARTICLE')
			{
				if(!empty($data['sentences']))
				{
					file_put_contents(get_filename($keyword), serialize($data));
				}
			}
			else
			{
				file_put_contents(get_filename($keyword), serialize($data));
			}
		}

		echo "==> Finishing..\r\n";
		sleep(BREAK_TIME);

	} catch (\Exception $e) {
		echo '===>' . $e->getMessage() . "\n";
		sleep(rand(5, 60));
	}

	sleep(1);
	$count++;

} while (!empty($keywords));