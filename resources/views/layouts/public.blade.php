@extends('layouts.app')

@section('maincontent')
    <div class="flex flex-col w-full">
        <div class="flex-grow overflow-auto">
            @yield('content')
        </div>
        <footer class="flex flex-shrink-0 justify-center h-10 text-black">
            <div class="flex items-center font-medium text-lg">
                <a href="{{ route('imprint') }}" class="mx-2 hover:text-gray-500"><font-awesome-icon icon="id-card"></font-awesome-icon> <span class="underline">{{ __('navigation.imprint') }}</span></a>
                <a href="{{ route('privacy') }}" class="mx-2 hover:text-gray-500"><font-awesome-icon icon="file-alt"></font-awesome-icon> <span class="underline">{{ __('navigation.privacy-policy') }}</span></a>
            </div>
        </footer>
    </div>
@endsection
