@extends('layouts.parent')

@section('title', 'Parent Dashboard')

@section('content')
<div class="space-y-7">
    <header class="flex flex-col gap-5 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase text-brand-700">
                <i class="fa-solid fa-heart-pulse"></i> Parent portal
            </p>
            <h1 class="text-3xl font-bold text-slate-900">Welcome back, {{ $parent->name }}</h1>
            <p class="mt-1 text-sm text-slate-600">A clear view of your children's vaccination care.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('parent.children.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                <i class="fa-solid fa-plus text-xs"></i> Add child
            </a>
            <a href="{{ route('parent.book.hospital') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-600">
                <i class="fa-solid fa-calendar-plus text-xs"></i> Book a vaccine
            </a>
        </div>
    </header>

    <section aria-label="Vaccination overview" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <article class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-5">
            <div>
                <p class="text-sm font-medium text-slate-600">Children</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $children->count() }}</p>
                <a href="{{ route('parent.children.index') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:underline">View profiles <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700"><i class="fa-solid fa-children"></i></span>
        </article>
        <article class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-5">
            <div>
                <p class="text-sm font-medium text-slate-600">Upcoming visits</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $upcomingAppointments->count() }}</p>
                <a href="{{ route('parent.vaccination.dates') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:underline">View schedule <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><i class="fa-solid fa-calendar-check"></i></span>
        </article>
        <article class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-5">
            <div>
                <p class="text-sm font-medium text-slate-600">Doses recorded</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $completedCount }}</p>
                <a href="{{ route('parent.reports.index') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:underline">View report <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-700"><i class="fa-solid fa-syringe"></i></span>
        </article>
    </section>

    @if(count($vaccineAlerts) > 0)
        <section aria-labelledby="reminders-title" class="rounded-lg border border-amber-200 bg-amber-50/70 p-5 sm:p-6">
            <div class="mb-4 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800"><i class="fa-solid fa-bell"></i></span>
                    <div>
                        <h2 id="reminders-title" class="font-bold text-slate-900">Vaccination reminders</h2>
                        <p class="mt-1 text-sm text-slate-600">Based on the recommended schedule for your children.</p>
                    </div>
                </div>
                <span class="shrink-0 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-amber-900">{{ count($vaccineAlerts) }}</span>
            </div>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($vaccineAlerts as $alert)
                    <article class="flex flex-col justify-between gap-4 rounded-lg border border-amber-200 bg-white p-4">
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-sm font-semibold text-slate-900">{{ $alert['child']->name }}</span>
                                @if($alert['is_overdue'])
                                    <span class="rounded-full bg-rose-50 px-2 py-1 text-[11px] font-semibold text-rose-700">Due now</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-2 py-1 text-[11px] font-semibold text-amber-800">Upcoming</span>
                                @endif
                            </div>
                            <h3 class="mt-3 font-bold text-slate-900">{{ $alert['vaccine']->name }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $alert['vaccine']->target_disease }}</p>
                            <p class="mt-2 text-xs text-slate-500">Recommended: {{ $alert['due_label'] }}</p>
                        </div>
                        <a href="{{ route('parent.book.hospital', ['child_id' => $alert['child']->id, 'vaccine_id' => $alert['vaccine']->id]) }}" class="inline-flex items-center justify-center gap-2 border-t border-slate-100 pt-3 text-sm font-semibold text-brand-700 hover:text-brand-800">
                            Book visit <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-5">
        <section aria-labelledby="appointments-title" class="rounded-lg border border-slate-200 bg-white p-5 sm:p-6 lg:col-span-3">
            <div class="mb-2 flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 id="appointments-title" class="font-bold text-slate-900">Upcoming appointments</h2>
                    <p class="mt-1 text-sm text-slate-600">Your scheduled clinic visits.</p>
                </div>
                <a href="{{ route('parent.vaccination.dates') }}" class="shrink-0 text-sm font-semibold text-brand-700 hover:underline">Full schedule</a>
            </div>
            <div>
                @forelse($upcomingAppointments as $apt)
                    <article class="grid grid-cols-[52px_minmax(0,1fr)] items-center gap-3 border-b border-slate-100 py-4 last:border-0 sm:grid-cols-[60px_minmax(0,1fr)_auto] sm:gap-4">
                        <div class="rounded-lg bg-slate-50 px-2 py-2 text-center">
                            <p class="text-[11px] font-semibold uppercase text-slate-500">{{ $apt->appointment_date?->format('M') ?? 'TBD' }}</p>
                            <p class="text-lg font-bold leading-tight text-slate-900">{{ $apt->appointment_date?->format('d') ?? '--' }}</p>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="truncate text-sm font-semibold text-slate-900">{{ $apt->vaccine->name }}</h3>
                                <span class="rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $apt->status_badge_class }}">{{ $apt->status_label }}</span>
                            </div>
                            <p class="mt-1 truncate text-xs text-slate-600">{{ $apt->child->name }} <span class="px-1 text-slate-300">/</span> {{ $apt->hospital->name }}</p>
                            <p class="mt-1 text-xs text-slate-500"><i class="fa-regular fa-clock mr-1"></i>{{ $apt->appointment_time }}</p>
                        </div>
                        <p class="col-start-2 text-[11px] text-slate-500 sm:col-start-auto sm:text-right">Ref: {{ $apt->booking_reference }}</p>
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-500"><i class="fa-regular fa-calendar-check"></i></span>
                        <p class="mt-3 text-sm font-semibold text-slate-800">No visits scheduled</p>
                        <p class="mt-1 text-sm text-slate-500">Your upcoming appointments will appear here.</p>
                        <a href="{{ route('parent.book.hospital') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:underline"><i class="fa-solid fa-plus text-xs"></i> Book a vaccine</a>
                    </div>
                @endforelse
            </div>
        </section>

        <section aria-labelledby="children-title" class="rounded-lg border border-slate-200 bg-white p-5 sm:p-6 lg:col-span-2">
            <div class="mb-2 flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h2 id="children-title" class="font-bold text-slate-900">Your children</h2>
                    <p class="mt-1 text-sm text-slate-600">Profiles and recorded doses.</p>
                </div>
                <a href="{{ route('parent.children.index') }}" class="shrink-0 text-sm font-semibold text-brand-700 hover:underline">All children</a>
            </div>
            <div>
                @forelse($children as $child)
                    @php
                        $childDoses = $child->appointments->where('status', 'vaccinated')->count();
                    @endphp
                    <article class="flex items-center gap-3 border-b border-slate-100 py-4 last:border-0">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 font-bold text-emerald-800">{{ strtoupper(substr($child->name, 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-semibold text-slate-900">{{ $child->name }}</h3>
                            <p class="mt-1 truncate text-xs text-slate-500">{{ $child->formatted_age }} <span class="px-1 text-slate-300">/</span> {{ $child->gender }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs font-semibold text-slate-700">{{ $childDoses }} {{ \Illuminate\Support\Str::plural('dose', $childDoses) }}</p>
                            <a href="{{ route('parent.children.show', $child->id) }}" class="mt-1 inline-block text-xs font-semibold text-brand-700 hover:underline">Timeline</a>
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-500"><i class="fa-solid fa-child"></i></span>
                        <p class="mt-3 text-sm font-semibold text-slate-800">No child profiles yet</p>
                        <p class="mt-1 text-sm text-slate-500">Add a profile to start tracking vaccinations.</p>
                        <a href="{{ route('parent.children.create') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:underline"><i class="fa-solid fa-plus text-xs"></i> Add child</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
