<div class="relative top-0 flex flex-row flex-wrap bg-bgprimary px-6 py-2 text-gray-100 items-center">
    <div class="text-3xl flex-grow text-primary">
        <a href="{{ route('home') }}">{{ config('app.name', 'Data Literacy') }}</a>
    </div>

    <div class="flex items-center ml-auto">
        <dk-language-dropdown></dk-language-dropdown>
        @guest
            <div class="border bg-bgsecondary border-black rounded-lg px-4 py-2 text-xl">
                <a href="{{ route('login') }}">
                    {{ __('Login') }}
                    <font-awesome-icon icon="sign-in-alt"></font-awesome-icon>
                </a>
            </div>
        @else
            <dk-account-dropdown></dk-account-dropdown>
        @endguest
    </div>
</div>
