@extends('layout')

@section('head')
<title>{{ ucwords($keyword) }}</title>
@endsection

@section('bg')
{{ collect($images)->random()['url'] }}
@endsection

@section('header')
	<h1>{{ ucwords($keyword) }}</h1>

	@php
		shuffle($sentences);
	@endphp

	<div class="navi text-center">
		@if(!empty($sentences))
			<p>{{ @array_pop($sentences) }} {{ @array_pop($sentences) }} {{ @array_pop($sentences) }} <br>
				@foreach($related as $r)
					@if(data_exists($r))
						<a class="badge badge-{{ collect(['primary', 'secondary', 'success', 'info', 'danger', 'warning', 'light', 'dark'])->random() }}" href="{{ image_url($r) }}">{{ $r }}</a>
					@endif
				@endforeach
			</p>
		@endif
	</div>

@endsection

@section('content')
	
	@foreach(collect($images)->shuffle()->chunk(3) as $chunked)
		
		<div class="row">
			@foreach($chunked as $image)
				<div class="col-md-4">
					<a href="{{ preview_url($image) }}" target="_blank">
						<img class="img-fluid" src="{{ $image['url'] }}" onerror="this.onerror=null;this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQh_l3eQ5xwiPy07kGEXjmjgmBKBRB7H2mRxCGhv1tFWg5c_mWT';"></a>
					<p>
						{{ $image['title'] }}
						<br>
						<span class="badge badge-secondary">{{ parse_url($image['source'], PHP_URL_HOST) }}</span>
					</p>
				</div>
			@endforeach
			
		</div>

		@if($loop->iteration == 2)
			<div class="row">
				<div class="col-md-12">
					<div class="text-left">
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
			
		@endif

	@endforeach
	


	
	
@endsection
