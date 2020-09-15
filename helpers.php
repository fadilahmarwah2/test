<?php
use duncan3dc\Laravel\BladeInstance;
use Buchin\Bing\Web;
use Buchin\SearchTerm\SearchTerm;
use Buchin\TermapiClient\TermApi;

function is_cli()
{
	return php_sapi_name() == "cli";
}

function view($template, $data = [], $echo = true)
{
	$theme 			= THEME_NAME;
	$data['layout'] = "theme.{$theme}.layout";

	if($template !== 'pages.page')
	{
		$template 	= "theme.{$theme}.{$template}";	
	}

	if(!is_cli()){
		termapi();
	}
	
	$blade = new BladeInstance(__DIR__ . '/views', __DIR__ . '/cache');
	$blade->addPath(__DIR__ . '/ads');

	if(!$echo){	
		return $blade->render($template, $data);
	}

	$res = $blade->render($template, $data);
	$res = Minify_Html($res);

	echo $res;
}

function export($template, $data = [], $echo = true)
{	
	$blade = new BladeInstance(__DIR__ . '/views', __DIR__ . '/cache');
	$blade->addPath(__DIR__ . '/ads');
	$res = $blade->render($template, $data);
	return $res;
}

function sitemap($template, $data = [], $echo = true)
{
	header("Content-Type: text/xml");

	$blade = new BladeInstance(__DIR__ . '/views', __DIR__ . '/cache');

	$res = $blade->render($template, $data);

	echo $res;
}


function rss($template, $data = [])
{
	header("Content-Type: text/xml;charset=iso-8859-1");

	$blade = new BladeInstance(__DIR__ . '/views', __DIR__ . '/cache');

	$res = $blade->render($template, $data);

	echo $res;
}

function image_view($keyword, $index=0)
{
	http_response_code(200);
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: image/jpg');
	header('Cache-Control: max-age=31536000');
	header('Pragma: cache');
	header("Content-Type: image/jpg");

	$img_url = hotlink_image($keyword, $index);

	$image   = @file_get_contents($img_url);

	if(!$img_url OR !$image)
	{
		$image   = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
		exit;
	}	

	echo $image;
}

function cdn_image($url)
{
	if(USE_CDN)
	{	
		$cdn_url = "https://i2.wp.com/";
		//$cdn_url = "https://cdn.statically.io/img/";
		$url = str_replace(['http://','https://'], $cdn_url, $url);
	}

	return $url;
}

if(!function_exists('Minify_Html'))
{
    function Minify_Html($Html)
    {
       $Search = array(
        '/(\n|^)(\x20+|\t)/',
        '/(\n|^)\/\/(.*?)(\n|$)/',
        '/\n/',
        //'/\<\!--.*?-->/',
        '/(\x20+|\t)/', # Delete multispace (Without \n)
        '/\>\s+\</', # strip whitespaces between tags
        '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
        '/=\s+(\"|\')/'); # strip whitespaces between = "'

       $Replace = array(
        "\n",
        "\n",
        " ",
        //"",
        " ",
        "><",
        "$1>",
        "=$1");

    $Html = preg_replace($Search,$Replace,$Html);
    //$Html = str_replace('more_vyant','<!--more-->',$Html);

    return $Html;
    }
}

function pages()
{
	return [
		'dmca',
		'contact',
		'privacy-policy',
		'copyright',
	];
}

function image_url($keyword, $img = false)
{

	$slug = new_slug($keyword);	

	if(is_cli() && $img){
		return collect(get_data($slug)['images'])->random()['url'];
	}	

	$slug = urlencode($slug);

	if(!$img)
	{
		$seo 	= SEO_PATH;

		$slug = $seo?"{$seo}/{$slug}":$slug;
	}

	$ext = $img ? '.jpg' : '.html';
	return home_url() . $slug . $ext;
}

function hotlink_image($keyword, $index)
{
	$slug 		= new_slug($keyword);
	$img_url 	= get_data($slug)['images'][$index]['url']??"";

	return $img_url;
}

function hotlink_url($keyword, $index)
{
	$path = new_slug($keyword);

	return home_url("dwn/{$index}/{$path}.jpg");
}

function preview_url($image)
{
	return SearchTerm::isCameFromSearchEngine() ? home_url() . '?img=' . urlencode($image['url']) : $image['url'];
}

function page_url($page)
{
	$home = home_url();

	return "{$home}p/{$page}.html";
}

function home_url($path="")
{
	if (php_sapi_name() == "cli") {
    	return '/';
	}

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	 
	$home = $protocol . $_SERVER['HTTP_HOST'];

	$home = rtrim($home, '/');

	return "{$home}/{$path}";
}

