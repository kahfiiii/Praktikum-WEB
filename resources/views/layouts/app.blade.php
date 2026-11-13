@extends('layouts.base')

@section('body')
@php
    $openReports = auth()->user()->isModerator()
        ? \App\Models\Report::where('status', 'baru')->count()
        : 0;

    $navItems = [
        ['beranda', 'home', 'Beranda'],
        ['mata-kuliah', 'library', 'Mata kuliah'],
        ['unggah', 'upload', 'Unggah'],
        ['kuis-saya', 'list-checks', 'Kuis saya'],
    ];
@endphp

<div class="min-h-screen bg-canvas-warm">
    {{-- ===== HEADER ===== --}}
    <header class="sticky top-0 z-30 border-b border-line bg-surface">
        <div class="mx-auto flex h-[70px] max-w-[1910px] items-center justify-between px-6 lg:px-[76px]">
            {{-- Logo --}}
            <div class="flex items-center">
                <x-logo />
            </div>

            {{-- Nav horizontal: desktop (Center aligned) --}}
            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                @foreach ($navItems as [$route, $icon, $label])
                    @php($active = request()->routeIs($route.'*'))
                    <a href="{{ route($route) }}"
                       class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold leading-5 transition {{ $active ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                        <x-icon :name="$icon" class="h-[18px] w-[18px]" />
                        <span>{{ $label }}</span>
                        @if ($active)
                            <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                        @endif
                    </a>
                @endforeach

                @if (auth()->user()->isModerator())
                    <a href="{{ route('moderasi.laporan') }}"
                       class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold leading-5 transition {{ request()->routeIs('moderasi*') ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                        <x-icon name="shield-check" class="h-4 w-4" />
                        <span>Moderasi</span>
                        @if ($openReports > 0)
                            <span class="grid h-5 min-w-5 place-items-center rounded-full bg-coral px-1.5 text-[10px] font-extrabold text-white">{{ $openReports }}</span>
                        @endif
                    </a>
                @endif

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold leading-5 transition {{ request()->routeIs('admin*') ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                        <x-icon name="settings" class="h-4 w-4" />
                        <span>Admin</span>
                    </a>
                @endif
            </nav>

            {{-- User profile & actions --}}
            <div class="flex items-center gap-3">
                {{-- Badge laporan: mobile saja --}}
                @if (auth()->user()->isModerator())
                    <a href="{{ route('moderasi.laporan') }}"
                       class="relative grid h-10 w-10 place-items-center rounded-[12px] border border-line-soft bg-surface md:hidden"
                       aria-label="Laporan masuk ({{ $openReports }} baru)">
                        <x-icon name="flag" class="h-5 w-5 text-ink" />
                        @if ($openReports > 0)
                            <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-coral px-1 font-display text-[10px] font-extrabold text-white">{{ $openReports }}</span>
                        @endif
                    </a>
                @endif

                {{-- User info desktop --}}
                <div class="hidden items-center gap-2.5 md:flex">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-lime font-display text-[12px] font-extrabold text-ink">
                        {{ auth()->user()->initials() }}
                    </span>
                    <div class="flex flex-col text-left">
                        <span class="max-w-[140px] truncate text-[13px] font-bold leading-tight text-ink">{{ auth()->user()->name }}</span>
                        <span class="max-w-[140px] truncate text-[11px] leading-tight text-ink-muted">{{ auth()->user()->study_program ?? auth()->user()->roleLabel() }}</span>
                    </div>
                </div>

                {{-- User avatar mobile --}}
                <span class="grid h-8 w-8 place-items-center rounded-full bg-lime font-display text-[11px] font-extrabold text-ink md:hidden">
                    {{ auth()->user()->initials() }}
                </span>

                {{-- Mobile menu button --}}
                <button type="button" data-menu-open
                        class="grid h-10 w-10 place-items-center rounded-[12px] border border-line-soft bg-[#f6f5fb] md:hidden"
                        aria-label="Buka menu" aria-expanded="false" aria-controls="menu-panel">
                    <x-icon name="menu" class="h-5 w-5 text-ink" />
                </button>

                {{-- Logout button desktop --}}
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button class="grid h-10 w-10 place-items-center rounded-[12px] text-ink-muted transition hover:bg-coral-soft hover:text-coral-ink" aria-label="Keluar dari akun" title="Keluar">
                        <x-icon name="log-out" class="h-5 w-5" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- ===== MAIN ===== --}}
    <main class="mx-auto w-full max-w-[1160px] px-5 pb-24 pt-5 md:pb-12 lg:pt-[48px] lg:px-0">
        @if (session('status'))
            <div class="mb-4 rounded-[13px] border border-lime-dark bg-lime-soft px-4 py-3 text-sm font-medium text-lime-ink" role="status">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-[13px] border border-coral bg-coral-soft px-4 py-3 text-sm font-medium text-coral-ink" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </main>
</div>

{{-- ===== MENU DRAWER (mobile) ===== --}}
<div id="menu-panel" class="fixed inset-0 z-40 hidden md:hidden" role="dialog" aria-modal="true" aria-label="Menu navigasi">
    <button type="button" data-menu-close class="absolute inset-0 h-full w-full bg-ink/40" aria-label="Tutup menu"></button>
    <div class="absolute right-0 top-0 h-full w-[320px] max-w-[85%] translate-x-full bg-surface shadow-[var(--shadow-modal)] transition-transform duration-300" data-menu-card>
        <div class="flex items-center justify-between border-b border-line px-5 py-4">
            <p class="font-display text-base font-extrabold text-ink">Menu</p>
            <button type="button" data-menu-close class="grid h-8 w-8 place-items-center rounded-full bg-gray-fill" aria-label="Tutup menu">
                <x-icon name="x" class="h-4 w-4 text-ink" />
            </button>
        </div>
        <nav class="flex flex-col gap-1 p-4">
            @foreach ($navItems as [$route, $icon, $label])
                <a href="{{ route($route) }}" class="flex items-center gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-ink hover:bg-primary-soft hover:text-primary">
                    <x-icon :name="$icon" class="h-[18px] w-[18px]" />{{ $label }}
                </a>
            @endforeach
            @if (auth()->user()->isModerator())
                <a href="{{ route('moderasi.laporan') }}" class="flex items-center justify-between gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-ink hover:bg-primary-soft hover:text-primary">
                    <span class="flex items-center gap-3"><x-icon name="shield-check" class="h-[18px] w-[18px]" />Moderasi</span>
                    @if ($openReports > 0)
                        <span class="badge border-coral bg-coral-soft text-[10px] text-coral-ink">{{ $openReports }}</span>
                    @endif
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-ink hover:bg-primary-soft hover:text-primary">
                    <x-icon name="settings" class="h-[18px] w-[18px]" />Dashboard admin
                </a>
            @endif
            <div class="mt-2 flex items-center gap-2 rounded-[12px] bg-canvas-warm px-3 py-2.5">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-primary-soft font-display text-[10px] font-extrabold text-primary">{{ auth()->user()->initials() }}</span>
                <div class="min-w-0">
                    <p class="truncate text-[11px] font-bold text-ink">{{ auth()->user()->name }}</p>
                    <span class="badge mt-0.5 border-line-soft bg-gray-fill text-[9px] text-ink-muted">{{ auth()->user()->roleLabel() }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-line pt-2">
                @csrf
                <button class="flex w-full items-center gap-3 rounded-[12px] px-3 py-2.5 text-sm font-bold text-coral-ink hover:bg-coral-soft">
                    <x-icon name="log-out" class="h-[18px] w-[18px]" />Keluar
                </button>
            </form>
        </nav>
    </div>
</div>

{{-- ===== BOTTOM NAV (mobile saja) ===== --}}
<nav class="fixed bottom-0 left-0 right-0 z-30 border-t border-line bg-surface px-2 pb-1 md:hidden" aria-label="Navigasi bawah">
    <div class="flex items-center justify-around">
        @foreach ($navItems as [$route, $icon, $label])
            <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($route.'*') ? 'nav-link-active' : '' }}">
                <x-icon :name="$icon" class="h-5 w-5" />{{ $label }}
            </a>
        @endforeach
    </div>
</nav>
@endsection
