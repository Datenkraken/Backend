@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('auth.login-title') }}</span>
                <label class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('general.email-address') }}:</span>

                    <input type="email" class="form-input w-full block" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </label>
                <label class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('general.password') }}:</span>

                    <input type="password" class="form-input w-full block" name="password" required autocomplete="current-password">
                </label>
                @if($errors->any())
                    <div class="text-center flex flex-col">
                        @foreach ($errors->all() as $error)
                            <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                                <strong>{{ $error }}</strong>
                            </span>
                        @endforeach
                    </div>
                @endif
                <div class="flex m-2 mt-4 p-2">
                    <label class="flex-grow">
                        <input class="form-checkbox w-5 h-5" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>

                        <span class=:>{{ __('auth.login-remember-me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <div>
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('auth.login-forgot-password') }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="text-lg text-gray-800 m-4 py-2 px-8 border border-solid border-black rounded">
                        {{ __('auth.login-submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
