@extends('layouts.dashboard')

@section('dashcontent')
    <div class="m-4 flex-grow">
        <span class="text-xl">{{ __('oauth.access-tokens') }}</span>
        <dk-oauth-personal-access-tokens class="mx-4"></dk-oauth-personal-access-tokens>
    </div>
    <div class="m-4 flex-grow">
        <span class="text-xl">{{ __('oauth.authorized-clients') }}</span>
        <dk-oauth-authorized-clients class="mx-4"></dk-oauth-authorized-clients>
    </div>
    <div class="m-4 flex-grow">
        <span class="text-xl">{{ __('oauth.clients') }}</span>
        <dk-oauth-clients class="mx-4"></dk-oauth-clients>
    </div>
@endsection
