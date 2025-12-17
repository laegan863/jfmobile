<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <i class="bi bi-phone"></i>
            </div>
            <span class="logo-text">JF Mobile</span>
        </div>
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        @php
            $sidebarMenu = [
                [
                    'title' => 'Main Menu',
                    'items' => [
                        [
                            'name' => 'Dashboard',
                            'icon' => 'bi-grid-1x2',
                            'route' => 'admin.dashboard',
                        ],
                        [
                            'name' => 'Activate Subscriber',
                            'icon' => 'bi-people',
                            'route' => 'admin.activate-subscriber',
                        ],
                        [
                            'name' => 'Adjust Balance',
                            'icon' => 'bi-box-seam',
                            'route' => null,
                        ],
                        [
                            'name' => 'Cancel Device Location',
                            'icon' => 'bi-cart3',
                            'route' => null,
                        ],
                        [
                            'name' => 'Cancel PortIn',
                            'icon' => 'bi-people',
                            'route' => null,
                        ],
                        [
                            'name' => 'Change IMEI',
                            'icon' => 'bi-bar-chart-line',
                            'route' => null,
                        ],
                    ],
                ],
                [
                    'title' => 'Management',
                    'items' => [
                        [
                            'name' => 'Check Network Coverage',
                            'icon' => 'bi-tags',
                            'route' => null,
                        ],
                        [
                            'name' => 'Check PortIn Eligibility',
                            'icon' => 'bi-percent',
                            'route' => null,
                        ],
                        [
                            'name' => 'Create PortIn',
                            'icon' => 'bi-truck',
                            'route' => null,
                        ],
                    ],
                ],
            ];
        @endphp

        @foreach ($sidebarMenu as $section)
            <div class="nav-section">
                <span class="nav-section-title">{{ $section['title'] }}</span>
                <ul class="nav-list">
                    @foreach ($section['items'] as $item)
                        <li
                            class="nav-item {{ $item['route'] && request()->routeIs($item['route'] . '*') ? 'active' : '' }}">
                            <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="nav-link">
                                <i class="bi {{ $item['icon'] }}"></i>
                                <span>{{ $item['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=6366f1&color=fff"
                    alt="{{ Auth::user()->name ?? 'Admin' }}">
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                <span class="user-role">Administrator</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
