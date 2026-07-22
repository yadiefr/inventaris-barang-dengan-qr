<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Inventaris Barang</title>
    <!-- Stylesheets -->
    @php
        $cssPath = public_path('css/app.css');
        $version = file_exists($cssPath) ? filemtime($cssPath) : time();
    @endphp
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ $version }}">
    @yield('styles')
</head>
<body>

    <!-- Mobile Top Bar -->
    <div class="mobile-navbar">
        <button id="menu-toggle" class="btn-menu-toggle" aria-label="Buka Menu">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div class="mobile-brand">
            <div class="brand-icon">I</div>
            <span class="brand-name">Inventaris Barang</span>
        </div>
        <div class="mobile-user">
            @auth
            <div class="user-avatar" style="width: 30px; height: 30px; font-size: 13px; box-shadow: none;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            @endauth
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">I</div>
            <div class="brand-name">Inventaris Barang</div>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ Route::is('items.index') || Route::is('items.create') || Route::is('items.edit') || Route::is('items.show') ? 'active' : '' }}">
                <a href="{{ route('items.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4 8 4 8-4zM4 12l8 4 8-4M4 17l8 4 8-4"/></svg>
                    <span>Daftar Barang</span>
                </a>
            </li>
            <li class="{{ Route::is('categories.index') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="{{ Route::is('items.scan') ? 'active' : '' }}">
                <a href="{{ route('items.scan') }}">
                    <svg viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2m10 0h2a2 2 0 0 1 2 2v2m0 10v2a2 2 0 0 1-2 2h-2m-10 0H5a2 2 0 0 1-2-2v-2M7 12h10M12 7v10"/></svg>
                    <span>Scan QR Code</span>
                </a>
            </li>
        </ul>
        <!-- Sidebar Footer -->
        @auth
        <div class="sidebar-footer">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-email">{{ auth()->user()->email }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <button class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Keluar">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            </button>
        </div>
        @endauth
    </aside>

    <!-- Main Workspace -->
    <main>
        <!-- Header Navbar -->
        <header>
            <div class="page-title">
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p>@yield('page_subtitle', 'Selamat datang kembali di sistem inventaris barang.')</p>
            </div>
            <div class="header-actions">
                <button id="theme-toggle" class="btn btn-secondary" title="Ganti Tema" aria-label="Ganti Tema">
                    <svg id="theme-icon-dark" class="btn-svg" style="display: none;" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <svg id="theme-icon-light" class="btn-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                </button>
                <a href="{{ route('items.scan') }}" class="btn btn-secondary">
                    <svg class="btn-svg" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2m10 0h2a2 2 0 0 1 2 2v2m0 10v2a2 2 0 0 1-2 2h-2m-10 0H5a2 2 0 0 1-2-2v-2M7 12h10M12 7v10"/></svg>
                    <span>Scan QR</span>
                </a>
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <svg class="btn-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah Barang</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Main Content View -->
        @yield('content')
    </main>

    <!-- Scripts Area -->
    @yield('scripts')

    <!-- Responsive Sidebar Scripts -->
    <script>
        // Set tema awal sebelum DOM load penuh untuk mencegah flash
        const currentTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Theme Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIconDark = document.getElementById('theme-icon-dark');
            const themeIconLight = document.getElementById('theme-icon-light');

            function applyTheme(theme) {
                if (themeIconDark && themeIconLight) {
                    if (theme === 'light') {
                        themeIconDark.style.display = 'block';
                        themeIconLight.style.display = 'none';
                    } else {
                        themeIconDark.style.display = 'none';
                        themeIconLight.style.display = 'block';
                    }
                }
            }

            applyTheme(currentTheme);

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    const newTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    applyTheme(newTheme);
                });
            }

            if (sidebar) {
                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    if (overlay) {
                        overlay.classList.toggle('show');
                    }
                }
                
                if (menuToggle) {
                    menuToggle.addEventListener('click', toggleSidebar);
                }
                
                if (overlay) {
                    overlay.addEventListener('click', toggleSidebar);
                }
                
                // Tambahkan tombol tutup (close) di sidebar brand khusus layar mobile
                const sidebarBrand = sidebar.querySelector('.sidebar-brand');
                if (sidebarBrand) {
                    const closeBtn = document.createElement('button');
                    closeBtn.className = 'btn-close-sidebar';
                    closeBtn.setAttribute('aria-label', 'Tutup Menu');
                    closeBtn.innerHTML = `
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    `;
                    closeBtn.addEventListener('click', toggleSidebar);
                    sidebarBrand.appendChild(closeBtn);
                }
            }
        });
    </script>
</body>
</html>
