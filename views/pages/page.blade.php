@extends('layout')

@section('head')
<title>{{ ucwords(str_replace('-', ' ', $page)) }}</title>
@endsection

@section('content')
	@include('pages.' . $page)
@endsection