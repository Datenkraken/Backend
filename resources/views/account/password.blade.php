@extends('layouts.public')

@section('content')
<div class="bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <form method="POST" action="{{ route('account.password.update') }}" class="mt-4 px-8 pb-6">
                @csrf

                <span class="text-2xl w-full block font-bold text-gray-800 text-center mb-4">{{__('passwords.reset-title')}}</span>

                @if($errors->any())
                    <div class="text-center flex flex-col">
                        @foreach ($errors->all() as $error)
                            <span class="py-1 px-2 m-2 border-2 border-solid border-red-400 rounded-lg inline-block">
                                <strong>{{ $error }}</strong>
                            </span>
                        @endforeach
                    </div>
                @endif

                <label class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('passwords.current') }}:</span>

                    <input id="password_current" name="password_current" type="password" class="form-input w-full block" required placeholder="{{ __('passwords.current') }}">
                </label>

                <label class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('passwords.new') }}:</span>

                    <input type="password" id="password" name="password" class="form-input w-full block" required placeholder="{{ __('passwords.new') }}">
                </label>

                <label class="block m-2">
                    <span class="text-gray-700 font-bold">{{ __('passwords.new-confirm') }}:</span>

                    <input type="password" id="password-confirm" name="password_confirmation" class="form-input w-full block" required placeholder="{{ __('passwords.new-confirm') }}">
                </label>

                <div class="flex mt-6">
                    <dk-button
                    type="submit"
                    class="flex-grow"
                    >
                        {{ __('general.save') }}
                    </dk-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
