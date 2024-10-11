<aside class="main-sidebar bg-sc">
    <a href="/home" class="brand-link">
        <img src="/images/logo-s.png" alt="S-Office" style="height: 50px; margin-top: -6px; margin-left: 12px;">
        <span class="brand-text"><img src="/images/logo-text.png" alt="S-Office"
                                      style="height: 60px; margin-top: -6px; margin-left: -12px;"></span>
    </a>
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('home') ? 'tw-bg-white/10' : ''}}">
                    <a href="{{route('home')}}" class="nav-link text-white">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fa-solid fa-gear"></i>
                        <p>Cài đặt<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview tw-list-disc {{ request()->is('asset-type-group/*')
                        || request()->is('asset-type/*')
                        || request()->is('industry/*')
                        || request()->is('supplier/*')
                        || request()->is('rbac/*') ? 'show' : '' }}">
                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('asset-type-group/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/asset-type-group/list" class="nav-link text-white"><p>Nhóm tài sản</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('asset-type/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/asset-type-group/list" class="nav-link text-white"><p>Loại tài sản</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('industry/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/industry/list" class="nav-link text-white"><p>Ngành hàng</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('supplier/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/supplier/list" class="nav-link text-white"><p>Nhà cung cấp</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('rbac/role/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/rbac/role/list" class="nav-link text-white"><p>Vai trò</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('rbac/permission/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/rbac/permission/list" class="nav-link text-white"><p>Quyền ứng dụng</p></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="fa-solid fa-gear"></i>
                        <p>Quản lý tài sản<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview tw-list-disc {{ request()->is('contract/*') || request()->is('contract-appendix/*') || request()->is('shopping_plan/*') ? 'show' : '' }}">
                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('contract/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/asset-type-group/list" class="nav-link text-white"><p>Hợp đồng</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('contract-appendix/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/industry/list" class="nav-link text-white"><p>Phụ lục hợp đồng</p></a>
                        </li>

                        <li class="nav-item rounded hover:tw-bg-white/10 {{request()->is('shopping_plan/*') ? 'tw-bg-white/10' : ''}}">
                            <a href="/supplier/list" class="nav-link text-white"><p>Kế hoạch mua sắm</p></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>
