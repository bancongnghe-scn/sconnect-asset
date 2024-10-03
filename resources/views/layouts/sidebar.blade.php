<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{route('home')}}">
                <i class="fa-solid fa-house"></i>
                <span>Trang chủ</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-gear"></i>
                <span>Cài đặt</span>
                <i class="fa-solid fa-arrow-right tw-ml-2"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('asset.type-group.list')}}">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Nhóm tài sản</span>
                    </a>
                </li>
                <li>
                    <a href="/asset-type/list">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Loại tài sản</span>
                    </a>
                </li>
                <li>
                    <a href="/industry/list">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Ngành hàng</span>
                    </a>
                </li>
                <li>
                    <a href="/supplier/list">
                        <i class="fa-solid fa-glass-water-droplet"></i><span>Nhà cung cấp</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#asset-nav" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-gear"></i>
                <span>Quản lý tài sản</span>
                <i class="fa-solid fa-arrow-right tw-ml-2"></i>
            </a>
            <ul id="asset-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/contract/list">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Hợp đồng</span>
                    </a>
                </li>
                <li>
                    <a href="/contract-appendix/list">
                        <i class="fa-solid fa-whiskey-glass"></i><span>Phụ lục hợp đồng</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
    </ul>

</aside><!-- End Sidebar-->
