<div class="header-top">
    <div class="container">
        <div class="logo" style="margin-top:-15px;margin-bottom:-10px;">
            <a href="{{ route('home') }}">
                <img src="{{ asset('') }}assets/images/logo/logo.png" alt="Logo" style="width:100%;height:70px;">
            </a>
        </div>
        <div class="header-top-right">

            <div class="dropdown">
                <a href="#" id="topbarUserDropdown"
                    class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="avatar avatar-md2">
                        <img src="{{ asset('') }}assets/images/faces/1.jpg" alt="Avatar">
                    </div>
                    <div class="text">
                        <h6 class="user-dropdown-name">{{ Auth::user()->name }}</h6>
                        <p class="user-dropdown-status text-sm text-muted">{{ Auth::user()->type }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person"></i>
                            My Account
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-cog"></i>
                            Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-in-left me-2"></i>
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Burger button responsive -->
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </div>
    </div>
</div>
