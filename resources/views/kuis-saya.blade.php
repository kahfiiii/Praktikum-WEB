@extends('layouts.app')

@section('title', 'Kuis Saya')

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="font-display text-[12px] font-bold text-[#5568ee]">Karyamu</p>
        <h1 class="mt-1 font-display text-[32px] font-extrabold tracking-[-1px] text-[#17254b] lg:text-[38px]">Kuis saya</h1>
        <p class="mt-1 text-[15px] text-[#68718a]">Kelola kuis yang kamu buat untuk teman belajar.</p>
    </div>
    <a href="{{ route('kuis.pilih') }}" class="inline-flex shrink-0 items-center gap-2 rounded-[13px] bg-[#5568ee] px-5 py-3 font-display text-[14px] font-bold text-white transition hover:bg-[#4252c9]" style="box-shadow: 0 4px 0 #3747bb">
        <x-icon name="sparkles" class="h-4 w-4" />
        Buat kuis baru
    </a>
</div>

{{-- Quizzes List (Figma Row Style) --}}
<div class="mt-8 space-y-3.5">
    @forelse ($quizzes as $quiz)
        <div class="flex flex-col justify-between gap-4 rounded-[20px] border border-[#e5e2dc] bg-white p-5 transition hover:border-[#5568ee] hover:shadow-[0_8px_20px_rgba(23,37,75,0.06)] sm:flex-row sm:items-center sm:gap-6">
            <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-[16px] {{ $quiz->isAiGenerated() ? 'bg-[#e9ddff] text-[#6741a8]' : 'bg-[#eafaaf] text-[#466419]' }}">
                    <x-icon :name="$quiz->isAiGenerated() ? 'sparkles' : 'list-checks'" class="h-6 w-6" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-display text-[17px] font-bold text-[#17254b]">{{ $quiz->title }}</h3>
                        @if ($quiz->isAiGenerated())
                            <span class="rounded-full bg-[#e9ddff] px-2.5 py-0.5 text-[10px] font-bold text-[#6741a8]">Dibuat AI</span>
                        @endif
                    </div>
                    <p class="mt-1 text-[13px] text-[#68718a]">
                        {{ $quiz->course ? $quiz->course->name : 'Mata Kuliah' }} · {{ $quiz->questions_count }} soal · {{ $quiz->duration_minutes }} menit
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 self-end sm:self-center">
                <a href="{{ route('kuis.preview', $quiz) }}"
                   class="inline-flex items-center gap-1.5 rounded-[12px] border border-[#d8dbe5] bg-white px-4 py-2 text-[13px] font-bold text-[#17254b] transition hover:border-[#5568ee] hover:text-[#5568ee]">
                    <x-icon name="pencil" class="h-3.5 w-3.5" />
                    Edit
                </a>
                <a href="{{ route('kuis.play', $quiz) }}"
                   class="inline-flex items-center justify-center rounded-[12px] bg-[#5568ee] px-4 py-2 text-[13px] font-bold text-white transition hover:bg-[#4252c9]">
                    Kerjakan
                </a>
            </div>
        </div>
    @empty
        <div class="rounded-[20px] border border-[#e5e2dc] bg-white p-12 text-center">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-[18px] bg-[#e9ebff] text-[#5568ee]">
                <x-icon name="list-checks" class="h-7 w-7" />
            </div>
            <p class="mt-4 font-display text-[16px] font-bold text-[#17254b]">Belum ada kuis yang kamu buat</p>
            <p class="mt-1 text-[13px] text-[#68718a]">Buat kuis latihan sendiri atau generate kilat pakai AI.</p>
            <a href="{{ route('kuis.pilih') }}" class="mt-5 inline-flex items-center gap-2 rounded-[13px] bg-[#5568ee] px-6 py-2.5 font-display text-[14px] font-bold text-white">
                Buat kuis sekarang
            </a>
        </div>
    @endforelse
</div>

@if ($attempts->isNotEmpty())
    <div class="mt-12">
        <h2 class="font-display text-[20px] font-extrabold text-[#17254b]">Riwayat pengerjaan</h2>
        <div class="mt-4 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($attempts as $attempt)
                <div class="flex items-center justify-between rounded-[18px] border border-[#e5e2dc] bg-white p-4">
                    <div>
                        <p class="font-display text-[14px] font-bold text-[#17254b]">{{ $attempt->quiz->title }}</p>
                        <p class="mt-0.5 text-[11px] text-[#68718a]">{{ $attempt->completed_at ? $attempt->completed_at->diffForHumans() : 'Belum selesai' }}</p>
                    </div>
                    <span class="font-display text-[22px] font-extrabold text-[#5568ee]">{{ $attempt->score }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
