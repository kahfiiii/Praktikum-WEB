@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@php
    $user = auth()->user();
    $recent = \App\Models\Material::with(['course', 'author'])->latest()->take(3)->get();
    $pendingQuiz = \App\Models\Quiz::withCount('questions')->where('status', 'draft')->first();
    $lastAttempt = \App\Models\Attempt::where('user_id', $user->id)->latest()->first();
    $myMaterials = \App\Models\Material::where('user_id', $user->id)->count();

    $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $today = $days[now()->format('l')].', '.now()->format('j').' '.$months[(int) now()->format('n')];
@endphp

{{-- ===== HERO BANNER (Figma: 1160x186, radius 26px, bg-primary-soft #e9ebff) ===== --}}
<section class="relative overflow-hidden rounded-[26px] bg-[#e9ebff] px-6 py-8 sm:px-10 lg:h-[186px] lg:py-0">
    {{-- Sparkle Bintang Putih ✦ --}}
    <span class="pointer-events-none absolute -top-8 right-32 select-none text-[120px] font-bold leading-none text-white/70 lg:right-48">
        ✦
    </span>

    <div class="relative flex flex-col justify-center lg:h-full">
        <div class="max-w-[500px]">
            <p class="font-display text-[12px] font-bold text-[#5568ee]">{{ $today }}</p>
            <h1 class="mt-1 font-display text-[28px] font-extrabold tracking-[-1.5px] text-[#17254b] sm:text-[36px] lg:text-[44px]">
                Halo, {{ explode(' ', $user->name)[0] }}! ∿
            </h1>
            <p class="mt-1 text-[14px] leading-relaxed text-[#68718a]">
                Mau belajar apa hari ini?@if ($pendingQuiz) Kamu punya satu kuis yang belum selesai.@else Semua kuis kamu sudah beres.@endif
            </p>
        </div>

        {{-- Button Unggah materi --}}
        <div class="mt-4 lg:absolute lg:right-10 lg:top-1/2 lg:-translate-y-1/2 lg:mt-0">
            <a href="{{ route('unggah') }}"
               class="inline-flex items-center gap-2 rounded-[13px] bg-[#5568ee] px-5 py-3 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">
                <x-icon name="upload" class="h-4 w-4" />
                Unggah materi
            </a>
        </div>
    </div>
</section>

{{-- ===== 4 STAT CARDS (Figma: 279x96, radius 18, border #e5e2dc) ===== --}}
<section class="mt-6 grid grid-cols-2 gap-3.5 sm:grid-cols-4 lg:gap-4" aria-label="Ringkasan aktivitas">
    @foreach ([
        ['bg' => 'bg-[#ffe6e2]', 'icon' => 'book', 'text' => 'text-[#ff7868]', 'title' => 'Mata kuliah', 'sub' => $stats['courses'].' kategori materi', 'route' => 'mata-kuliah'],
        ['bg' => 'bg-[#e9ebff]', 'icon' => 'clock', 'text' => 'text-[#5568ee]', 'title' => 'Materi terbaru', 'sub' => \App\Models\Material::where('created_at', '>=', now()->subWeek())->count().' baru minggu ini', 'route' => 'mata-kuliah'],
        ['bg' => 'bg-[#eafaaf]', 'icon' => 'file-text', 'text' => 'text-[#466419]', 'title' => 'Materi saya', 'sub' => $myMaterials.' unggahanmu', 'route' => 'unggah'],
        ['bg' => 'bg-[#e9ddff]', 'icon' => 'circle-help', 'text' => 'text-[#6741a8]', 'title' => 'Riwayat kuis', 'sub' => $lastAttempt ? 'Skor terakhir: '.$lastAttempt->score : 'Belum ada skor', 'route' => 'kuis-saya'],
    ] as $item)
        <a href="{{ route($item['route']) }}"
           class="group flex items-center justify-between rounded-[18px] border border-[#e5e2dc] bg-white p-4 transition hover:border-[#5568ee]/50 hover:shadow-sm lg:p-5">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-[14px] {{ $item['bg'] }} {{ $item['text'] }}">
                    <x-icon :name="$item['icon']" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="font-display text-[14px] font-bold text-[#17254b] group-hover:text-[#5568ee]">{{ $item['title'] }}</p>
                    <p class="truncate text-[11px] text-[#68718a]">{{ $item['sub'] }}</p>
                </div>
            </div>
            <x-icon name="chevron-right" class="hidden h-4 w-4 shrink-0 text-[#9aa0b1] sm:block group-hover:text-[#5568ee]" />
        </a>
    @endforeach
</section>

{{-- ===== 2 KOLOM: List Materi Terbaru (794px) | Sidebar Kuis (330px) ===== --}}
<div class="mt-8 grid gap-8 lg:grid-cols-[1fr_330px]">
    {{-- KIRI: Materi terbaru --}}
    <section>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-display text-[12px] font-bold text-[#5568ee]">Baru mendarat</p>
                <h2 class="mt-0.5 font-display text-[22px] font-extrabold text-[#17254b]">Materi terbaru</h2>
            </div>
            <a href="{{ route('mata-kuliah') }}" class="font-display text-[12px] font-bold text-[#5568ee] hover:underline">Lihat semua →</a>
        </div>

        <div class="mt-4 space-y-3.5">
            @forelse ($recent as $i => $material)
                <a href="{{ route('materi.detail', $material) }}"
                   class="group flex flex-col gap-3.5 rounded-[19px] border border-[#e5e2dc] bg-white p-4 transition hover:border-[#5568ee] hover:shadow-sm sm:flex-row sm:items-start sm:p-5">
                    {{-- File badge thumbnail --}}
                    <div class="grid h-14 w-14 shrink-0 place-items-center rounded-[14px] {{ $material->file_type === 'pdf' ? 'bg-[#ffe6e2] text-[#ff7868]' : ($material->file_type === 'pptx' ? 'bg-[#fff5bd] text-[#a87a19]' : 'bg-[#e9ddff] text-[#6741a8]') }}">
                        <div class="flex flex-col items-center">
                            <x-icon name="file-text" class="h-5 w-5" />
                            <span class="text-[9px] font-bold uppercase">{{ $material->file_type }}</span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-[#f6f5fb] px-2 py-0.5 text-[10px] font-bold uppercase text-[#68718a]">
                                {{ $material->file_type }}
                            </span>
                            <span class="text-[11px] text-[#68718a]">{{ $material->created_at->diffForHumans() }}</span>
                            @if ($material->isVerified())
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#eafaaf] px-2 py-0.5 text-[9px] font-bold text-[#466419]">
                                    <x-icon name="shield-check" class="h-2.5 w-2.5" />Terverifikasi
                                </span>
                            @endif
                        </div>

                        <p class="mt-1 line-clamp-1 font-display text-[16px] font-bold text-[#17254b] group-hover:text-[#5568ee]">
                            {{ $material->title }}
                        </p>

                        <div class="mt-2.5 flex flex-wrap items-center gap-4 text-[11px] text-[#68718a]">
                            <span class="flex items-center gap-1.5">
                                <span class="grid h-4 w-4 place-items-center rounded-full bg-[#cde969] text-[8px] font-bold text-[#17254b]">
                                    {{ $material->author?->initials() ?? 'UB' }}
                                </span>
                                {{ $material->author?->name ?? 'Anonim' }}
                            </span>
                            <span class="flex items-center gap-1"><x-icon name="list-checks" class="h-3.5 w-3.5" />{{ $material->course ? $material->course->quizzes()->count() : 0 }} kuis</span>
                            <span class="flex items-center gap-1"><x-icon name="message-square" class="h-3.5 w-3.5" />{{ $material->comments()->count() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-[19px] border border-[#e5e2dc] bg-white p-8 text-center">
                    <p class="font-display text-sm font-bold text-ink">Belum ada materi</p>
                    <a href="{{ route('unggah') }}" class="btn-primary mt-3">Unggah materi</a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- KANAN: Sidebar Card (Kuis Aktif + Catatan Kuning) --}}
    <aside class="space-y-5">
        {{-- Kartu Kuis Biru (Figma: 330x306, radius 23px, bg #5568ee) --}}
        <div class="relative overflow-hidden rounded-[23px] bg-[#5568ee] p-7 text-white">
            {{-- Sparkle Kuning Lime ✦ --}}
            <span class="pointer-events-none absolute right-4 top-2 select-none text-[38px] font-bold text-[#cde969]">
                ✦
            </span>

            <span class="grid h-11 w-11 place-items-center rounded-[14px] bg-[#e9ddff] text-[#6741a8]">
                <x-icon name="list-checks" class="h-5 w-5" />
            </span>

            <div class="mt-5">
                <span class="rounded-full bg-[#eafaaf] px-2.5 py-1 text-[11px] font-bold text-[#466419]">
                    Lanjutkan kuis
                </span>
            </div>

            <h3 class="mt-2 font-display text-[20px] font-bold leading-snug">
                {{ $pendingQuiz->title ?? 'Linked List Dasar' }}
            </h3>

            <p class="mt-1 text-[13px] text-white/80">
                {{ $pendingQuiz ? ($pendingQuiz->questions_count ?? 0).' soal siap dikerjakan.' : '2 dari 10 soal sudah kamu jawab.' }}
            </p>

            {{-- Progress bar --}}
            <div class="mt-5 h-2 w-full overflow-hidden rounded-full bg-white/30">
                <div class="h-full w-[25%] rounded-full bg-[#cde969]"></div>
            </div>

            @if ($pendingQuiz)
                <a href="{{ route('kuis.preview', $pendingQuiz) }}"
                   class="mt-6 flex h-11 w-full items-center justify-center rounded-[13px] bg-[#17254b] font-display text-[14px] font-bold text-white transition hover:bg-[#17254b]/90">
                    Lanjut mengerjakan
                </a>
            @else
                <a href="{{ route('kuis.pilih') }}"
                   class="mt-6 flex h-11 w-full items-center justify-center rounded-[13px] bg-[#17254b] font-display text-[14px] font-bold text-white transition hover:bg-[#17254b]/90">
                    Mulai kuis baru
                </a>
            @endif
        </div>

        {{-- Note Sticky Kuning (Figma: bg #fff5bd, border #c9b34c) --}}
        <div class="rounded-[18px] border border-[#c9b34c] bg-[#fff5bd] p-5">
            <p class="font-display text-[13px] font-bold text-[#17254b]">Catatan kecil hari ini</p>
            <p class="mt-1 text-[12px] leading-relaxed text-[#625b3f]">
                Belajar 25 menit fokus lebih ampuh daripada 2 jam sambil buka Reels.
            </p>
        </div>
    </aside>
</div>
@endsection
