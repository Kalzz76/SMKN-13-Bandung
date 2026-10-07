<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Guru - SMKN 13 Bandung' }}</title>
    @if(!empty($sitePengaturan?->logo_url))
        <link rel="icon" type="image/png" href="{{ $sitePengaturan->logo_url }}">
        <link rel="apple-touch-icon" href="{{ $sitePengaturan->logo_url }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        /* Sembunyikan Scrollbar di Seluruh Halaman Guru & Sidebar */
        ::-webkit-scrollbar {
            width: 0px !important;
            height: 0px !important;
            display: none !important;
        }
        * {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        .no-scrollbar::-webkit-scrollbar,
        #guruMainScroll::-webkit-scrollbar,
        #guruSidebar::-webkit-scrollbar,
        #guruSidebar *::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
        }
        .no-scrollbar,
        #guruMainScroll,
        #guruSidebar,
        #guruSidebar * {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        /* Animasi Buka Tutup Mini Sidebar (Icon-Only Mode) */
        #guruSidebar {
            transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
        }
        @media (min-width: 768px) {
            .sidebar-collapsed {
                width: 5rem !important; /* 80px */
            }
            .sidebar-collapsed .sidebar-text,
            .sidebar-collapsed .sidebar-category-header {
                display: none !important;
                opacity: 0;
            }
            .sidebar-collapsed .sidebar-category-divider {
                display: block !important;
            }
            .sidebar-collapsed .sidebar-header-box {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                justify-content: center !important;
            }
            .sidebar-collapsed .sidebar-menu-link {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: 2.75rem !important;
                height: 2.75rem !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .sidebar-collapsed .sidebar-menu-link i {
                margin: 0 !important;
                font-size: 1.15rem !important;
            }
            .sidebar-collapsed .sidebar-logout-btn {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: 2.75rem !important;
                height: 2.75rem !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .sidebar-collapsed .sidebar-logout-btn span {
                display: none !important;
            }
            .sidebar-collapsed .sidebar-logout-btn i {
                margin: 0 !important;
                font-size: 1.15rem !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden flex flex-col md:flex-row">
    {{-- Mobile Sidebar Backdrop with Blur --}}
    <div id="guruSidebarBackdrop" onclick="closeGuruSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-all duration-200" style="touch-action: none;"></div>

    @include('partials.guru-sidebar')

    <div id="guruMainContent" class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden transition-all duration-200">
        @include('partials.guru-header')

        <main id="guruMainScroll" class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('partials.modal-pesan')
    @include('partials.modal-logout')

    <script>
    function toggleGuruSidebar() {
        const sidebar = document.getElementById('guruSidebar');
        if (!sidebar) return;

        if (window.innerWidth >= 768) {
            // Mode Desktop: Toggle Mini Sidebar (Icon-Only)
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            if (isCollapsed) {
                sidebar.classList.remove('sidebar-collapsed');
                localStorage.setItem('guru_sidebar_collapsed', 'false');
            } else {
                sidebar.classList.add('sidebar-collapsed');
                localStorage.setItem('guru_sidebar_collapsed', 'true');
            }
        } else {
            // Mode Mobile: Toggle Off-canvas Drawer
            const isHiddenMobile = sidebar.classList.contains('hidden');
            if (isHiddenMobile) {
                openGuruSidebarMobile();
            } else {
                closeGuruSidebarMobile();
            }
        }
    }

    function openGuruSidebarMobile() {
        const sidebar = document.getElementById('guruSidebar');
        const backdrop = document.getElementById('guruSidebarBackdrop');
        const content = document.getElementById('guruMainContent');
        const main = document.getElementById('guruMainScroll');

        if (sidebar) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('flex');
        }
        if (backdrop) {
            backdrop.classList.remove('hidden');
        }
        if (content) {
            content.classList.add('blur-[3px]', 'pointer-events-none');
        }
        if (main) {
            main.classList.add('overflow-hidden');
        }
        document.body.classList.add('overflow-hidden');
    }

    function closeGuruSidebarMobile() {
        const sidebar = document.getElementById('guruSidebar');
        const backdrop = document.getElementById('guruSidebarBackdrop');
        const content = document.getElementById('guruMainContent');
        const main = document.getElementById('guruMainScroll');

        if (sidebar && window.innerWidth < 768) {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex');
        }
        if (backdrop) {
            backdrop.classList.add('hidden');
        }
        if (content) {
            content.classList.remove('blur-[3px]', 'pointer-events-none');
        }
        if (main) {
            main.classList.remove('overflow-hidden');
        }
        document.body.classList.remove('overflow-hidden');
    }

    function openGuruSidebar() {
        openGuruSidebarMobile();
    }

    function closeGuruSidebar() {
        closeGuruSidebarMobile();
    }

    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('guruSidebar');
        if (!sidebar) return;

        if (window.innerWidth >= 768) {
            closeGuruSidebarMobile();
            if (localStorage.getItem('guru_sidebar_collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
            }
            sidebar.classList.remove('hidden');
            sidebar.classList.add('md:flex');
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            sidebar.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGuruSidebarMobile();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('guruSidebar');
        if (sidebar && window.innerWidth >= 768) {
            if (localStorage.getItem('guru_sidebar_collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            }
        }

        const links = document.querySelectorAll('#guruSidebar a');
        links.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    closeGuruSidebarMobile();
                }
            });
        });
    });
    </script>
</body>
</html>
