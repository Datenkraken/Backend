<div class="w-full sm:w-4/12 md:w-3/12 xl:w-2/12 h-auto sm:h-full overflow-y-auto flex-none bg-bgsecondary text-lg border-r-4 border-gray-600">
    <ul class="mx-auto px-3">
        @component('components.navitem', ['url' => 'home', 'name' =>
            __('navigation.home'), 'icon' => 'home'])
        @endcomponent
        @component('components.navitem', ['url' => 'maps', 'name' =>
        __('navigation.maps'), 'icon' => 'map-marker-alt'])
            @endcomponent
        @component('components.navitem', ['url' => 'users', 'name' =>
            __('navigation.users'), 'icon' => 'users'])
        @endcomponent
        @component('components.navitem', ['url' => 'sources', 'name' =>
            __('navigation.sources'), 'icon' => 'folder-open'])
        @endcomponent
        @component('components.navitem', ['url' => 'privacy/edit', 'name'
			=> __('navigation.privacy-policy'), 'icon' => 'file-alt'])
        @endcomponent
        @component('components.navitem', ['url' => 'imprint/edit',
            'name' => __('navigation.imprint'), 'icon' => 'id-card'])
        @endcomponent
        @component('components.navitem', ['url' => 'manage-oauth',
            'name' => __('navigation.oauth'), 'icon' => 'key'])
        @endcomponent
        @component('components.navitem', ['url' => 'retention', 'name' => __('navigation.retention'), 'icon' => 'hourglass-end'])
        @endcomponent
    </ul>
</div>
