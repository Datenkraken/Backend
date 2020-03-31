@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <div class="block m-2">
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('auth.verify-title') }}</span>

                <div class="mt-2">
                    @if (session('resent'))
                        <div class="text-center flex flex-col">
                            <div class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block" role="alert">
                                {{ __('auth.verify-sent') }}
                            </div>
                        </div>
                    @endif

                    {{ __('auth.verify-check-email') }}
                    {{ __('auth.verify-check-email-2') }},
                    <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="text-blue-600">{{ __('auth.verify-check-email-request') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
