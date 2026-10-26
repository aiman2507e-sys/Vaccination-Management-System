<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hospital Portal') - e-Vaccination Management</title>
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
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
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
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white flex flex-col transition-transform duration-300 ease-in-out shrink-0 shadow-xl">
        
        <!-- Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950/40">
            <a href="{{ route('hospital.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-teal-500/20">
                    <i class="fa-solid fa-hospital text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-sm tracking-tight leading-tight truncate max-w-[170px]">{{ auth()->user()->name }}</h1>
                    <span class="text-[10px] font-semibold tracking-wider uppercase text-teal-400">Clinical Facility</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Links -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 text-sm font-medium">
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Operations</div>

            <a href="{{ route('hospital.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('hospital.dashboard') ? 'bg-teal-600 text-white font-semibold shadow-md shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center text-base"></i>
                <span>Hospital Dashboard</span>
            </a>

            <!-- Update Vaccine Status / Appointments Queue (SRS Module) -->
            <a href="{{ route('hospital.appointments.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('hospital.appointments.*') ? 'bg-teal-600 text-white font-semibold shadow-md shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-syringe w-5 text-center text-base"></i>
                    <span>Vaccine Appointments</span>
                </div>
                @php
                    $hospitalObj = auth()->user()->hospital ?? \App\Models\Hospital::where('email', auth()->user()->email)->first();
                    $approvedCount = $hospitalObj ? \App\Models\Appointment::where('hospital_id', $hospitalObj->id)->where('status', 'approved')->count() : 0;
                @endphp
                @if($approvedCount > 0)
                    <span class="bg-teal-400 text-slate-950 font-bold text-xs px-2 py-0.5 rounded-full">{{ $approvedCount }}</span>
                @endif
            </a>

            <div class="pt-4 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Hospital Settings</div>

            <!-- Profile & Facility Info -->
            <a href="{{ route('hospital.profile') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('hospital.profile') ? 'bg-teal-600 text-white font-semibold shadow-md shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-building-user w-5 text-center text-base"></i>
                <span>Facility Profile</span>
            </a>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-9 h-9 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center font-bold text-sm">
                    HP
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">Vaccination Center</p>
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
                    <h2 class="text-lg font-bold text-slate-900 leading-tight">@yield('page_title', 'Hospital Portal')</h2>
                    <p class="text-xs text-slate-500">Vaccine Administration & Clinical Status Updates</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-teal-50 text-teal-700 border border-teal-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                    Clinic Online
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
