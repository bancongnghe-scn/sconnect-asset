<!-- ======= Header ======= -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-sc px-2">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fa-solid fa-table-columns"></i></a>
        </li>
    </ul>

    <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav mr-auto"></ul>
        <ul class="navbar-nav eo-top-right-nav d-flex justify-content-end">
            <li class="nav-item dropdown user-menu pt-1 pl-2" style="border-radius: 30px; background-color: rgba(255,255,255,0.1)">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white"
                       href="#"
                       id="userDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <img src="https://lh3.googleusercontent.com/a/ACg8ocJ-NELNG55xGTjMztdZpSLwO6SsJiKCfW1UluF-QjAddVaFSQ=s96-c"
                             class="user-image img-circle elevation-1 user-default-avatar my-sefl-avatar"
                             alt="User Avatar">
                        <span class="display-name-u ms-2">
                            {{ \Illuminate\Support\Facades\Auth::user()?->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="/logout">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>


