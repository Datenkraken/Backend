@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('passwords.request-reset-email-title') }}</span>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label for="email" class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('general.email-address') }}:</span>
                    <input id="email" type="email" class="form-input w-full block" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </label>

                @error('email')
                    <div class="text-center">
                        <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    </div>
                @enderror

                <div class="flex justify-center">
                    <button type="submit" class="text-lg text-gray-800 m-4 py-2 px-8 border border-solid border-black rounded">
                        {{ __('passwords.request-reset-email-submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
