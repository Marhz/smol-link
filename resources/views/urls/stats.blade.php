@extends('layouts.app')

@section('content')
<div class="container">
	<h2>{{ $url->url }}</h2>
	<h3>{{ $url->path }} <v-copy data={{ $url->path }}></v-copy></h3>
	<p>{{ $url->visits_count }} visits</p>
	<visits-graph-container
		:url="{{$url}}"
		:options="{responsive: true, maintainAspectRatio: true}"
	>
	</visits-graph-container>
</div>
@endsection
