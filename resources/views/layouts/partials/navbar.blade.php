<nav class="main-navbar">
    <div class="container">
        <ul class="menu">
            <li class="menu-item">
                <a href="{{ route('home') }}" class="menu-link text-white">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('courses.index') }}"
                    class="menu-link {{ request()->is('courses*') ? 'text-white' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Data Mapel</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('classroom.index') }}"
                    class="menu-link {{ request()->is('classroom*') ? 'text-white' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Data Kelas</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('students.index') }}"
                    class="menu-link {{ request()->is('students*') ? 'text-white' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Siswa</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('teachers.index') }}"
                    class="menu-link {{ request()->is('teachers*') ? 'text-white' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Guru</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('questions.index') }}"
                    class="menu-link {{ request()->is('questions*') ? 'text-white' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Data Soal</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('exams.index') }}"
                    class="menu-link {{ request()->is('exams*') ? 'text-white' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Data Ujian</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('exam-students.index') }}"
                    class="menu-link {{ request()->is('exam-students*') ? 'text-white' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Ujian</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="index.html" class="menu-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Hasil Ujian</span>
                </a>
            </li>

            <li class="menu-item  has-sub">
                <a href="#" class="menu-link {{ isset($setting) ? 'text-white' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan</span>
                </a>
                <div class="submenu ">
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <li class="submenu-item  ">
                                <a href="{{ route('permissions.index') }}"
                                    class="submenu-link {{ request()->is('permissions*') ? 'text-info' : '' }}">
                                    <i class="bi bi-shield"></i>
                                    Permission
                                </a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="{{ route('roles.index') }}"
                                    class="submenu-link {{ request()->is('roles*') ? 'text-info' : '' }}">
                                    <i class="bi bi-shield"></i>
                                    Role
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a href="{{ route('users.index') }}"
                                    class="submenu-link {{ request()->is('users*') ? 'text-info' : '' }}">
                                    <i class="bi bi-person"></i>
                                    Pengguna
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</nav>
