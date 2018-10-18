@php
	shuffle($sentences);
@endphp

@if(!empty($sentences))
	<p>{{ @array_pop($sentences) }} {{ @array_pop($sentences) }}</p>
@endif

@foreach(collect($images) as $image)
	
	<p>
		<a href="{{ $image['url'] }}">
		<img class="img-fluid" src="{{ $image['url'] }}" width="100%" onerror="this.onerror=null;this.src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQh_l3eQ5xwiPy07kGEXjmjgmBKBRB7H2mRxCGhv1tFWg5c_mWT';"></a>
	{{ $image['title'] }}
	</p>

	@if($loop->first)
		
		<h3>{{ @array_pop($sentences) }}</h3>
		<img src="{{ collect($images)->random()['url'] }}" width="100%" align="left" style="margin-right: 8px;margin-bottom: 8px;"> @foreach(collect($sentences)->chunk(3) as $chunked_sentences)
			<p>
				@if($loop->first) <strong>{{ ucfirst($keyword) }}</strong>. @endif @foreach($chunked_sentences as $chunked_sentence){{ $chunked_sentence }} @endforeach
			</p>
		@endforeach
				
	@endif

@endforeach
	