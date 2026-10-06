<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Administrator - CMS SMKN 13 Bandung' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
            display: none;
        }
        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
        const isHidden = sidebar.classList.contains('hidden');
        if (isHidden) {
            openAdminSidebar();
        } else {
            closeAdminSidebar();
        }
    }

    function openAdminSidebar() {
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

    function closeAdminSidebar() {
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

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeAdminSidebar();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdminSidebar();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('#adminSidebar a');
        links.forEach(function(link) {
            link.addEventListener('click', closeAdminSidebar);
        });
    });
    </script>
</body>
</html>
