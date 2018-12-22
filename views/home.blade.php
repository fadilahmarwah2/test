@extends('layout')

@section('head')
<title>{{ site_name() }}</title>
@endsection

@section('bg')
@endsection

@section('content')
	@if(isset($_GET['img']))
	<div class="row">
		<div class="col-md-12 text-center">
			<p><a href="{{ $_GET['img'] }}" class="btn btn-outline-primary" download="image">Download Image</a></p>
			<p><img src="{{ $_GET['img'] }}" alt="" class="img-fluid"></p>

		</div>
	</div>
	<div class="mt-3"></div>
	@endif

	@foreach(collect(keywords())->shuffle()->take(16)->chunk(4) as $chunked)
		<div class="row mt-3">
			@foreach($chunked as $keyword)
				<div class="col-md-3">
					<p class="text-center">{{ $keyword }}</p>
					<a href="{{ image_url($keyword) }}">
						<img src="{{ image_url($keyword, true) }}" alt="" class="img-fluid" onerror="this.onerror=null;this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQh_l3eQ5xwiPy07kGEXjmjgmBKBRB7H2mRxCGhv1tFWg5c_mWT';">
					</a>
				</div>
			@endforeach
			
		</div>

	@endforeach
@endsection
