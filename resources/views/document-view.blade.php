@extends('layouts.public')

@section('content')
    <div class="overflow-x-auto w-full">
        <div class="ck-content w-full md:w-3/4 lg:w-1/2 mx-auto p-4">
            <p class="text-gray-700 text-xs">
                {{ __('general.last-changed') . ' ' . $data->updated_at->diffForHumans() }}
            </p>
            <div>
            {!! $data->content !!}
            </div>
        </div>
    </div>
@endsection
