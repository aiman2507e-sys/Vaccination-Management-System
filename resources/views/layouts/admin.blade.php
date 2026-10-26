<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - e-Vaccination Management</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
    <!-- Fonts & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white flex flex-col transition-transform duration-300 ease-in-out shrink-0 shadow-xl">
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950/40">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-base tracking-tight leading-tight">Admin Console</h1>
                    <span class="text-[10px] font-semibold tracking-wider uppercase text-emerald-400">Vaccine Control</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 text-sm font-medium">
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Main</div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center text-base"></i>
                <span>Dashboard</span>
            </a>

            <!-- Requests from Parents (SRS Module) -->
            <a href="{{ route('admin.requests.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.requests.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope-open-text w-5 text-center text-base"></i>
                    <span>Parent Requests</span>
                </div>
                @php
                    $pendingBadgeCount = \App\Models\Appointment::where('status', 'pending')->count();
                @endphp
                @if($pendingBadgeCount > 0)
                    <span class="bg-amber-500 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full">{{ $pendingBadgeCount }}</span>
                @endif
            </a>

            <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">SRS Core Modules</div>

            <!-- All Child Details (SRS Module) -->
            <a href="{{ route('admin.children.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.children.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-children w-5 text-center text-base"></i>
                <span>All Child Details</span>
            </a>

            <!-- Date & Time of Vaccination (SRS Module) -->
            <a href="{{ route('admin.vaccination.dates') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.vaccination.dates') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center text-base"></i>
                <span>Vaccination Dates</span>
            </a>

            <!-- List of Vaccine (SRS Module) -->
            <a href="{{ route('admin.vaccines.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.vaccines.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-syringe w-5 text-center text-base"></i>
                <span>List of Vaccines</span>
            </a>

            <!-- Hospital Management (SRS Module) -->
            <a href="{{ route('admin.hospitals.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.hospitals.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-hospital w-5 text-center text-base"></i>
                <span>Hospital Clinics</span>
            </a>

            <!-- Booking Details (SRS Module) -->
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clipboard-list w-5 text-center text-base"></i>
                <span>Booking Details</span>
            </a>

            <!-- Report of Vaccination (SRS Module) -->
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-brand-600 text-white font-semibold shadow-md shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                <span>Vaccination Reports</span>
            </a>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-9 h-9 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-sm">
                    AD
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">System Administrator</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 transition-colors" title="Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 leading-tight">@yield('page_title', 'Admin Dashboard')</h2>
                    <p class="text-xs text-slate-500">Vaccination Management System &bull; SRS Full-Stack Engine</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-xl transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Public Site</span>
                </a>
                <div class="w-px h-6 bg-slate-200"></div>
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Admin Online
                </span>
            </div>
        </header>

        <!-- Body -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            @include('partials.alerts')
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
