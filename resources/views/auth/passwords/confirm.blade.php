@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <div class="block m-2">
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('passwords.confirm-password') }}</span>

                <div class="block mt-2">
                    {{ __('passwords.confirm-title') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="block mt-2">
                            <label for="password" class="text-gray-700 font-bold">{{ __('passwords.current') }}</label>

                            <div>
                                <input id="password" type="password" class="form-input w-full block" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-start mt-4">
                            <button type="submit" class="text-lg text-gray-800 py-2 px-8 border border-solid border-black rounded">
                                {{ __('passwords.confirm-password') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="py-2 self-center flex-grow text-right" href="{{ route('password.request') }}">
                                    {{ __('passwords.confirm-forgot') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
