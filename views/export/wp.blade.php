@php
$default_author = 'Admin';
$site_url = 'http://example.com/';
$category = isset($argv[2]) ? $argv[2] : 'Uncategorized';
$backdate = isset($argv[1]) ? $argv[1] : date('Y-m-d');
@endphp
<?xml version=\"1.0\" encoding=\"UTF-8\"
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.0/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.0/"
>

<channel>
	<title>My Site</title>
	<link>http://example.com/</link>
	<description></description>
	<pubDate>Thu, 28 May 2009 16:06:40 +0000</pubDate>
	<generator>http://wordpress.org/?v=2.7.1</generator>
	<language>en</language>
	<wp:wxr_version>1.0</wp:wxr_version>
	<wp:base_site_url>http://example.com/</wp:base_site_url>
	<wp:base_blog_url>http://example.com/</wp:base_blog_url>

	@foreach($keywords as $post_id => $keyword)
		@php
			$data = get_data(str_slug($keyword));
			$data['keyword'] = $keyword;
			$post_title = $keyword;
			$slug = str_slug( $post_title );
			$unixtime = rand(strtotime($backdate), time());
			$pubdate = date( 'D, d M Y H:i:s', $unixtime )." +0000";
			$post_date = date( 'Y-m-d H:i:s', $unixtime );
			$month = date('m', $unixtime);
			$day = date('d', $unixtime);
			$year = date('Y', $unixtime);

<<<<<<< HEAD
			$post_content = view('export.post', $data, false);
=======
			$post_content = view('export.wp-post', $data, false);
>>>>>>> ec7a639b89c84631d6309096f12e0f5474518b5a
		@endphp
		<item>
			<title><![CDATA[{{ ucwords($keyword) }}]]></title>
			
			<link>{{ $site_url }}{{ $slug }}/</link>
			<pubDate>{{ $pubdate }}</pubDate>

			<dc:creator><![CDATA[{{ $default_author }}]]></dc:creator>
			<wp:postmeta>
				<wp:meta_key>_byline</wp:meta_key>
				<wp:meta_value>{{ $default_author }}</wp:meta_value>
			</wp:postmeta>

			@php
			$category = trim( $category );
			$cat_slug = str_slug( $category );
			@endphp

			<category><![CDATA[{{ $category }}]]></category>
			<category domain="category" nicename="{{ $cat_slug }}"><![CDATA[{{ $category }}]]></category>
			
			@php
				$tags = $data['related'];
			@endphp

			@foreach ( $tags as $tag )
				@if( !empty( $tag ))
					@php
					$tag_slug = str_slug( $tag );
					@endphp

					<category domain="tag" nicename="{{ $tag_slug }}"><![CDATA[{{ $tag }}]]></category>
				@endif
			@endforeach
			
		
			<guid isPermaLink="false">{{ $site_url }}?p={{ $post_id }}</guid>
			<description></description>
			<content:encoded><![CDATA[{!! $post_content !!}]]></content:encoded>
			<excerpt:encoded><![CDATA[]]></excerpt:encoded>
			<wp:post_id>{{ $post_id }}</wp:post_id>
			<wp:post_date>{{ $post_date }}</wp:post_date>
			<wp:post_date_gmt>{{ $post_date }}</wp:post_date_gmt>
			<wp:comment_status>open</wp:comment_status>
			<wp:ping_status>closed</wp:ping_status>
			<wp:post_name>{{ $slug }}</wp:post_name>

			<wp:status>publish</wp:status>
			<wp:post_parent>0</wp:post_parent>
			<wp:menu_order>0</wp:menu_order>
			<wp:post_type>post</wp:post_type>
			<wp:post_password></wp:post_password>
			
			<wp:postmeta>
				<wp:meta_key>_old_id</wp:meta_key>
				<wp:meta_value>{{ $post_id }}</wp:meta_value>
			</wp:postmeta>


		</item>
		
	@endforeach

</channel>
</rss>