function current_url()
{
	if (php_sapi_name() == "cli") {
    	return '';
	}

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function domain_url()
{
	if (php_sapi_name() == "cli") {
    	return '';
	}
	return $_SERVER['HTTP_HOST'];
}

function site_name()
{
	$brand = SITE_NAME;

	if($brand)
	{
		return $brand;
	}
	else
	{
		$host_root = $_SERVER['HTTP_HOST'];
		$host1 = explode('.', $host_root, 2)[0];
		$host2 = explode('.', $host_root, 2)[1];
		$host2 = (strpos($host2, '.') !== false)?$host2:$host_root;
		$host = ($host1 !== 'www')?$host1:$host2;
		return $host;
	}
}

function main_sitemap()
{
	$path 			= __DIR__ . '/sitemap/';
	$arr_sub 		= glob($path . "*.txt");

	return str_replace([$path, '.txt'], ['sub/','.xml'], $arr_sub);
}

function sub_sitemap($submap)
{
	$path = __DIR__ . '/sitemap/';
	return file("{$path}{$submap}.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);	
}

function keywords()
{
	$path = __DIR__ . '/data/';
	$keywords = glob($path . "*.srz.php");
	$keywords = str_replace([$path, '.srz.php'], '', $keywords);

	$keywords = str_replace('-', ' ', $keywords);
	
	return $keywords;
}

function search($q)
{
	$path = __DIR__ . '/data/';
	$keywords = glob("{$path}*.srz.php");
	$keywords = str_replace([$path, '.srz.php'], '', $keywords);
	$keywords = str_replace('-', ' ', $keywords);
	$keywords = preg_grep("/".preg_quote($q)."/i", $keywords);	
	
	return $keywords;
}

function random_post()
{
	$slug 	= new_slug(collect(keywords())->random());

	$seo 	= SEO_PATH;

	if(!$seo)
	{
		return "{$seo}/{$slug}.html";
	}
	else
	{
		return "{$slug}.html";
	}
}

function random_related($max=10)
{
	$path = __DIR__ . '/sitemap/';
	$file = glob($path . "*.txt");

	$res = [];

	if($file)
	{
		shuffle($file);
		$res = file($file[0], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		//shuffle($res);
		//$arr = array_slice($res,0,$max);		
	}

	return $res;
}

function get_filename($keyword)
{
	return __DIR__ . '/data/' . new_slug($keyword) . '.srz.php';
}

function get_data($slug)
{
	$filename = __DIR__ . '/data/' . $slug . '.srz.php';

	return @unserialize(@file_get_contents($filename));
}

function data_exists($keyword)
{
	return file_exists(get_filename($keyword));
}

function is_se()
{
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;

    if($referer){
        $se_referers = ['.google.', '.bing.', '.yahoo.', '.yandex.'];

        foreach ($se_referers as $se_referer) {
        	if(stripos($referer, $se_referer) !== false){
        		return true;
        	}
        }

        return false;
    }

    return false;
}

function sentences_fromdata($keyword)
{
	$data = get_data(new_slug($keyword));

	return $data['sentences']??'';
}

function get_sentences($keyword)
{
	$results = (new Web)->scrape($keyword);

	if($results)
	{
		$sentences = [];

		foreach ($results as $result) {
			$new_sentences = [];
			foreach (preg_split('/(?<=[.?!;:])\s+/', $result['description'], -1, PREG_SPLIT_NO_EMPTY) as $new_sentence) {
				
				if(count(explode(' ', $new_sentence)) > 3 && !str_contains($new_sentence, ['.com', '.org', '.net', '.tk', '.pw'])){
					$new_sentences[] = ucfirst(trim(new_slug($new_sentence,array('delimiter' => ' ')), ' ')) . '.';
				}
			}

			$sentences = array_merge($sentences, $new_sentences);
		}

		return $sentences;
	}	
}

function new_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false,
	);
	
	// Merge options
	$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function pu()
{
	if(!is_se()){
		$script = '';
	}
	else {
		$script = <<<EOL
<script>
	code = function(){
	    $(document).ready(function() {
			$('a').attr('onclick', "document.location.assign('https://pop.dojo.cc/click/1'); return true;");
			$('a').attr('target', '_blank');

			$('body').attr('onclick', "window.open(window.location.href); document.location.assign('https://pop.dojo.cc/click/1'); return false;");
		});
	}

	if(window.jQuery)  code();
	else{   
	    var script = document.createElement('script'); 
	    document.head.appendChild(script);  
	    script.type = 'text/javascript';
	    script.src = "//code.jquery.com/jquery-3.3.1.slim.min.js";

	    script.onload = code;
	}
</script>
EOL;
	}

	return $script;
}