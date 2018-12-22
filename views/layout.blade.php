<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Mr+Dafoe" rel="stylesheet" type="text/css">

		<style type="text/css">
			html, body {
			    font-family: "Open Sans", Arial, sans-serif;
			    font-size: 14px;
			    font-weight: 400;
			    background: #f3f3f3 url(@yield('bg')) no-repeat center center fixed;
				background-size: cover;
				height: 100%;
			}

			#title{
				font-family: "Mr Dafoe", Times, serif;
			    font-size: 34px;
			    background: rgba(255, 255, 255, 0.8);
			    margin-bottom: 34px;
			}

			.header{
				padding-bottom: 13px;
			    margin-bottom: 13px;
			    border-bottom: 1px solid #eee;
			}

			a {
			    color: #26ADE4;
			    text-decoration: none;
			}

			#title a{
				color: #333;
				margin-left: 21px;
			}

			#title a:hover{
				text-decoration: none;
				color: #26ADE4;
			}

			.container{
				max-width: 960px;
				margin: 0px auto 60px;
			    padding: 20px;
			    background-color: #fff;
			    border: 1px solid #ccc;
			    border-top: 1px solid #ddd;
			    border-left: 1px solid #ddd;
			    box-shadow: 3px 3px 3px rgba(150,150,150,0.2);
			}
			.sidebar ul{
				margin: 0;
			    padding: 0;
			}

			.sidebar ul li{
				list-style: none;
			}

			.footer{
				margin-top: 21px;
				padding-top: 13px;
				border-top: 1px solid #eee;
			}

			.footer a{
				margin: 0 15px;
			}

			.navi{
				margin:13px 0 13px 0;
				padding:13px;
			}

			.navi a{
				margin: 5px 2px;
				font-size: 95%;
			}
			
		</style>

		@yield('head')
		@include('header')
	</head>
	<body>
		<div id="title" class="">
			<a href="{{ home_url() }}">{{ site_name() }}</a>
		</div>
		<div class="container">
			<div class="row header">
				<div class="col-sm-12 text-center">

					@yield('header')
					@include('related')

				</div>
			</div>
			<div class="row content">
				<div class="col-md-12">
					
					@yield('content')
				</div>
			</div>
			<div class="row footer">
				<div class="col-md-12 text-center">
					@foreach(pages() as $page)
					<a href="{{ page_url($page) }}">{{ ucwords(str_replace('-', ' ', $page)) }}</a>
					@endforeach

				</div>
			</div>
		</div>
		@include('bar')
		@include('footer')
	</body>
</html>