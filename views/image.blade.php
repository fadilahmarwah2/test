@extends('layout')

@section('head')
<title>{{ ucwords($keyword) }}</title>
@endsection

@section('header')
<h1>{{ ucwords($keyword) }}</h1>

@php
	shuffle($sentences);
@endphp

<div class="text-center">
	@if(!empty($sentences))
		<p>{{ @array_pop($sentences) }} {{ @array_pop($sentences) }}</p>
	@endif
</div>
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

		@if($loop->iteration == 2)
			<div class="row">
				<div class="col-md-12">
					<div class="navi text-left">
						<h3>{{ @array_pop($sentences) }}</h3>
						<img src="{{ collect($images)->random()['url'] }}" width="34%" align="left" style="margin-right: 8px;margin-bottom: 8px;">

						@foreach(collect($sentences)->chunk(3) as $chunked_sentences)
							<p>
								@if($loop->first)
									<strong>{{ ucfirst($keyword) }}</strong>. 
								@endif

								@foreach($chunked_sentences as $chunked_sentence)
									{{ $chunked_sentence }} 
								@endforeach
							</p>
						@endforeach
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="navi text-center bg-dark text-white">
						<p>{{ @array_pop($sentences) }}</p>
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
