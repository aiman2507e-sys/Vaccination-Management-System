<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parent Portal') - e-Vaccination Management</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
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
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col" x-data="{ mobileNav: false }">

    <!-- Top Navigation Bar for Parents -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs no-print">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 xl:px-8">
            <div class="flex justify-between items-center h-19 gap-4">
                
                <!-- Brand -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('parent.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-400 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                            <i class="fa-solid fa-baby text-lg"></i>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-slate-900 tracking-tight">Parent<span class="text-brand-600">Portal</span></span>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Infant Immunization</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav aria-label="Parent navigation" class="hidden xl:flex items-center gap-1 text-[13px] font-semibold">
                    <a href="{{ route('parent.dashboard') }}" @if(request()->routeIs('parent.dashboard')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.dashboard') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-house text-xs"></i> Dashboard
                    </a>
                    <a href="{{ route('parent.children.index') }}" @if(request()->routeIs('parent.children.*')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.children.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-children text-xs"></i> Children
                    </a>
                    <a href="{{ route('parent.vaccination.dates') }}" @if(request()->routeIs('parent.vaccination.dates')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.vaccination.dates') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-bell text-xs"></i> Schedule
                    </a>
                    <a href="{{ route('parent.book.hospital') }}" @if(request()->routeIs('parent.book.hospital*')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.book.hospital*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-calendar-plus text-xs"></i> Appointments
                    </a>
                    <a href="{{ route('parent.reports.index') }}" @if(request()->routeIs('parent.reports.*')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.reports.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-file-medical text-xs"></i> Reports
                    </a>
                    <a href="{{ route('parent.profile') }}" @if(request()->routeIs('parent.profile')) aria-current="page" @endif class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 transition-colors {{ request()->routeIs('parent.profile') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }}">
                        <i class="fa-solid fa-user-circle text-xs"></i> Profile
                    </a>
                </nav>

                <!-- Actions / User -->
                <div class="hidden xl:flex items-center gap-3">
                    <a href="{{ route('parent.book.hospital') }}" class="inline-flex items-center gap-2 whitespace-nowrap bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-colors shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>Book Vaccine</span>
                    </a>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="max-w-30 truncate text-xs font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600" title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex xl:hidden items-center">
                    <button type="button" aria-label="Toggle navigation" :aria-expanded="mobileNav" @click="mobileNav = !mobileNav" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl">
                        <i class="fa-solid" :class="mobileNav ? 'fa-xmark text-xl' : 'fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileNav" x-cloak class="xl:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-5">
            <nav aria-label="Parent navigation" class="grid grid-cols-1 sm:grid-cols-2 gap-1">
                <a href="{{ route('parent.dashboard') }}" @if(request()->routeIs('parent.dashboard')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.dashboard') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-house w-5 text-center mr-2"></i>Dashboard</a>
                <a href="{{ route('parent.children.index') }}" @if(request()->routeIs('parent.children.*')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.children.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-children w-5 text-center mr-2"></i>Children</a>
                <a href="{{ route('parent.vaccination.dates') }}" @if(request()->routeIs('parent.vaccination.dates')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.vaccination.dates') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-bell w-5 text-center mr-2"></i>Vaccination Schedule</a>
                <a href="{{ route('parent.book.hospital') }}" @if(request()->routeIs('parent.book.hospital*')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.book.hospital*') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-calendar-plus w-5 text-center mr-2"></i>Appointments</a>
                <a href="{{ route('parent.reports.index') }}" @if(request()->routeIs('parent.reports.*')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.reports.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-file-medical w-5 text-center mr-2"></i>Vaccination Reports</a>
                <a href="{{ route('parent.profile') }}" @if(request()->routeIs('parent.profile')) aria-current="page" @endif class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs('parent.profile') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}"><i class="fa-solid fa-user-circle w-5 text-center mr-2"></i>Profile</a>
            </nav>
            <a href="{{ route('parent.book.hospital') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-700 sm:w-auto">
                <i class="fa-solid fa-plus"></i> Book Vaccine
            </a>
            <div class="mt-4 border-t border-slate-100 pt-2 xl:hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-rose-600 font-medium"><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Log Out</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="no-print">
            @include('partials.alerts')
        </div>
        @yield('content')
    </main>

    <!-- Simple Parent Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400 no-print mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <p>e-Vaccination Parent Care Portal &bull; Protecting infants through timely immunization schedules.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
