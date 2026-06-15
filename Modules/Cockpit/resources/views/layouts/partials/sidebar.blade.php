<nav class="sidebar bg-body-tertiary border-end" style="min-width: 220px; min-height: calc(100vh - 56px);">
    <ul class="nav flex-column py-3">
        @foreach(app('menu')->visible() as $item)
            <li class="nav-item">
                <a class="nav-link px-3 py-2 {{ request()->routeIs($item['route']) ? 'active fw-semibold' : 'text-body' }}"
                   href="{{ route($item['route']) }}">
                    @if(!empty($item['icon']))
                        <i class="{{ $item['icon'] }} me-2"></i>
                    @endif
                    {{ __($item['label']) }}
                </a>
            </li>
        @endforeach

        @guest
            <li class="nav-item mt-3 px-3">
                <small class="text-muted">@lang('cockpit::menu.guest_notice')</small>
            </li>
        @endguest
    </ul>
</nav>
