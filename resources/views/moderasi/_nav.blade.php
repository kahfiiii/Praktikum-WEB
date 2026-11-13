@php
    $openReports = \App\Models\Report::where('status', 'baru')->count();
    $pendingComments = \App\Models\Comment::where('status', 'pending')->count();
@endphp

{{-- Header Admin Standar STUDORA (Sesuai Desain Figma: bg-surface putih, logo di kiri, nav di tengah, user di kanan) --}}
<header class="sticky top-0 z-30 border-b border-line bg-surface">
    <div class="mx-auto flex h-[70px] max-w-[1910px] items-center justify-between px-6 lg:px-[76px]">
        {{-- Logo Kiri --}}
        <div class="flex items-center gap-3">
            <x-logo />
            <span class="rounded-[8px] bg-primary-soft px-2 py-0.5 font-display text-[11px] font-bold text-primary">
                {{ auth()->user()->isAdmin() ? 'Panel Admin' : 'Panel Moderasi' }}
            </span>
        </div>

        {{-- Navigasi Tengah --}}
        <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi admin">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold transition {{ ($active ?? '') === 'dashboard' ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                    <x-icon name="home" class="h-[18px] w-[18px]" />
                    <span>Dashboard</span>
                    @if (($active ?? '') === 'dashboard')
                        <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                    @endif
                </a>

                <a href="{{ route('admin.kelola') }}"
                   class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold transition {{ ($active ?? '') === 'mata kuliah' ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                    <x-icon name="library" class="h-[18px] w-[18px]" />
                    <span>Kelola mata kuliah</span>
                    @if (($active ?? '') === 'mata kuliah')
                        <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                    @endif
                </a>

                <a href="{{ route('admin.moderasi') }}"
                   class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold transition {{ ($active ?? '') === 'komentar' ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                    <x-icon name="messages-square" class="h-[18px] w-[18px]" />
                    <span>Moderasi komentar</span>
                    @if ($pendingComments > 0)
                        <span class="grid h-5 min-w-5 place-items-center rounded-full bg-coral px-1.5 text-[10px] font-extrabold text-white">{{ $pendingComments }}</span>
                    @endif
                    @if (($active ?? '') === 'komentar')
                        <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                    @endif
                </a>

                <a href="{{ route('admin.moderator') }}"
                   class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold transition {{ ($active ?? '') === 'moderator' ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                    <x-icon name="users" class="h-[18px] w-[18px]" />
                    <span>Moderator</span>
                    @if (($active ?? '') === 'moderator')
                        <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                    @endif
                </a>
            @endif

            <a href="{{ route('moderasi.laporan') }}"
               class="relative flex h-[69px] items-center gap-2 whitespace-nowrap px-4 font-display text-[13px] font-bold transition {{ ($active ?? '') === 'laporan' ? 'text-primary' : 'text-ink-muted hover:text-ink' }}">
                <x-icon name="shield-check" class="h-[18px] w-[18px]" />
                <span>Laporan masuk</span>
                @if ($openReports > 0)
                    <span class="grid h-5 min-w-5 place-items-center rounded-full bg-coral px-1.5 text-[10px] font-extrabold text-white">{{ $openReports }}</span>
                @endif
                @if (($active ?? '') === 'laporan')
                    <span class="absolute bottom-0 left-4 right-4 h-[3px] rounded-t-full bg-primary"></span>
                @endif
            </a>
        </nav>

        {{-- Profil Kanan --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('beranda') }}"
               class="hidden items-center gap-1.5 rounded-[12px] border border-line-soft bg-surface px-3 py-1.5 font-display text-[12px] font-bold text-ink-muted transition hover:border-primary hover:text-primary sm:flex">
                <x-icon name="arrow-left" class="h-3.5 w-3.5" />
                Mode mahasiswa
            </a>

            <div class="flex items-center gap-2.5">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-lime font-display text-[12px] font-extrabold text-ink">
                    {{ auth()->user()->initials() }}
                </span>
                <div class="hidden flex-col text-left sm:flex">
                    <span class="max-w-[140px] truncate text-[13px] font-bold text-ink">{{ auth()->user()->name }}</span>
                    <span class="max-w-[140px] truncate text-[11px] text-ink-muted">{{ auth()->user()->roleLabel() }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="grid h-10 w-10 place-items-center rounded-[12px] text-ink-muted transition hover:bg-coral-soft hover:text-coral-ink" aria-label="Keluar dari akun" title="Keluar">
                    <x-icon name="log-out" class="h-5 w-5" />
                </button>
            </form>
        </div>
    </div>
</header>
