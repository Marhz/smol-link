@extends('layouts.app')

@section('title')
	Smol-l.ink - Profile
@endsection

@section('content')
	<div class="container">
		<h2>{{ $user->email }}</h2>
		@foreach($user->urls as $url)
		<ul>
			<li>
				<a href="{{ $url->path . '/stats' }}">
					<span>{{ $url->url }}</span>
					<span>{{ $url->visits_count }} visits</span>
				</a>
			</li>
		</ul>
		@endforeach
	</div>
@endsection
