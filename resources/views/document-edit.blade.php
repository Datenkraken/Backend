@extends('layouts.dashboard')

@section('dashcontent')
	<dk-document-edit title="{{ $title }}" routeprefix="{{ $routePrefix }}"></dk-document-edit>
@endsection
