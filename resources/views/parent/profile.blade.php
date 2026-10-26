@extends('layouts.parent')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-2xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500">Parent Account &bull; Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
        </div>

        <form action="{{ route('parent.profile.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">Primary email used for account authentication</p>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Residential Street Address *</label>
                <textarea name="address" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">{{ old('address', $user->address) }}</textarea>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <h4 class="font-bold text-sm text-slate-900 mb-3">Change Password (Optional)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">New Password</label>
                        <input type="password" name="password" placeholder="Leave empty to keep current" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition-all shadow-md shadow-brand-600/20">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
