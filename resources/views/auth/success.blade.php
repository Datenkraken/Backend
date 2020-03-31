@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <div class="block m-2">
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('auth.created-success') }}</span>
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('auth.created-success-close') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
