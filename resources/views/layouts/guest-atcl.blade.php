<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ATCL SACCOS Membership Referral Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-primaryText antialiased min-h-screen">
    <div class="min-h-screen flex flex-col">
        {{-- Header – ATCL brand --}}
        <div class="h-[28vh] min-h-[160px] flex items-center justify-center bg-brandBlue px-6 py-4">
            <a href="{{ url('/') }}" class="block">
                <img src="{{ asset('images/atcl-logo.png') }}" alt="ATCL SACCOS" class="h-14 sm:h-16 w-auto object-contain drop-shadow-sm" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span class="text-title font-semibold text-white" style="display:none;">ATCL SACCOS</span>
            </a>
        </div>
        {{-- Main --}}
        <div class="flex-1 min-h-0 overflow-y-auto px-4 sm:px-6 py-6 sm:py-8 flex flex-col items-center">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>
</html>
