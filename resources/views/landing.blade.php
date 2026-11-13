@extends('layouts.base')

@section('title', 'STUDORA')

@section('body')
<div class="min-h-screen bg-[#fffdf8] text-[#17254b]">
    {{-- ===== HEADER ===== --}}
    <header class="sticky top-0 z-30 border-b border-[#e5e2dc]/40 bg-white/90 backdrop-blur-sm">
        <div class="mx-auto flex h-[74px] max-w-[1440px] items-center justify-between px-6 lg:px-[95.5px]">
            <x-logo />
            <div class="flex items-center gap-2">
                <a href="{{ route('masuk') }}" class="rounded-[12px] px-4 py-2 font-display text-[16px] font-bold text-[#17254b] transition hover:text-[#5568ee]">Masuk</a>
                <a href="{{ route('daftar') }}" class="inline-flex items-center justify-center rounded-[13px] bg-[#5568ee] px-5 py-2.5 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">Daftar gratis</a>
            </div>
        </div>
    </header>

    {{-- ===== HERO SECTION ===== --}}
    <section class="mx-auto max-w-[1280px] px-6 pt-8 pb-16 lg:px-[48px] lg:pt-[74px] lg:pb-[90px]">
        <div class="grid items-center gap-12 lg:grid-cols-[572px_1fr] lg:gap-[72px]">
            {{-- KIRI: Teks & CTA --}}
            <div class="flex flex-col items-start">
                <div class="inline-flex items-center rounded-full bg-[#eafaaf] px-3 py-1 text-[11px] font-bold tracking-[0.15px] text-[#466419]">
                    Dibuat khusus mahasiswa Universitas Brawijaya
                </div>

                <h1 class="mt-5 font-display text-[38px] font-extrabold leading-[1.12] tracking-[-1.5px] text-[#17254b] sm:text-[48px] lg:text-[62px] lg:leading-[70px] lg:tracking-[-2.2px]">
                    Materi kuliah<br>nggak lagi
                    <span class="relative inline-block text-[#5568ee]">
                        hilang ditelan chat.
                        <span class="absolute -bottom-1.5 right-0 -rotate-1 h-[9px] w-[61px] rounded-full bg-[#ff7868]/80"></span>
                    </span>
                </h1>

                <p class="mt-5 max-w-[570px] font-sans text-[16px] leading-[28px] text-[#68718a] lg:text-[19px] lg:leading-[31.5px]">
                    Simpan, temukan, dan pahami materi kuliah bareng teman se-UB. Ada kuis dan ruang diskusi di setiap materi.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3.5">
                    <a href="{{ route('daftar') }}" class="inline-flex items-center gap-2 rounded-[13px] bg-[#5568ee] px-6 py-3 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">
                        <x-icon name="sparkles" class="h-4 w-4 text-white" />
                        Mulai belajar
                    </a>
                    <a href="{{ route('masuk') }}" class="inline-flex items-center justify-center rounded-[13px] border border-[#d8dbe5] bg-white px-6 py-3 font-display text-[14px] font-bold text-[#17254b] transition hover:border-[#5568ee] hover:text-[#5568ee]" style="box-shadow: 0 3px 0 #e5e2dc">
                        Aku sudah punya akun
                    </a>
                </div>

                {{-- Social proof stack --}}
                <div class="mt-8 flex items-center gap-3.5">
                    <div class="flex items-center -space-x-2">
                        <span class="grid h-[38px] w-[38px] place-items-center rounded-full border-[3px] border-[#fffdf8] bg-[#ff7868] font-sans text-[11px] font-bold text-[#17254b]">NP</span>
                        <span class="grid h-[38px] w-[38px] place-items-center rounded-full border-[3px] border-[#fffdf8] bg-[#dfe4ff] font-sans text-[11px] font-bold text-[#17254b]">RP</span>
                        <span class="grid h-[38px] w-[38px] place-items-center rounded-full border-[3px] border-[#fffdf8] bg-[#cde969] font-sans text-[11px] font-bold text-[#17254b]">AK</span>
                        <span class="grid h-[38px] w-[38px] place-items-center rounded-full border-[3px] border-[#fffdf8] bg-[#17254b] font-sans text-[11px] font-bold text-white">+</span>
                    </div>
                    <div class="font-sans text-[13px] leading-[18px]">
                        <span class="font-bold text-[#17254b]">1.200+ mahasiswa</span><br>
                        <span class="text-[#68718a]">sudah belajar bareng</span>
                    </div>
                </div>
            </div>

            {{-- KANAN: Visual Illustration Frame --}}
            <div class="relative mx-auto w-full max-w-[539px]">
                {{-- Organic Pastel Bubble Canvas --}}
                <div class="relative h-[480px] w-full rounded-[230px_294px_252px_273px] bg-[#e9ebff] sm:h-[510px]">
                    {{-- Dashed ring --}}
                    <div class="absolute inset-2 rotate-6 rounded-[257px] border-2 border-dashed border-[#5568ee]/35"></div>

                    {{-- Sparkle Coral --}}
                    <span class="absolute -top-4 right-10 select-none text-[52px] font-bold leading-none text-[#ff7868]">✦</span>

                    {{-- Arrow Deco --}}
                    <span class="absolute -left-4 top-28 select-none font-display text-[44px] font-bold text-[#17254b]" style="transform: rotate(35deg);">↗</span>

                    {{-- 1. Kartu Materi Utama (Top Left Rotated) --}}
                    <div class="absolute left-3 top-8 w-[92%] max-w-[388px] -rotate-4 rounded-[22px] border-2 border-[#17254b] bg-white p-6 shadow-[8px_10px_0px_rgba(23,37,75,0.18)] transition hover:rotate-0">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full bg-[#ffe6e2] px-2.5 py-1 text-[11px] font-bold text-[#a84439]">Struktur Data</span>
                            <span class="h-3 w-14 rounded-full bg-[#ffd96a]/80"></span>
                        </div>
                        <h3 class="mt-3 font-display text-[20px] font-bold leading-[28px] text-[#17254b] sm:text-[22px]">
                            Linked List, tapi versi gampangnya
                        </h3>
                        <div class="mt-4 space-y-2">
                            <div class="h-2 w-full rounded bg-[#eceef5]"></div>
                            <div class="h-2 w-4/5 rounded bg-[#eceef5]"></div>
                            <div class="h-2 w-3/5 rounded bg-[#eceef5]"></div>
                        </div>
                        <div class="mt-5 flex items-center justify-between border-t border-[#eceef5] pt-3 text-[12px] font-medium text-[#68718a]">
                            <span>PDF · 12 hlm</span>
                            <span>12 komentar</span>
                        </div>
                    </div>

                    {{-- 2. Kartu Kuis Kilat (Right Middle Rotated) --}}
                    <div class="absolute right-0 top-[280px] flex w-[88%] max-w-[361px] rotate-3 items-center gap-3 rounded-[22px] border-2 border-[#17254b] bg-white p-4 shadow-[8px_10px_0px_rgba(23,37,75,0.18)] transition hover:rotate-0">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-[15px] bg-[#e9ddff] text-[#6741a8]">
                            <x-icon name="sparkles" class="h-5 w-5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[12px] text-[#68718a]">Kuis kilat</p>
                            <p class="font-display text-[16px] font-bold text-[#17254b]">Skor kamu 90!</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-full border-[5px] border-[#cde969] font-display text-[15px] font-bold text-[#17254b]">
                            90
                        </div>
                    </div>

                    {{-- 3. Testimoni Balon (Bottom Left Rotated) --}}
                    <div class="absolute -bottom-4 left-2 flex -rotate-2 items-center gap-2.5 rounded-[20px] border-2 border-[#17254b] bg-white px-4 py-2.5 shadow-[8px_10px_0px_rgba(23,37,75,0.18)] transition hover:rotate-0">
                        <div class="flex items-center -space-x-1.5">
                            <span class="grid h-7 w-7 place-items-center rounded-full border-2 border-white bg-[#ff7868] text-[9px] font-bold text-[#17254b]">RP</span>
                            <span class="grid h-7 w-7 place-items-center rounded-full border-2 border-white bg-[#cde969] text-[9px] font-bold text-[#17254b]">AK</span>
                        </div>
                        <p class="text-[12px] font-bold text-[#17254b]">"Akhirnya pointer masuk akal."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION: KENAPA STUDORA? ===== --}}
    <section class="bg-white py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-6 lg:px-[48px]">
            <div class="mx-auto max-w-[700px] text-center">
                <p class="font-display text-[13px] font-bold tracking-[1.5px] text-[#5568ee]">KENAPA STUDORA?</p>
                <h2 class="mt-3 font-display text-[28px] font-extrabold leading-[1.2] tracking-[-1px] text-[#17254b] lg:text-[37px]">
                    Satu tempat buat belajar, bukan sekadar numpuk berkas
                </h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3 lg:gap-8">
                {{-- Feature 1 --}}
                <div class="flex flex-col rounded-[22px] border border-[#e5e2dc] bg-[#fffdf8] p-8 transition hover:border-[#5568ee]/50 hover:shadow-lg">
                    <div class="grid h-[55px] w-[55px] place-items-center rounded-[17px] bg-[#eafaaf] text-[#466419]">
                        <x-icon name="cloud-upload" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-6 font-display text-[20px] font-bold text-[#17254b]">Materi rapi, kepala tenang</h3>
                    <p class="mt-3 text-[14px] leading-[24px] text-[#68718a]">
                        Cari catatan, slide, dan latihan berdasarkan mata kuliah. Semuanya rapi di satu tempat, tinggal buka dan mulai belajar.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="flex flex-col rounded-[22px] border border-[#e5e2dc] bg-[#fffdf8] p-8 transition hover:border-[#5568ee]/50 hover:shadow-lg">
                    <div class="grid h-[55px] w-[55px] place-items-center rounded-[17px] bg-[#e9ddff] text-[#6741a8]">
                        <x-icon name="sparkles" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-6 font-display text-[20px] font-bold text-[#17254b]">Latihan sampai paham</h3>
                    <p class="mt-3 text-[14px] leading-[24px] text-[#68718a]">
                        Uji pemahaman lewat kuis buatan teman atau buat otomatis dari AI. Cocok buat sesi belajar sebelum UTS/UAS.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="flex flex-col rounded-[22px] border border-[#e5e2dc] bg-[#fffdf8] p-8 transition hover:border-[#5568ee]/50 hover:shadow-lg">
                    <div class="grid h-[55px] w-[55px] place-items-center rounded-[17px] bg-[#ffe6e2] text-[#a84439]">
                        <x-icon name="messages-square" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-6 font-display text-[20px] font-bold text-[#17254b]">Bingung? Bahas bareng</h3>
                    <p class="mt-3 text-[14px] leading-[24px] text-[#68718a]">
                        Tanya bagian yang belum jelas langsung di halaman materi. Teman-teman bisa jawab, diskusi terbuka buat semua.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION: MATA KULIAH POPULER ===== --}}
    <section class="py-16">
        <div class="mx-auto max-w-[1280px] px-6 lg:px-[48px]">
            <div class="flex items-end justify-between">
                <div>
                    <p class="font-display text-[12px] font-bold text-[#5568ee]">RUANG BELAJAR</p>
                    <h2 class="mt-1 font-display text-[24px] font-extrabold text-[#17254b] lg:text-[28px]">Mata kuliah populer</h2>
                </div>
                <a href="{{ route('daftar') }}" class="font-display text-[13px] font-bold text-[#5568ee] hover:underline">Lihat semua →</a>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($courses as $course)
                    <a href="{{ route('daftar') }}" class="flex items-center justify-between rounded-[18px] border border-[#e5e2dc] bg-white p-5 transition hover:border-[#5568ee]/50 hover:shadow-md">
                        <div>
                            <p class="font-display text-[16px] font-bold text-[#17254b]">{{ $course->name }}</p>
                            <p class="mt-1 text-[12px] text-[#68718a]">{{ $course->materials_count }} materi · {{ $course->code }}</p>
                        </div>
                        <span class="badge {{ $course->accentClasses() }} text-[11px]">{{ $course->semester }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CTA BANNER BANNER ===== --}}
    <section class="mx-auto max-w-[1120px] px-6 py-10 lg:px-0">
        <div class="flex flex-col items-start justify-between gap-6 rounded-[24px] bg-[#17254b] px-8 py-10 lg:flex-row lg:items-center lg:px-12" style="box-shadow: 0 10px 30px rgba(23,37,75,0.15)">
            <div>
                <p class="font-display text-[12px] font-bold text-[#cde969]">Siap jadi mahasiswa yang nggak panik H-1?</p>
                <h2 class="mt-2 font-display text-[24px] font-extrabold text-white lg:text-[32px]">
                    Yuk, rapikan belajar bareng STUDORA.
                </h2>
            </div>
            <a href="{{ route('daftar') }}" class="inline-flex shrink-0 items-center gap-2 rounded-[13px] bg-[#cde969] px-6 py-3.5 font-display text-[14px] font-bold text-[#17254b] transition hover:bg-[#eafaaf]">
                <x-icon name="sparkles" class="h-4 w-4" />
                Daftar pakai email UB
            </a>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-[#e5e2dc] bg-white py-8">
        <div class="mx-auto flex max-w-[1280px] flex-col items-center justify-between gap-4 px-6 text-[13px] text-[#68718a] md:flex-row lg:px-[48px]">
            <x-logo />
            <p>Dari mahasiswa, untuk mahasiswa Universitas Brawijaya.</p>
            <p>© 2025 STUDORA</p>
        </div>
    </footer>
</div>
@endsection
