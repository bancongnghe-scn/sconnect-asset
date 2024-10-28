<aside x-data="sidebar" class="main-sidebar bg-sc">
    <a href="/home" class="brand-link text-decoration-none">
        <img src="/images/logo-s.png" alt="S-Office" style="height: 50px; margin-top: -6px; margin-left: 12px;">
        <span class="brand-text"><img src="/images/logo-text.png" alt="S-Office" style="height: 60px; margin-top: -6px; margin-left: -12px;"></span>
    </a>
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item rounded bg-white-opacity-10-hover {{request()->is('home') ? 'bg-white-opacity-10' : ''}}">
                    <a href="{{route('home')}}" class="nav-link text-white">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </a>
                </li>
                <template x-for="menu in menus">
                    <li class="nav-item"
                        :class="menu.children.map(child => child.url).includes(window.location.pathname) ? 'menu-is-opening menu-open' : ''">
                        <a href="#" class="nav-link text-white">
                            <i :class="menu.icon"></i>
                            <p>
                                <span x-text="menu.name"></span>
                                <i x-show="menu.children.length > 0" class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <template x-for="child in menu.children">
                                <li class="nav-item rounded text-white bg-white-opacity-10-hover"
                                    :class="window.location.pathname === child.url ? 'bg-white-opacity-10' : 'bg-white-opacity-10-hover'">
                                    <a :href="child.url" class="nav-link text-decoration-none text-white d-flex align-items-center tw-pl-7">
                                        <i class="fa-solid fa-circle" style="font-size: 7px"></i>
                                        <p class="tw-pl-2.5" x-text="child.name"></p>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </li>
                </template>
            </ul>
        </nav>
    </div>
</aside>
@vite([
        'resources/js/layouts/sidebar.js',
        'resources/js/rbac/api/apiMenu.js',
])
