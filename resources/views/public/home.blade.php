@extends('layouts.app')

@section('title', 'Save Lives with Infant e-Vaccination')

@section('content')
<!-- Hero Section with Clean Pediatric Clinic Video Background -->
<section class="relative overflow-hidden pt-16 sm:pt-20 pb-20 sm:pb-28 border-b border-slate-200/80 min-h-[640px] flex items-center">
    <!-- Clean Video Background (Doctor & Baby in Clinic) -->
    <video 
        autoplay 
        muted 
        loop 
        playsinline 
        preload="auto"
        class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none -z-20"
        poster="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=1920&q=80"
    >
        <source src="{{ asset('videos/baby-doctor.mp4') }}" type="video/mp4">
        <source src="https://assets.mixkit.co/videos/4753/4753-720.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Clean, Bright Medical Light Wash (No Dark Overlay) -->
    <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/85 to-white/40 sm:to-white/20 backdrop-blur-[0.5px] -z-10"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Headline & Problem/Solution Intro -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 bg-emerald-100/90 border border-emerald-300/60 text-emerald-800 px-3.5 py-1.5 rounded-full text-xs font-bold tracking-wide backdrop-blur-sm shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Infant e-Vaccination & Pediatric Care</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                    Every Child Deserves <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-teal-600 to-emerald-500">Timely Immunization</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-700 leading-relaxed font-medium">
                    Under the manual system, thousands of infants miss vital vaccines due to school absences and logistical delays—increasing the risk of polio and other preventable diseases. 
                    Our <span class="font-bold text-slate-900">e-Vaccination System</span> bridges parents, administrators, and hospitals into one seamless, life-saving digital schedule.
                </p>

                <!-- Quick Action Buttons -->
                <div class="pt-4 flex flex-wrap gap-4 items-center">
                    <a href="{{ route('register.parent') }}" class="inline-flex items-center gap-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-brand-600/25 hover:shadow-xl hover:scale-[1.02] transition-all text-sm sm:text-base">
                        <i class="fa-solid fa-baby"></i>
                        <span>Register Your Child Now</span>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 bg-white/95 hover:bg-white text-slate-800 border border-slate-300/90 font-bold px-6 py-3.5 rounded-2xl shadow-sm hover:shadow transition-all text-sm sm:text-base">
                        <i class="fa-solid fa-arrow-right-to-bracket text-brand-600"></i>
                        <span>Portal Sign In</span>
                    </a>
                </div>

                <!-- Features Badges -->
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-slate-200/90">
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-brand-600">{{ $stats['children_protected'] }}+</div>
                        <p class="text-xs font-bold text-slate-600 mt-0.5">Doses Administered</p>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['active_hospitals'] }}</div>
                        <p class="text-xs font-bold text-slate-600 mt-0.5">Partner Clinics</p>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $stats['available_vaccines'] }}</div>
                        <p class="text-xs font-bold text-slate-600 mt-0.5">WHO Vaccines</p>
                    </div>
                </div>
            </div>

            <!-- Right Interactive Quick Access Card -->
            <div class="lg:col-span-5">
                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/80 shadow-2xl shadow-slate-300/40 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Vaccination Portal Access</h3>
                            <p class="text-xs text-slate-500">Select your role to explore the system</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>

                    <!-- Role Card 1: Parent -->
                    <a href="{{ route('register.parent') }}" class="block p-4 rounded-2xl border border-slate-200 hover:border-brand-500 hover:bg-brand-50/40 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-person-breastfeeding"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm group-hover:text-brand-600 transition-colors">Parents & Guardians</h4>
                                    <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Register children, view upcoming dates, book hospital slots & download vaccination records.</p>
                            </div>
                        </div>
                    </a>

                    <!-- Role Card 2: Hospital -->
                    <a href="{{ route('register.hospital') }}" class="block p-4 rounded-2xl border border-slate-200 hover:border-teal-500 hover:bg-teal-50/40 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-hospital"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm group-hover:text-teal-600 transition-colors">Hospitals & Centers</h4>
                                    <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Receive admin-approved appointments and update vaccination status (Vaccinated / Missed).</p>
                            </div>
                        </div>
                    </a>

                    <!-- Role Card 3: Admin -->
                    <a href="{{ route('login') }}" class="block p-4 rounded-2xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/40 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 text-lg group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors">Health Administrators</h4>
                                    <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Review parent requests, manage hospitals, monitor dates & export vaccination reports.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Modules & Workflow Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-brand-600 font-bold text-xs uppercase tracking-wider bg-brand-50 px-3 py-1 rounded-full">System Architecture</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">How the e-Vaccination Flow Operates</h2>
            <p class="text-slate-600 text-sm mt-2">A laddered, multi-role synchronization ensuring no infant misses an immunization milestone.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-7 relative">
                <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-lg mb-5 shadow-md shadow-brand-600/30">
                    1
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Parent Booking</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Parents register child demographic and birth details. The dashboard automatically calculates upcoming milestone dates, allowing parents to select a clinic and submit a booking request.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-200/60 text-xs text-brand-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Status: <span class="font-mono font-bold">Pending Review</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-7 relative">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5 shadow-md shadow-indigo-600/30">
                    2
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Admin Approval</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Health Administrators review incoming requests, check hospital slot capacity and vaccine stock availability, then either Approve or Reject with constructive administrative notes.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-200/60 text-xs text-indigo-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Status: <span class="font-mono font-bold">Approved / Scheduled</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-7 relative">
                <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-bold text-lg mb-5 shadow-md shadow-teal-600/30">
                    3
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Hospital Administration</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Clinics view approved appointments in their queue. Following injection, staff records the batch lot number and marks the status as Vaccinated, issuing an official digital report.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-200/60 text-xs text-teal-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Status: <span class="font-mono font-bold">Vaccinated (Completed)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Available Vaccines Preview -->
<section class="py-16 bg-slate-50 border-t border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Essential Protection</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Pediatric Vaccines Registry</h2>
            </div>
            <a href="{{ route('public.schedule') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700">
                <span>View Full Schedule</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vaccines as $vac)
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono">{{ $vac->short_code }}</span>
                        @if($vac->status === 'available')
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Available</span>
                        @else
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">Unavailable</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $vac->name }}</h4>
                    <p class="text-xs text-brand-700 font-semibold mt-1"><i class="fa-solid fa-shield-virus mr-1"></i> {{ $vac->target_disease }}</p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span><i class="fa-regular fa-clock mr-1"></i> {{ $vac->recommended_age_label }}</span>
                        <span><i class="fa-solid fa-vial mr-1"></i> {{ $vac->dosage }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 bg-gradient-to-r from-brand-700 to-teal-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Protect Your Child Today</h2>
        <p class="text-brand-100 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
            Never miss an infant vaccination due to manual tracking errors. Register your account and start scheduling appointments at verified municipal hospitals.
        </p>
        <div class="pt-2 flex flex-wrap justify-center gap-4">
            <a href="{{ route('register.parent') }}" class="bg-white hover:bg-slate-100 text-brand-800 font-bold px-8 py-3.5 rounded-2xl shadow-lg transition-all text-sm sm:text-base">
                Get Started as a Parent
            </a>
            <a href="{{ route('public.hospitals') }}" class="bg-brand-900/40 hover:bg-brand-900/60 text-white border border-brand-400/40 font-bold px-8 py-3.5 rounded-2xl transition-all text-sm sm:text-base">
                Explore Partner Hospitals
            </a>
        </div>
    </div>
</section>
@endsection
