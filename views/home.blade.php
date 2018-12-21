@extends('layout')

@section('head')
<title>{{ site_name() }}</title>
@endsection

@section('bg')
@endsection

@section('content')
	@foreach(collect(keywords())->shuffle()->take(16)->chunk(4) as $chunked)
		<div class="row">
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
