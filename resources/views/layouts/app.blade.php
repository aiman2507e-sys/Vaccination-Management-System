<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'e-Vaccination') - Child Vaccination Management System</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
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
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Top Navigation Bar -->
    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/80 shadow-xs" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-syringe text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-brand-600 transition-colors">
                            e-Vaccine<span class="text-brand-500">Care</span>
                        </span>
                        <p class="text-[11px] font-semibold text-slate-400 tracking-wider uppercase">Vaccination Management</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Home
                    </a>
                    <a href="{{ route('public.schedule') }}" class="text-sm font-semibold {{ request()->routeIs('public.schedule') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Vaccine Schedule
                    </a>
                    <a href="{{ route('public.hospitals') }}" class="text-sm font-semibold {{ request()->routeIs('public.hospitals') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Hospitals & Clinics
                    </a>
                </nav>

                <!-- Auth Buttons / User Pill -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @php
                            $dashboardRoute = match(auth()->user()->role) {
                                'admin' => route('admin.dashboard'),
                                'hospital' => route('hospital.dashboard'),
                                'parent' => route('parent.dashboard'),
                                default => route('home'),
                            };
                            $roleLabel = match(auth()->user()->role) {
                                'admin' => 'Admin Panel',
                                'hospital' => 'Hospital Portal',
                                'parent' => 'Parent Portal',
                                default => 'Dashboard',
                            };
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2 bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-xs">
                            <i class="fa-solid fa-gauge text-brand-600"></i>
                            <span>{{ $roleLabel }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 transition-colors" title="Logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-brand-600 px-4 py-2.5 transition-colors">
                            Sign In
                        </a>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('register.parent') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md shadow-brand-600/20 hover:shadow-lg">
                                <i class="fa-solid fa-user-plus text-xs"></i>
                                <span>Parent Register</span>
                            </a>
                            <a href="{{ route('register.hospital') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all shadow-xs">
                                <i class="fa-solid fa-hospital text-xs"></i>
                                <span>Hospital Join</span>
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center">
                    <button @click="mobileOpen = !mobileOpen" type="button" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid" :class="mobileOpen ? 'fa-xmark text-xl' : 'fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileOpen" x-cloak class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-medium text-slate-700 hover:bg-brand-50 hover:text-brand-600">Home</a>
            <a href="{{ route('public.schedule') }}" class="block px-3 py-2 rounded-lg font-medium text-slate-700 hover:bg-brand-50 hover:text-brand-600">Vaccine Schedule</a>
            <a href="{{ route('public.hospitals') }}" class="block px-3 py-2 rounded-lg font-medium text-slate-700 hover:bg-brand-50 hover:text-brand-600">Hospitals & Clinics</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ $dashboardRoute ?? route('login') }}" class="w-full text-center bg-brand-600 text-white py-2.5 rounded-xl font-semibold text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center border border-slate-300 text-slate-700 py-2.5 rounded-xl font-semibold text-sm">Sign In</a>
                    <a href="{{ route('register.parent') }}" class="w-full text-center bg-brand-600 text-white py-2.5 rounded-xl font-semibold text-sm">Parent Register</a>
                    <a href="{{ route('register.hospital') }}" class="w-full text-center bg-slate-900 text-white py-2.5 rounded-xl font-semibold text-sm">Hospital Join</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow">
        @if ($errors->any() || session('success') || session('error') || session('info'))
        <div class="container mx-auto px-4 pt-6">
            @include('partials.alerts')
        </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: Project Identity -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-syringe text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">e-Vaccine<span class="text-brand-400">Care</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        An integrated e-Vaccination management platform saving children's lives through systematic immunization tracking and hospital scheduling.
                    </p>
                    <div class="text-xs text-brand-400 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-award"></i>
                        <span>Aptech eProject Standard Implementation</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wide uppercase mb-4">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('public.schedule') }}" class="hover:text-white transition-colors">Pediatric Schedule</a></li>
                        <li><a href="{{ route('public.hospitals') }}" class="hover:text-white transition-colors">Authorized Clinics</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Unified Login</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Roles -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wide uppercase mb-4">Portals</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-shield-halved text-brand-400 mr-2 text-xs"></i>Admin Panel</a></li>
                        <li><a href="{{ route('register.parent') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-user text-brand-400 mr-2 text-xs"></i>Parent Registration</a></li>
                        <li><a href="{{ route('register.hospital') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-hospital text-brand-400 mr-2 text-xs"></i>Hospital Onboarding</a></li>
                    </ul>
                </div>

                <!-- Col 4: Child Health Helpline -->
                <div class="bg-slate-800/60 p-5 rounded-2xl border border-slate-700/60">
                    <h4 class="text-white font-bold text-sm mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-emerald-400"></i>
                        Immunization Helpdesk
                    </h4>
                    <p class="text-xs text-slate-400 mb-3">Toll-free emergency helpline for infant immunization guidance:</p>
                    <p class="text-lg font-extrabold text-emerald-400 tracking-wider">1800-VACCINE-01</p>
                    <p class="text-[11px] text-slate-400 mt-2">Available 24/7 Mon - Sun</p>
                </div>
            </div>

            <div class="pt-10 mt-10 border-t border-slate-800 text-center md:flex md:justify-between items-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Vaccination Management System. Built with Laravel & MySQL.</p>
                <p class="mt-2 md:mt-0">eProject Live Learning Environment &bull; All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
