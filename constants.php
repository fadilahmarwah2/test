<?php
/*
| -------------------------------------------------------------------
| COSTUME CONSTANS
| -------------------------------------------------------------------
*/

define('SITE_NAME',			'Sprüche iMages');//Costum brand

define('THEME_NAME',		'three');//default or three
define('SEO_PATH',			'');// Add seo path for Article

define('SOURCE_URL',		'https://pinterest.com');
define('SOURCE_NAME',		'pinterest.com');

//IMPORT CONFIG
define('CONTENT_MODE',		'IMAGE_ONLY');// IMAGE_ONLY, IMAGE_ARTICLE , or RANDOM
define('CSE_SITE',			'pinterest.com/pin/');//Index target by website
define('MAX_IMAGE_RESULT',	40);
define('BREAK_TIME',		0);//Sleep in Second


//IMAGE CONFIG
define('USE_CDN',			FALSE);//TRUE / FALSE
define('LAZY_LOAD',			TRUE);//TRUE / FALSE
define('SHOW_DOWNLOAD',		TRUE);//TRUE / FALSE

//EXPORT CONFIG
define('ARTICLE_PER_XML',	500);//MAX ARTICLE
define('BACK_DATE',			'-4 month');//Backdate
define('SHEDULE_DATE',		'+2 month');//Max Schedule
define('WP_CATEGORY',		'wallpaper');


//RSS CONFIG
define('MAX_RSS',			25);