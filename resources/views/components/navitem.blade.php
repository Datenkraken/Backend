<li class="flex-1 mr-3 my-2">
    <a href="{{ url($url) }}" class="block p-2 {{
        Request::is($url) ?
            'text-primary border-primary' :
            'hover:text-primary hover:border-primary text-gray-800 no-underline border-bgprimary'
    }} border-b-2">
        <font-awesome-icon icon="{{ $icon }}"></font-awesome-icon>
        <span class="{{ Request::is($url) ? 'text-gray-100' : 'text-gray-600'}}">
            {{ $name }}
        </span>
    </a>
</li>
