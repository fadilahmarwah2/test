<?php
require 'vendor/autoload.php';
require 'helpers.php';

Flight::route('/', function(){
    view('home');
});

Flight::route('/pages/@page', function($page){
    view('pages.page', ['page' => $page]);
});

Flight::route('/@slug.html', function($slug){
	$data = get_data($slug);

	if($data === false){
		return Flight::redirect(random_post());
	}

	$data['keyword'] = str_replace('-', ' ', $slug);

    view('image', $data);
});

Flight::start();