@extends('layouts.app')

@section('maincontent')
    @include('layouts.navigation')
    <div class="flex-grow flex flex-col h-full overflow-x-auto sm:overflow-auto">
        @yield('dashcontent')
    </div>
@endsection
