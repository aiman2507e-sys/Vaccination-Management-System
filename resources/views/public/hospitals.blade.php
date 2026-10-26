@extends('layouts.app')

@section('title', 'Participating Hospitals & Immunization Clinics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10 text-center max-w-3xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full">Healthcare Centers</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">Authorized Vaccination Hospitals</h1>
        <p class="text-slate-600 text-sm sm:text-base mt-2">
            Locate approved municipal hospitals and clinics equipped with cold-chain storage and certified pediatric vaccination specialists.
        </p>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('public.hospitals') }}" class="mt-6 max-w-xl mx-auto flex items-center gap-2">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by hospital name, city, or district..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-xs">
            </div>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition-all shadow-md shadow-brand-600/20">
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('public.hospitals') }}" class="p-3 bg-slate-200 text-slate-600 hover:text-slate-900 rounded-2xl text-sm" title="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Hospital Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($hospitals as $hosp)
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xl shrink-0">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active Facility
                        </span>
                    </div>

                    <h3 class="font-bold text-lg text-slate-900 leading-snug">{{ $hosp->name }}</h3>
                    <p class="text-xs font-semibold text-brand-600 mt-1"><i class="fa-solid fa-location-dot mr-1"></i> {{ $hosp->location }}, {{ $hosp->city }}</p>

                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-slate-400 w-4 text-center"></i>
                            <span class="truncate">{{ $hosp->address }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-slate-400 w-4 text-center"></i>
                            <span>{{ $hosp->phone }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-regular fa-clock text-slate-400 w-4 text-center"></i>
                            <span>{{ $hosp->operating_hours }}</span>
                        </p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    @auth
                        @if(auth()->user()->isParent())
                            <a href="{{ route('parent.book.hospital', ['hospital_id' => $hosp->id]) }}" class="w-full text-center inline-flex justify-center items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-xs">
                                <i class="fa-solid fa-calendar-check"></i>
                                <span>Book Appointment Here</span>
                            </a>
                        @else
                            <span class="block text-center text-xs text-slate-400 py-2">Hospital Center</span>
                        @endif
                    @else
                        <a href="{{ route('register.parent') }}" class="w-full text-center inline-flex justify-center items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-xs">
                            <i class="fa-solid fa-baby"></i>
                            <span>Register to Book Here</span>
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
                <i class="fa-solid fa-hospital-user text-4xl text-slate-300 mb-3"></i>
                <p class="text-base font-semibold text-slate-700">No hospitals matched your search criteria.</p>
                <p class="text-xs text-slate-400 mt-1">Try searching for other areas or cities.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $hospitals->links() }}
    </div>
</div>
@endsection
