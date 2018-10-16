@extends('layout')

@section('head')
<title>{{ ucwords($keyword) }}</title>
@endsection

@section('header')
<h1>{{ ucwords($keyword) }}</h1>
<p>
	<strong>{{ ucfirst($keyword) }}</strong>. {{ collect($sentences)->shuffle()->take(3)->implode(' ') }}
</p>
@endsection

@section('content')
	
	@foreach(collect($images)->shuffle()->chunk(3) as $chunked)

		<div class="row">
			@foreach($chunked as $image)
				<div class="col-md-4">
					<a href="{{ $image['url'] }}">
						<img class="img-fluid" src="{{ $image['url'] }}" class="img-fluid" onerror="this.onerror=null;this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQh_l3eQ5xwiPy07kGEXjmjgmBKBRB7H2mRxCGhv1tFWg5c_mWT';"></a>
					<p>{{ $image['title'] }}</p>
				</div>
			@endforeach
			
		</div>

		@if($loop->first)

			<div class="row">
				<div class="col-md-12">
					<div class="navi text-center bg-dark text-white">
						<p>{{ collect($sentences)->shuffle()->take(3)->implode(' ') }}</p>
						<p>
							You might also like:
						</p>
						@foreach($related as $r)
							@if(data_exists($r))
								<a class="badge badge-{{ collect(['primary', 'secondary', 'success', 'info', 'danger', 'warning', 'light', 'dark'])->random() }}" href="{{ image_url($r) }}">{{ $r }}</a>
							@endif
						@endforeach
					</div>
				</div>
			</div>
		@endif

	@endforeach

	
	
@endsection
