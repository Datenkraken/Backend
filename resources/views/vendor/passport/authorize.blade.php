@extends('layouts.public')

@section('content')
<div class="flex-grow bg-gray-100 py-4">
    <div class="flex justify-center w-full">
        <div class="shadow-lg rounded-lg w-full sm:w-1/2 xl:w-1/4 p-4">
            <div class="block m-2">
                <span class="text-2xl w-full block font-bold text-gray-800 text-center">{{ __('Authorization Request') }}</span>

                <div class="card-body">
                    <!-- Introduction -->
                    <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

                    <!-- Scope List -->
                    @if (count($scopes) > 0)
                        <div class="p-2">
                                <p><strong>This application will be able to:</strong></p>

                                <ul class="list-disc list-inside">
                                    @foreach ($scopes as $scope)
                                        <li class="p-1">{{ $scope->description }}</li>
                                    @endforeach
                                </ul>
                        </div>
                    @endif

                    <div class="flex flex-wrap flex-row p-2">
                        <!-- Authorize Button -->
                        <form method="post" action="{{ route('passport.authorizations.approve') }}">
                            @csrf

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <dk-button type="submit">Authorize</dk-button>
                        </form>

                        <!-- Cancel Button -->
                        <form method="post" action="{{ route('passport.authorizations.deny') }}">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <dk-button>Cancel</dk-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
