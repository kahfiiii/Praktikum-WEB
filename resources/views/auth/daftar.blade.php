@extends('layouts.base')

@section('title', 'Daftar Akun')

@section('body')
<div class="min-h-screen bg-canvas">
    {{-- ===== MOBILE: stacked layout ===== --}}
    <div class="lg:hidden">
        <div class="bg-lime px-5 pb-8 pt-5">
            <x-logo />
            <span class="mt-6 inline-flex items-center rounded-full bg-lime-soft px-[9px] py-[5px] text-[11px] font-bold tracking-[0.15px] text-lime-ink">
                Teman belajar mahasiswa UB
            </span>
            <h1 class="mt-4 font-display text-[30px] font-extrabold leading-tight tracking-[-1.5px] text-ink">Mulai belajar bareng, bukan sendirian.</h1>
            <p class="mt-2.5 text-xs leading-relaxed text-lime-ink">Satu akun buat cari materi, berbagi catatan, dan latihan kuis.</p>
        </div>

        <div class="rounded-t-[26px] bg-canvas px-5 pb-10 pt-6" style="margin-top:-18px">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-[13px] font-bold text-ink-muted">
                ← Kembali ke beranda
            </a>

            {{-- Icon --}}
            <span class="mt-5 grid h-[46px] w-[46px] place-items-center rounded-[15px] bg-violet-soft">
                <x-icon name="user-plus" class="h-5 w-5 text-violet" />
            </span>

            <h2 class="mt-3 font-display text-[22px] font-extrabold tracking-[-0.6px] text-ink">Buat akun mahasiswa</h2>
            <p class="mt-1 text-xs text-ink-muted">Gratis, cukup pakai email kampusmu.</p>

            <form method="POST" action="{{ route('daftar.store') }}" class="mt-6 space-y-4" novalidate>
                @csrf
                <div>
                    <label class="label" for="name-m">Nama lengkap</label>
                    <input id="name-m" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                           placeholder="Contoh: Nadia Putri" class="field @error('name') !border-coral @enderror">
                    @error('name')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="email-m">Email mahasiswa</label>
                    <input id="email-m" name="email" type="email" inputmode="email" required value="{{ old('email') }}"
                           placeholder="nama@student.ub.ac.id" class="field @error('email') !border-coral @enderror">
                    <p class="mt-1.5 text-[11px] text-ink-muted">Wajib menggunakan email student.ub.ac.id</p>
                    @error('email')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password-m">Kata sandi</label>
                    <input id="password-m" name="password" type="password" required placeholder="Masukkan kata sandi"
                           class="field @error('password') !border-coral @enderror">
                    <p class="mt-1.5 text-[11px] text-ink-muted">Minimal 8 karakter</p>
                    @error('password')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password_confirmation-m">Ulangi kata sandi</label>
                    <input id="password_confirmation-m" name="password_confirmation" type="password" required
                           placeholder="Ulangi kata sandi" class="field">
                </div>

                <button type="submit" class="btn-primary w-full">
                    <x-icon name="sparkles" class="h-[18px] w-[18px]" />Buat akun STUDORA
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-ink-muted">
                Sudah punya akun? <a href="{{ route('masuk') }}" class="font-bold text-primary">Masuk di sini</a>
            </p>
        </div>
    </div>

    {{-- ===== DESKTOP: 2-column layout ===== --}}
    <div class="hidden min-h-screen lg:grid lg:grid-cols-[917px_1fr]">
        {{-- LEFT: Brand panel (bg-lime) --}}
        <div class="relative flex min-h-screen flex-col justify-between overflow-hidden bg-lime pb-[50px] pl-[95px] pr-[153px] pt-[42px]">
            <x-logo />

            <div>
                <span class="inline-flex items-center rounded-full bg-lime-soft px-[9px] py-[5px] text-[11px] font-bold tracking-[0.15px] text-lime-ink">
                    Teman belajar mahasiswa UB
                </span>
                <h1 class="mt-5 max-w-[600px] font-display text-[66px] font-semibold leading-[69px] tracking-[-3px] text-ink">
                    Mulai belajar bareng, bukan sendirian.
                </h1>
                <p class="mt-5 max-w-[520px] text-[18px] leading-[30px] text-lime-ink">
                    Satu akun buat cari materi, berbagi catatan, dan latihan kuis.
                </p>
            </div>

            {{-- Quote --}}
            <div class="-rotate-1 rounded-[18px] border border-ink/15 bg-ink/[0.07] px-6 py-5" style="max-width:439px">
                <p class="text-[15px] font-semibold leading-[22px] text-ink">"Nilai bagus itu bonus. Paham konsepnya, baru keren."</p>
                <p class="mt-[7px] text-xs text-ink-muted">— mahasiswa Universitas Brawijaya</p>
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
                    <x-icon name="user-plus" class="h-5 w-5 text-violet" />
                </span>

                <h2 class="mt-[15px] font-display text-[31px] font-semibold leading-[47px] tracking-[-1px] text-ink">Buat akun mahasiswa</h2>
                <p class="mt-[7px] text-base text-ink-muted">Gratis, cukup pakai email kampusmu.</p>

                <form method="POST" action="{{ route('daftar.store') }}" class="mt-8 w-full space-y-5" novalidate>
                    @csrf
                    <div>
                        <label class="label" for="name">Nama lengkap</label>
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                               placeholder="Contoh: Nadia Putri" class="field @error('name') !border-coral @enderror">
                        @error('name')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="label" for="email">Email mahasiswa</label>
                        <input id="email" name="email" type="email" inputmode="email" required value="{{ old('email') }}"
                               placeholder="nama@student.ub.ac.id" class="field @error('email') !border-coral @enderror">
                        <p class="mt-1.5 text-[11px] text-ink-muted">Wajib menggunakan email student.ub.ac.id</p>
                        @error('email')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="label" for="password">Kata sandi</label>
                        <input id="password" name="password" type="password" required placeholder="Masukkan kata sandi"
                               class="field @error('password') !border-coral @enderror">
                        <p class="mt-1.5 text-[11px] text-ink-muted">Minimal 8 karakter</p>
                        @error('password')<p class="mt-1 text-[11px] font-bold text-coral-ink">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        <x-icon name="sparkles" class="h-[18px] w-[18px]" />Buat akun STUDORA
                    </button>
                </form>

                <p class="mt-6 w-full text-center text-sm text-ink-muted">
                    Sudah punya akun? <a href="{{ route('masuk') }}" class="font-bold text-primary">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
