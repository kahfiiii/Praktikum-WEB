@extends('layouts.base')

@section('title', 'Masuk')

@section('body')
<div class="min-h-screen bg-canvas">
    {{-- ===== MOBILE: stacked layout ===== --}}
    <div class="lg:hidden">
        <div class="bg-ink px-5 pb-8 pt-5">
            <x-logo :inverse="true" />
            <span class="mt-6 inline-flex items-center rounded-full bg-lime-soft px-[9px] py-[5px] text-[11px] font-bold tracking-[0.15px] text-lime-ink">
                Teman belajar mahasiswa UB
            </span>
            <h1 class="mt-4 font-display text-[30px] font-semibold leading-tight tracking-[-1.5px] text-white">Balik lagi? Materi kamu sudah nunggu.</h1>
            <p class="mt-2.5 text-xs leading-relaxed text-[#bac1d4]">Masuk untuk lanjutkan kuis, diskusi, dan simpan materi baru.</p>
        </div>

        <div class="rounded-t-[26px] bg-canvas px-5 pb-10 pt-6" style="margin-top:-18px">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-[13px] font-bold text-ink-muted">
                ← Kembali ke beranda
            </a>

            {{-- Icon --}}
            <span class="mt-5 grid h-[46px] w-[46px] place-items-center rounded-[15px] bg-violet-soft">
                <x-icon name="lock" class="h-5 w-5 text-violet" />
            </span>

            <h2 class="mt-3 font-display text-[22px] font-extrabold tracking-[-0.6px] text-ink">Masuk ke STUDORA</h2>
            <p class="mt-1 text-xs text-ink-muted">Senang lihat kamu balik lagi.</p>

            <form method="POST" action="{{ route('masuk.attempt') }}" class="mt-6 space-y-4" novalidate>
                @csrf
                <div>
                    <label class="label" for="email-m">Email mahasiswa</label>
                    <input id="email-m" name="email" type="email" inputmode="email" autocomplete="email" required
                           value="{{ old('email') }}" placeholder="nama@student.ub.ac.id"
                           class="field @error('email') !border-coral @enderror">
                    <p class="mt-1.5 text-[11px] text-ink-muted">Wajib menggunakan email student.ub.ac.id</p>
                    @error('email')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password-m">Kata sandi</label>
                    <input id="password-m" name="password" type="password" autocomplete="current-password" required
                           placeholder="Masukkan kata sandi"
                           class="field @error('password') !border-coral @enderror">
                    <p class="mt-1.5 text-[11px] text-ink-muted">Minimal 8 karakter</p>
                    @error('password')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn-primary w-full">
                    <x-icon name="sparkles" class="h-[18px] w-[18px]" />Masuk dan lanjut belajar
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-ink-muted">
                Belum punya akun? <a href="{{ route('daftar') }}" class="font-bold text-primary">Daftar sekarang</a>
            </p>

            <div class="mt-6 rounded-[13px] border border-line bg-canvas-warm p-3.5 text-[11px] text-ink-muted">
                <p class="font-bold text-ink">Akun demo</p>
                <p class="mt-1">Mahasiswa: sohibulkahfi@student.ub.ac.id / password</p>
                <p>Admin: admin@student.ub.ac.id / password</p>
            </div>
        </div>
    </div>

    {{-- ===== DESKTOP: 2-column layout ===== --}}
    <div class="hidden min-h-screen lg:grid lg:grid-cols-[917px_1fr]">
        {{-- LEFT: Brand panel (bg-ink) --}}
        <div class="relative flex min-h-screen flex-col justify-between overflow-hidden bg-ink pb-[50px] pl-[95px] pr-[153px] pt-[42px]">
            <x-logo :inverse="true" />

            <div>
                <span class="inline-flex items-center rounded-full bg-lime-soft px-[9px] py-[5px] text-[11px] font-bold tracking-[0.15px] text-lime-ink">
                    Teman belajar mahasiswa UB
                </span>
                <h1 class="mt-5 max-w-[600px] font-display text-[66px] font-semibold leading-[69px] tracking-[-3px] text-white">
                    Balik lagi? Materi kamu sudah nunggu.
                </h1>
                <p class="mt-5 max-w-[520px] text-[18px] leading-[30px] text-[#bac1d4]">
                    Masuk untuk lanjutkan kuis, diskusi, dan simpan materi baru.
                </p>
            </div>

            {{-- Quote --}}
            <div class="-rotate-1 rounded-[18px] border border-white/15 bg-white/[0.07] px-6 py-5" style="max-width:439px">
                <p class="text-[15px] font-semibold leading-[22px] text-white">"Nilai bagus itu bonus. Paham konsepnya, baru keren."</p>
                <p class="mt-[7px] text-xs text-[#9da6c0]">— mahasiswa Universitas Brawijaya</p>
            </div>

            {{-- Decorative sparkle --}}
            <span class="absolute right-[50px] top-[316px] rotate-[15deg] text-[170px] leading-none text-coral opacity-[0.14]">✦</span>
        </div>

        {{-- RIGHT: Form panel --}}
        <div class="flex flex-col px-[134px] py-9">
            <a href="{{ route('landing') }}" class="text-[13px] font-bold text-ink-muted">← Kembali ke beranda</a>

            <div class="flex flex-1 flex-col items-start justify-center" style="max-width:470px">
                {{-- Icon --}}
                <span class="grid h-[46px] w-[46px] place-items-center rounded-[15px] bg-violet-soft">
                    <x-icon name="lock" class="h-5 w-5 text-violet" />
                </span>

                <h2 class="mt-[15px] font-display text-[31px] font-semibold leading-[47px] tracking-[-1px] text-ink">Masuk ke STUDORA</h2>
                <p class="mt-[7px] text-base text-ink-muted">Senang lihat kamu balik lagi.</p>

                <form method="POST" action="{{ route('masuk.attempt') }}" class="mt-8 w-full space-y-5" novalidate>
                    @csrf
                    <div>
                        <label class="label" for="email">Email mahasiswa</label>
                        <input id="email" name="email" type="email" inputmode="email" autocomplete="email" required
                               value="{{ old('email') }}" placeholder="nama@student.ub.ac.id"
                               class="field @error('email') !border-coral @enderror">
                        <p class="mt-1.5 text-[11px] text-ink-muted">Wajib menggunakan email student.ub.ac.id</p>
                        @error('email')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="label" for="password">Kata sandi</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="Masukkan kata sandi"
                               class="field @error('password') !border-coral @enderror">
                        <p class="mt-1.5 text-[11px] text-ink-muted">Minimal 8 karakter</p>
                        @error('password')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        <x-icon name="sparkles" class="h-[18px] w-[18px]" />Masuk dan lanjut belajar
                    </button>
                </form>

                <p class="mt-6 w-full text-center text-sm text-ink-muted">
                    Belum punya akun? <a href="{{ route('daftar') }}" class="font-bold text-primary">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
