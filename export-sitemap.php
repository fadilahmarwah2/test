<?php
// prevent browser
if(PHP_SAPI !== 'cli'){ die; }
require 'constants.php';
require 'vendor/autoload.php';
require 'helpers.php';

//WAJIB CONFIG
$seo_path   = '';//Kosongi jika url artikel langsung dr root domain
$base       = "http://domain-target.com";

if(isset($argv[2]))
{
    $seo_path = ($argv[2] !== 'no') ? $argv[2]."/":"";
}

$base_url   = isset($argv[1]) ? $argv[1]."/{$seo_path}" : "{$base}/{$seo_path}";

$date 		= date('c',time());

$keywords 	= keywords();
$list 		= array_chunk($keywords,5000);

echo "\r\n ==> [\033[32mgenerating Sitemap\033[39m] <== \r\n\r\n";

$no = 0;

foreach ($list as $key => $sublist)
{
	$no++;

	$sitemap   = sitemap_wraper($sublist);

	$sn        = "submap_{$no}.xml";

	file_put_contents("export/{$sn}", $sitemap);

	echo "[\033[32msuccess\033[39m] ==> {$sn}\r\n";
}

function sitemap_wraper($arr_kw)
{    
    global $base_url;
    global $date;

    $dt ='';
    $dt .='<?xml version="1.0" encoding="utf-8"?>';
    $dt .='<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
    
    foreach ($arr_kw as $kw)
    {
        $path 		= rawurlencode(new_slug($kw));	
        $item_url   = "{$base_url}{$path}.html";
        $dt         .="<url><loc>{$item_url}</loc><lastmod>{$date}</lastmod></url>".PHP_EOL;
    }

    $dt .='</urlset>';
    return $dt;
}

echo "\r\n=> Sitemap Ready..\r\n\r\n";