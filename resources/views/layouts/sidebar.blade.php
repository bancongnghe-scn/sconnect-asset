<<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar bg-sc" :class="activeSidebarSm ? 'tw-left-0' : 'menu'">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link bg-sc text-white" href="{{route('home')}}">
                <i class="fa-solid fa-house text-white"></i>
                <span>Trang chủ</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link bg-sc text-white" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-gear text-white" ></i>
                <span>Cài đặt</span>
                <i class="fa-solid fa-arrow-right tw-ml-2 text-white" ></i>
            </a>
            <ul
                id="setting-nav"
                class="nav-content collapse
                        {{ request()->is('asset-type-group/*')
                        || request()->is('asset-type/*')
                        || request()->is('industry/*')
                        || request()->is('supplier/*')
                        || request()->is('rbac/*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li class="hover:tw-bg-white/10 {{request()->is('asset-type-group/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/asset-type-group/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Nhóm tài sản</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('asset-type/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/asset-type/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Loại tài sản</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('industry/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/industry/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Ngành hàng</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('supplier/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/supplier/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Nhà cung cấp</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('rbac/role/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/rbac/role/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Vai trò</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('rbac/permission/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/rbac/permission/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Quyền ứng dụng</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
        <li class="nav-item">
            <a class="nav-link bg-sc text-white" data-bs-target="#asset-nav" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-gear text-white"></i>
                <span>Quản lý tài sản</span>
                <i class="fa-solid fa-arrow-right tw-ml-2 text-white"></i>
            </a>
            <ul id="asset-nav" class="nav-content collapse {{ request()->is('contract/*') || request()->is('contract-appendix/*') || request()->is('shopping_plan/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li class="hover:tw-bg-white/10 {{request()->is('contract/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/contract/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Hợp đồng</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('contract-appendix/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/contract-appendix/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Phụ lục hợp đồng</span>
                    </a>
                </li>
                <li class="hover:tw-bg-white/10 {{request()->is('shopping_plan/*') ? 'tw-bg-white/10' : ''}}">
                    <a href="/shopping_plan/list" class="text-white text-decoration-none">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Kế hoạch mua sắm</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
    </ul>
</aside><!-- End Sidebar-->

<style>
    @media screen and (max-width: 1199px){
        .menu {
            left: -300px;
        }
    }
</style>
>
