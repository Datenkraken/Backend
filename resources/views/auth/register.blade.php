@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <input type="hidden" name="origin" value="{{ request()->input('origin') }}">

            <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('auth.register-title') }}</span>
                <div class="block m-2">
                    <label for="email" class="text-gray-700 font-bold">{{ __('general.email-address') }}:</label>

                    <div>
                        <input id="email" type="email" class="form-input w-full block" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="block m-2">
                    <label for="password" class="text-gray-700 font-bold">{{ __('general.password') }}:</label>

                    <div>
                        <input id="password" type="password" class="form-input w-full block" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="block m-2">
                    <label for="password-confirm" class="text-gray-700 font-bold">{{ __('passwords.confirm-password') }}:</label>

                    <div>
                        <input id="password-confirm" type="password" class="form-input w-full block" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="text-lg text-gray-800 m-4 py-2 px-8 border border-solid border-black rounded">
                        {{ __('auth.register-submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
