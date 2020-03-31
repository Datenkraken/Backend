@extends('layouts.dashboard')

@section('dashcontent')
    <div class="p-3 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        <dk-chart-container title="{{ __('home.user-graph') }}">
            <dk-user-count-chart></dk-user-count-chart>
        </dk-chart-container>
        <dk-chart-container title="{{ __('home.opened-graph') }}">
            <dk-app-opened-chart></dk-app-opened-chart>
        </dk-chart-container>
    </div>
@endsection
