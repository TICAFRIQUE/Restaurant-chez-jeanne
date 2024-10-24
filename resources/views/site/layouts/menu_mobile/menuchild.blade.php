<!-- mobile-menu-area-start -->
<li>
    @if ($menu->children->isNotEmpty())
        <a href="{{ route('produit', $menu->slug) }}">
            @if ($menu->slug === 'bar')
                Nos boissons
            @elseif ($menu->slug === 'menu')
                Nos plats
            @else
                {{ $menu['name'] }}
            @endif
        </a>
        </a>
        <ul>
            @foreach ($menu->children as $child)
                @include('site.layouts.menu_mobile.menuchild', ['menu' => $child])
            @endforeach
        </ul>
    @else
        <a href="{{ route('produit', $menu->slug) }}">
            @if ($menu->slug === 'bar')
                Nos boissons
            @elseif ($menu->slug === 'menu')
                Nos plats
            @else
                {{ $menu['name'] }}
            @endif
        </a>
    @endif
</li>
<!-- mobile-menu-area-end -->
