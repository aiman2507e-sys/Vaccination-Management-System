@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="max-w-md mx-auto px-4 py-16" x-data="{
    fillDemo(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
}">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex w-14 h-14 rounded-2xl bg-brand-600 text-white items-center justify-center font-bold text-2xl shadow-lg shadow-brand-600/30 mb-3">
            <i class="fa-solid fa-syringe"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Vaccination Portal Login</h1>
        <p class="text-xs text-slate-500 mt-1">Single sign-on access for Admins, Hospitals, and Parents</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl shadow-slate-100">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                    <span>Remember this device</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl text-sm transition-all shadow-md shadow-brand-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                <span>Sign In to Portal</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center space-y-3">
            <p class="text-xs text-slate-600">
                Are you a Parent? 
                <a href="{{ route('register.parent') }}" class="font-bold text-brand-600 hover:text-brand-700">Register child account</a>
            </p>
            <p class="text-xs text-slate-600">
                Are you a Healthcare Clinic? 
                <a href="{{ route('register.hospital') }}" class="font-bold text-slate-900 hover:text-brand-600">Register hospital</a>
            </p>
        </div>
    </div>
</div>
@endsection
