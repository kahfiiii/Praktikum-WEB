@extends('layouts.app')

@section('title', 'Kuis AI · Memproses — STUDORA')

@section('content')
<div class="mx-auto max-w-[820px]">
    <div class="h-[24px]">
        <a href="{{ route('kuis.pilih') }}" class="inline-flex items-center gap-1.5 text-[13px] font-bold text-[#68718a] hover:text-[#17254b]">
            ← Kembali pilih mode
        </a>
    </div>

    <div class="pt-[16px]">
        <p class="font-display text-[12px] font-extrabold uppercase tracking-[1.5px] text-[#5568ee]">
            Asisten belajarmu
        </p>
        <h1 class="pt-2 font-display text-[32px] font-semibold leading-tight tracking-[-1.5px] text-[#17254b] sm:text-[48px] sm:leading-[52.8px] sm:tracking-[-2px]">
            Buat kuis dengan AI
        </h1>
        <p class="pt-2 text-[15px] leading-[24.8px] text-[#68718a] sm:text-[16px]">
            Atur bahannya, lalu biarkan AI menyusun draf soal untuk kamu periksa.
        </p>
    </div>

    <div class="pt-[32px]">
        <div class="flex h-[440px] w-full flex-col items-center justify-center rounded-[24px] bg-white px-[30px] py-[50px] shadow-[0px_8px_0px_rgba(23,37,75,0.07)] border border-line-soft/60">
            
            {{-- Animasi Orbit Sesuai Desain Figma --}}
            <div class="relative h-[120px] w-[120px]">
                <div class="absolute inset-0 flex items-center justify-center animate-[spin_12s_linear_infinite]">
                    <div class="relative h-[120px] w-[120px] rounded-full border-2 border-dashed border-[#cfc1ec]">
                        <span class="absolute left-[51.5px] top-[7px] h-[13px] w-[13px] rounded-full bg-[#ff7868]"></span>
                        <span class="absolute left-[101px] top-[79px] h-[13px] w-[13px] rounded-full bg-[#cde969]"></span>
                        <span class="absolute left-[5px] top-[83px] h-[13px] w-[13px] rounded-full bg-[#5568ee]"></span>
                    </div>
                </div>
                {{-- Sparkle Icon di Tengah Orbit --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="text-[#7952d9]">
                        <x-icon name="sparkles" class="h-[34px] w-[34px]" />
                    </span>
                </div>
            </div>

            {{-- Heading & Deskripsi --}}
            <h2 class="mt-6 font-display text-[24px] font-semibold leading-[32px] tracking-[-0.5px] text-[#17254b]">
                Lagi meracik soal yang pas…
            </h2>
            <p class="mt-2 max-w-[450px] text-center text-[15px] leading-[24.8px] text-[#68718a] sm:text-[16px]">
                AI sedang membaca materi dan menyusun pilihan jawaban. Biasanya hanya sebentar.
            </p>

            {{-- Indikator Status Langkah (Horizontal) --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-[24px] text-[11px]">
                <div id="step-1" class="flex items-center gap-[7px] text-[#54741c]">
                    <x-icon name="check" class="h-[14px] w-[14px] text-[#54741c]" stroke="2.5" />
                    <span>Membaca materi</span>
                </div>
                <div id="step-2" class="flex items-center gap-[7px] text-[#5568ee]">
                    <span class="h-[10px] w-[10px] rounded-[5px] border-2 border-[#5568ee] bg-[#5568ee]/20 animate-pulse"></span>
                    <span>Menyusun pertanyaan</span>
                </div>
                <div id="step-3" class="flex items-center gap-[7px] text-[#68718a]">
                    <span class="h-[10px] w-[10px] rounded-[5px] border-2 border-[#c9ccd6]"></span>
                    <span>Memeriksa jawaban</span>
                </div>
            </div>

        </div>
    </div>
</div>

<form id="ai-form" method="POST" action="{{ route('kuis.ai.generate') }}" class="sr-only">
    @csrf
    <input type="hidden" name="course_id" value="{{ $payload['course_id'] ?? '' }}">
    <input type="hidden" name="topic" value="{{ $payload['topic'] ?? '' }}">
    <input type="hidden" name="count" value="{{ $payload['count'] ?? 8 }}">
    <input type="hidden" name="type" value="{{ $payload['type'] ?? 'pilihan-ganda' }}">
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ai-form');

    // Transisi visual tahapan
    setTimeout(() => {
        const step2 = document.getElementById('step-2');
        if (step2) {
            step2.className = 'flex items-center gap-[7px] text-[#54741c]';
            step2.innerHTML = '<span class="inline-flex items-center justify-center h-[14px] w-[14px] text-[#54741c] font-bold text-xs">✓</span><span>Menyusun pertanyaan</span>';
        }
        const step3 = document.getElementById('step-3');
        if (step3) {
            step3.className = 'flex items-center gap-[7px] text-[#5568ee]';
            step3.innerHTML = '<span class="h-[10px] w-[10px] rounded-[5px] border-2 border-[#5568ee] bg-[#5568ee]/20 animate-pulse"></span><span>Memeriksa jawaban</span>';
        }
    }, 1500);

    // Auto submit form untuk generate soal AI
    setTimeout(() => {
        if (form) form.submit();
    }, 600);
});
</script>
@endsection
