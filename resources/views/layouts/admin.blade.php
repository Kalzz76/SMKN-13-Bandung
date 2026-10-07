<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Administrator - CMS SMKN 13 Bandung' }}</title>
    @if(!empty($sitePengaturan?->logo_url))
        <link rel="icon" type="image/png" href="{{ $sitePengaturan->logo_url }}">
        <link rel="apple-touch-icon" href="{{ $sitePengaturan->logo_url }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        #adminMainScroll::-webkit-scrollbar,
        #adminSidebar::-webkit-scrollbar {
            width: 6px;
        }
        #adminMainScroll::-webkit-scrollbar-track,
        #adminSidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        #adminMainScroll::-webkit-scrollbar-thumb,
        #adminSidebar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }
        #adminMainScroll::-webkit-scrollbar-thumb:hover,
        #adminSidebar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
        .overflow-x-auto,
        .overflow-x-scroll,
        .custom-scrollbar-x {
            scrollbar-width: auto;
            scrollbar-color: #047857 #f1f5f9;
            -ms-overflow-style: auto;
        }
        .overflow-x-auto::-webkit-scrollbar,
        .overflow-x-scroll::-webkit-scrollbar,
        .custom-scrollbar-x::-webkit-scrollbar {
            display: block;
            height: 14px;
            width: 14px;
        }
        .overflow-x-auto::-webkit-scrollbar-track,
        .overflow-x-scroll::-webkit-scrollbar-track,
        .custom-scrollbar-x::-webkit-scrollbar-track {
            background-color: #f1f5f9;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb,
        .overflow-x-scroll::-webkit-scrollbar-thumb,
        .custom-scrollbar-x::-webkit-scrollbar-thumb {
            background-color: #047857;
            border-radius: 9999px;
            border: 3px solid #f1f5f9;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover,
        .overflow-x-scroll::-webkit-scrollbar-thumb:hover,
        .custom-scrollbar-x::-webkit-scrollbar-thumb:hover {
            background-color: #065f46;
        }

        /* Animasi Buka Tutup Mini Sidebar (Icon-Only Mode) */
        #adminSidebar {
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
    <div id="adminSidebarBackdrop" onclick="closeAdminSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-all duration-200" style="touch-action: none;"></div>

    @include('partials.admin-sidebar')

    <div id="adminMainContent" class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden transition-all duration-200">
        @include('partials.admin-header')

        <main id="adminMainScroll" class="flex-grow p-4 sm:p-8 lg:p-10 overflow-y-auto">
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
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        if (!sidebar) return;

        if (window.innerWidth >= 768) {
            // Mode Desktop: Toggle Mini Sidebar (Icon-Only)
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            if (isCollapsed) {
                sidebar.classList.remove('sidebar-collapsed');
                localStorage.setItem('admin_sidebar_collapsed', 'false');
            } else {
                sidebar.classList.add('sidebar-collapsed');
                localStorage.setItem('admin_sidebar_collapsed', 'true');
            }
        } else {
            // Mode Mobile: Toggle Off-canvas Drawer
            const isHiddenMobile = sidebar.classList.contains('hidden');
            if (isHiddenMobile) {
                openAdminSidebarMobile();
            } else {
                closeAdminSidebarMobile();
            }
        }
    }

    function openAdminSidebarMobile() {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminSidebarBackdrop');
        const content = document.getElementById('adminMainContent');
        const main = document.getElementById('adminMainScroll');

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

    function closeAdminSidebarMobile() {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminSidebarBackdrop');
        const content = document.getElementById('adminMainContent');
        const main = document.getElementById('adminMainScroll');

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

    function openAdminSidebar() {
        openAdminSidebarMobile();
    }

    function closeAdminSidebar() {
        closeAdminSidebarMobile();
    }

    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('adminSidebar');
        if (!sidebar) return;

        if (window.innerWidth >= 768) {
            closeAdminSidebarMobile();
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
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
            closeAdminSidebarMobile();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        if (sidebar && window.innerWidth >= 768) {
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            }
        }

        const links = document.querySelectorAll('#adminSidebar a');
        links.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    closeAdminSidebarMobile();
                }
            });
        });

        let liveSearchTimer = null;
        let liveAbortController = null;

        const searchForm = document.querySelector('form[method="GET"] input[name="cari"]')?.closest('form');
        if (!searchForm) return;

        const searchInput = searchForm.querySelector('input[name="cari"]');
        if (!searchInput) return;

        // Auto-wrap data container jika belum ada #liveDataContainer
        let dataContainer = document.getElementById('liveDataContainer');
        const cardParent = searchForm.closest('.bg-white') || searchForm.parentElement;
        if (!dataContainer && cardParent) {
            const siblings = Array.from(cardParent.children).filter(child => {
                return child !== searchForm && !child.classList.contains('live-filter-wrapper') && !child.querySelector('.live-filter-tab');
            });
            if (siblings.length > 0) {
                const wrapper = document.createElement('div');
                wrapper.id = 'liveDataContainer';
                wrapper.className = 'w-full space-y-6';
                siblings[0].parentNode.insertBefore(wrapper, siblings[0]);
                siblings.forEach(s => wrapper.appendChild(s));
                dataContainer = wrapper;
            }
        }

        const searchIcon = searchForm.querySelector('i.fa-magnifying-glass, i.fa-search');

        function doLiveSearch(customUrl = null) {
            if (liveAbortController) {
                liveAbortController.abort();
            }
            liveAbortController = new AbortController();

            const container = document.getElementById('liveDataContainer');
            const searchIcon = searchForm.querySelector('.fa-magnifying-glass, .fa-search, .fa-circle-notch');
            if (searchIcon) {
                searchIcon.classList.remove('fa-magnifying-glass', 'fa-search', 'text-slate-400');
                searchIcon.classList.add('fa-circle-notch', 'fa-spin', 'text-emerald-600');
            }
            if (container) {
                container.style.transition = 'opacity 0.2s';
                container.style.opacity = '0.4';
                container.style.pointerEvents = 'none';
            }

            let fetchUrl = customUrl;
            if (!fetchUrl) {
                const formData = new FormData(searchForm);
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    if (value !== '' && value !== null) {
                        params.append(key, value);
                    }
                }
                const action = searchForm.getAttribute('action') || window.location.pathname;
                fetchUrl = action + (params.toString() ? '?' + params.toString() : '');
            }

            // Atur tombol Reset
            const resetBtn = searchForm.querySelector('#liveResetBtn, a[href*="admin/"]:not([type="submit"])');
            if (resetBtn) {
                if (searchInput.value.trim() !== '') {
                    resetBtn.classList.remove('hidden');
                    resetBtn.style.display = 'flex';
                } else if (resetBtn.id === 'liveResetBtn') {
                    resetBtn.classList.add('hidden');
                    resetBtn.style.display = 'none';
                }
            }

            fetch(fetchUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: liveAbortController.signal
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContainer = doc.getElementById('liveDataContainer');
                if (newContainer && container) {
                    container.innerHTML = newContainer.innerHTML;
                } else if (container) {
                    const newCard = doc.querySelector('.bg-white');
                    if (newCard) {
                        const newSiblings = Array.from(newCard.children).filter(child => {
                            return child.tagName !== 'FORM' && !child.classList.contains('live-filter-wrapper') && !child.querySelector('.live-filter-tab');
                        });
                        if (newSiblings.length > 0) {
                            container.innerHTML = '';
                            newSiblings.forEach(s => container.appendChild(s));
                        }
                    }
                }
            })
            .catch(err => {
                if (err.name !== 'AbortError') console.error('Live search error:', err);
            })
            .finally(() => {
                const icon = searchForm.querySelector('.fa-magnifying-glass, .fa-search, .fa-circle-notch');
                if (icon) {
                    icon.classList.remove('fa-circle-notch', 'fa-spin', 'text-emerald-600');
                    icon.classList.add('fa-magnifying-glass', 'text-slate-400');
                }
                if (container) {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            });
        }

        // Live typing debounce (200ms)
        searchInput.addEventListener('input', function() {
            clearTimeout(liveSearchTimer);
            liveSearchTimer = setTimeout(() => {
                doLiveSearch();
            }, 200);
        });

        // Cegah submit reload
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(liveSearchTimer);
            doLiveSearch();
        });

        // Intercept dropdown select (misal filter kelas)
        searchForm.querySelectorAll('select').forEach(sel => {
            sel.removeAttribute('onchange');
            sel.addEventListener('change', function() {
                doLiveSearch();
            });
        });

        searchForm.addEventListener('click', function(e) {
            const resetCandidate = e.target.closest('#liveResetBtn, a[href*="admin/"]');
            if (resetCandidate && (resetCandidate.id === 'liveResetBtn' || resetCandidate.textContent.trim().toLowerCase() === 'reset')) {
                e.preventDefault();
                searchInput.value = '';
                searchForm.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                searchForm.querySelectorAll('input[type="hidden"]:not([name="_token"])').forEach(i => i.value = '');
                document.querySelectorAll('.live-filter-tab').forEach((btn, idx) => {
                    const isFirst = btn.getAttribute('data-filter-value') === '' || idx === 0;
                    if (isFirst) {
                        btn.classList.add('bg-emerald-700', 'text-white', 'shadow-sm');
                        btn.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    } else {
                        btn.classList.remove('bg-emerald-700', 'text-white', 'shadow-sm');
                        btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    }
                });
                doLiveSearch();
            }
        });

        // Intercept klik pagination pada hasil live search
        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('#liveDataContainer a[href*="page="], #liveDataContainer a.page-link, #liveDataContainer nav a');
            if (pageLink && pageLink.getAttribute('href')) {
                e.preventDefault();
                doLiveSearch(pageLink.getAttribute('href'));
            }

            // Intercept tab filter
            const filterTab = e.target.closest('.live-filter-tab');
            if (filterTab) {
                e.preventDefault();
                const filterName = filterTab.getAttribute('data-filter-name');
                const filterValue = filterTab.getAttribute('data-filter-value') || '';

                if (filterName) {
                    let hiddenInput = searchForm.querySelector(`input[name="${filterName}"]`);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = filterName;
                        searchForm.appendChild(hiddenInput);
                    }
                    hiddenInput.value = filterValue;

                    document.querySelectorAll(`.live-filter-tab[data-filter-name="${filterName}"]`).forEach(btn => {
                        btn.classList.remove('bg-emerald-700', 'text-white', 'shadow-sm');
                        btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    });
                    filterTab.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    filterTab.classList.add('bg-emerald-700', 'text-white', 'shadow-sm');

                    doLiveSearch();
                }
            }
        });

        window.resetLiveSearch = function() {
            if (searchInput) {
                searchInput.value = '';
                doLiveSearch();
            }
        };
    });
    </script>
</body>
</html>
