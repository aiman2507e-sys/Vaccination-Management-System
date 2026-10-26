@if (session('success'))
    <div class="mb-4 flex items-center p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check text-lg mr-3 text-emerald-600"></i>
        <div class="font-medium">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 flex items-center p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation text-lg mr-3 text-rose-600"></i>
        <div class="font-medium">{{ session('error') }}</div>
    </div>
@endif

@if (session('info'))
    <div class="mb-4 flex items-center p-4 text-sm text-sky-800 rounded-xl bg-sky-50 border border-sky-200 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-info text-lg mr-3 text-sky-600"></i>
        <div class="font-medium">{{ session('info') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
        <div class="flex items-center mb-2">
            <i class="fa-solid fa-triangle-exclamation text-lg mr-2 text-rose-600"></i>
            <span class="font-semibold">Please correct the following errors:</span>
        </div>
        <ul class="list-disc list-inside ml-4 space-y-1 text-rose-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
