@extends('layout')

@section('head')
<title>{{ site_name() }}</title>
@endsection

@section('bg')
http://themes.iamabdus.com/kidz/1.5/assets/img/home/slider/slider-1.jpg
@endsection

@section('content')
	@foreach(collect(keywords())->chunk(4) as $chunked)
		<div class="row">
			@foreach($chunked as $keyword)
				<div class="col-md-3">
					<a href="{{ image_url($keyword) }}">
						{{ $keyword }}
					</a>
				</div>
			@endforeach
			
		</div>

	@endforeach
@endsection
