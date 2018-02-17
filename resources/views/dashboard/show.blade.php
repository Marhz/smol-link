@extends('layouts.app')

@section('title')
	Smol-l.ink - Dashboard
@endsection

@section('content')
	<v-dashboard :user="{{ $user }}"></v-dashboard>
@endsection
