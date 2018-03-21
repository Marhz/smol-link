@extends('layouts.app')

@section('title')
	Smoll.ink - Dashboard
@endsection

@section('content')
	<v-dashboard :user="{{ $user }}"></v-dashboard>
@endsection
