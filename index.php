<?php
require 'constants.php';
require 'vendor/autoload.php';
require 'helpers.php';

//error_reporting(0);

$seo 		= SEO_PATH;
$seo_path 	= $seo ? "/{$seo}":"";

Flight::route('/', function(){
	if(isset($_GET['nerd'])){
		echo home_url() . random_post();
		die;
	}

    view('home');
});

Flight::route('/p/@page.html', function($page){
    view('pages.page', ['page' => $page]);
});

Flight::route('/@slug.jpg', function($slug){
	$data = get_data($slug);

	return Flight::redirect(collect($data['images'])->random()['url']);
});

Flight::route($seo_path.'/@slug.html', function($slug){
	$data = get_data($slug);

	if($data === false){
		return Flight::redirect(random_post());
	}
	
	$data['keyword'] = str_replace('-', ' ', $slug);

    view('image', $data);
});


Flight::route('/search', function(){
	$q = $_GET['q']??"";

	if(!$q){
		return Flight::redirect(home_url());
	}
	
	$data['list'] 	= search($q);
	$data['query'] 	= $q;

    view('search', $data);
});

Flight::route('/sitemap.xml', function()
{
	$data['arr_sub']= main_sitemap();
    sitemap('sitemap.sitemap_index', $data);
});

Flight::route('/sub/@submap.xml', function($submap)
{
	$data['arr_kw']	= sub_sitemap($submap);

    sitemap('sitemap.sitemap_sub', $data);
});

Flight::route('/rss/@rss.xml', function($rss)
{
	if(!$rss){die;}

	$q = ($rss == 'default')?'':$rss;

	$q = str_replace('-', ' ', $q);

	$data['list'] 	= search($q);
	$data['query'] 	= $q;

    sitemap('sitemap.rss', $data);
});

Flight::route('/dwn/@index/@keyword.jpg', function($index,$keyword)
{
	image_view($keyword,$index);
});

Flight::map('notFound', function(){
    // Display custom 404 page
    return Flight::redirect('/');
});



Flight::start();